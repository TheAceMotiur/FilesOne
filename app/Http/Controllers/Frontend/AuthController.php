<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Mail;
use App\Mail\WelcomeMail;
use App\Mail\VerificationMail;
use App\Mail\PasswordResetMail;
use App\Mail\PasswordNewMail;
use App\Helpers\SeoHelper;
use Illuminate\Support\Facades\Log;
use App\Helpers\ActivityHelper;

class AuthController extends Controller
{
    public function login(): View
    {
        $seo = SeoHelper::pageSeo('login');

        return view('frontend.auth.login.index', [
            'functions' => 'frontend.auth.login.function',
            'pageKey' => 'login',
            'seoData' => $seo,
        ]);
    }

    public function login_post(
        Request $request
    ): Redirector|RedirectResponse {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
            'remember-me' => 'nullable|in:1',
            'g-recaptcha-response' => setting('recaptcha_status') == 1
                ? 'required|recaptchav3:login,0.5'
                : 'nullable',
        ]);

        $user = User::where('email', $request->input('email'))
            ->first();

        if (!$user) {
            return back()->withErrors([
                'error' => __('auth.failed'),
            ])->onlyInput('email');
        }

        if ($user->verified != 1) {
            $slug = pageSlug('verify_account', true);
            return redirect($slug);
        }

        $login = Auth::attempt(
            [
                'email' => $request->input('email'),
                'password' => $request->input('password')
            ],
            $request->input('remember-me')
        );

        if ($login) {
            ActivityHelper::log($user->id, 'login');
            $request->session()->regenerate();
            $user = Auth::user();
            return ($user->type == 2)
                ? redirect()->route('admin.overview')
                : redirect()->route('home');
        }

        return back()->withErrors([
            'error' => __('auth.failed'),
        ])->onlyInput('email');

    }

    public function login_post_google(
        Request $request
    ): RedirectResponse {
        $googleUser = $this->configDriver()
            ->stateless()
            ->user();
        $user = User::where('email', $googleUser->email)
            ->first();

        if (!$user) {
            $apiToken = uniqueCode('users','api_token');
            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'password' => Hash::make(Str::password(12)),
                'type' => 1,
                'verified' => 1,
                'api_token' => $apiToken,
            ]);
        }

        Auth::loginUsingId($user->id);
        $request->session()->regenerate();

        return redirect()->route('home');
    }

    public function login_post_google_redirect(): mixed
    {
        $google = $this->configDriver();
        return $google->redirect();
    }

    public function register(): View
    {
        $seo = SeoHelper::pageSeo('register');

        return view('frontend.auth.register.index', [
            'functions' => 'frontend.auth.register.function',
            'pageKey' => 'register',
            'seoData' => $seo,
        ]);
    }

    public function register_post(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'terms-of-use' => 'required|accepted',
            'g-recaptcha-response' => setting('recaptcha_status') == 1
                ? 'required|recaptchav3:register,0.5'
                : 'nullable',
        ]);

        $verificationKey = Str::random(80);
        $apiToken = uniqueCode('users','api_token');
        $create = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'type' => 1,
            'verified' => setting('email_verification') == 1 ? 0 : 1,
            'verification_token' => $verificationKey,
            'api_token' => $apiToken,
        ]);

        if ($create) {

            $verifyAccountSlug = pageSlug('verify_account') ?: 'verify-account';
            $verifyUrl = LaravelLocalization::localizeUrl(
                "/{$verifyAccountSlug}/{$verificationKey}"
            );
            $mailData = [
                'email' => $request->input('email'),
                'verifyLink' => $verifyUrl,
                'ip' => $request->ip(),
            ];
            if (setting('email_verification') == 1) {
                try {
                    Mail::to($request->input('email'))
                        ->send(new WelcomeMail($mailData));

                    return redirect()
                        ->route('login')
                        ->with(
                            'success', 
                            __('lang.registration_successful_pending')
                        );
                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                    return back()
                        ->with('error', __('lang.error'));
                }
            } else {
                return redirect()
                    ->route('login')
                    ->with('success', __('lang.registration_successful'));
            }
        }

        return back()
            ->with('error', __('lang.error'));
    }

    public function forgot_password(): View
    {
        $seo = SeoHelper::pageSeo('forgot_password');

        return view('frontend.auth.forgot_password.index', [
            'functions' => 'frontend.auth.forgot_password.function',
            'pageKey' => 'forgot_password',
            'seoData' => $seo,
        ]);
    }

    public function forgot_password_post(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'email' => 'required|email|max:100',
            'g-recaptcha-response' => setting('recaptcha_status') == 1
                ? 'required|recaptchav3:reset_password,0.5'
                : 'nullable',
        ]);

        $user = User::where('email', $request->input('email'))
            ->first();

        if (!$user) {
            return back()
                ->with('success', __('lang.auth_reset_email'));
        }

        $resetToken = Str::random(80);
        $update = $user->update([
            'updated_by_id' => $user->id,
            'updated_by_ip' => $request->ip(),
            'reset_token' => $resetToken,
        ]);

        if (!$update) {
            return back()
                ->with('error', __('lang.error'));
        }

        $forgotPasswordSlug = pageSlug('forgot_password') ?: 'forgot-password';
        $forgotUrl = LaravelLocalization::localizeUrl(
            "/{$forgotPasswordSlug}/{$resetToken}"
        );
        $mailData = [
            'email' => $user->email,
            'resetLink' => $forgotUrl,
            'ip' => $request->ip(),
        ];

        try {
            Mail::to($mailData['email'])
                ->send(new PasswordResetMail($mailData));

            return back()
                ->with('success', __('lang.auth_reset_email'));

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return back()
                ->with('error', __('lang.error'));
        }
    }

    public function forgot_password_reset(
        Request $request,
        string $resetToken
    ): mixed {
        $user = User::where('reset_token', $resetToken)
            ->first();

        if (!$user) {
            abort(404);
        }

        $password = Str::random(8);
        $update = $user->update([
            'updated_by_ip' => $request->ip(),
            'updated_by_id' => $user->id,
            'password' => Hash::make($password),
            'reset_token' => null,
        ]);

        if (!$update) {
            return notice(
                __('lang.error_title'),
                __('lang.error')
            );
        }

        $mailData = [
            'email' => $user->email,
            'password' => $password,
            'ip' => $request->ip(),
        ];

        try {
            Mail::to($mailData['email'])
                ->send(new PasswordNewMail($mailData));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return back()
                ->with('error', __('lang.error'));
        }

        return notice(
            __('lang.auth_reset_successful'),
            __('lang.auth_new_password')
        );
    }

    public function verify_account(): View {
        $seo = SeoHelper::pageSeo('verify_account');

        return view('frontend.auth.verification.index', [
            'functions' => 'frontend.auth.verification.function',
            'pageKey' => 'verify_account',
            'seoData' => $seo,
        ]);
    }

    public function verify_account_post(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'email' => 'required|email|max:100',
            'g-recaptcha-response' => setting('recaptcha_status') == 1
                ? 'required|recaptchav3:verify,0.5'
                : 'nullable',
        ]);

        $user = User::where('email', $request->input('email'))
            ->where('verified', 0)
            ->first();

        if (!$user) {
            return back()
                ->with('success', __('lang.auth_verification_email'));
        }

        $verificationKey = Str::random(80);
        $update = $user->update([
            'updated_by_id' => $user->id,
            'updated_by_ip' => $request->ip(),
            'verification_token' => $verificationKey,
        ]);
        if (!$update) {
            return back()
                ->with('error', __('lang.error'));
        }

        $verifyAccountSlug = pageSlug('verify_account') ?: 'verify-account';
        $verifyUrl = LaravelLocalization::localizeUrl(
            "/{$verifyAccountSlug}/{$verificationKey}"
        );
        $mailData = [
            'email' => $user->email,
            'verifyLink' => $verifyUrl,
            'ip' => $request->ip(),
        ];
        try {
            Mail::to($mailData['email'])
                ->send(new VerificationMail($mailData));

            return back()
                ->with('success', __('lang.auth_verification_email'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return back()
                ->with('error', __('lang.error'));
        }
    }

    public function verify_account_verified(
        Request $request,
        string $verificationKey
    ): mixed {
        $user = User::where('verification_token', $verificationKey)
            ->where('verified', 0)
            ->first();

        if (!$user) {
            abort(404);
        }

        $update = $user->update([
            'updated_by_id' => $user->id,
            'updated_by_ip' => $request->ip(),
            'verification_token' => NULL,
            'verified' => 1,
        ]);

        if (!$update) {
            return notice(
                __('lang.error_title'),
                __('lang.error')
            );
        }

        $request->session()->forget('verify');

        return notice(
            __('lang.auth_verification_successful'),
            __('lang.auth_start_using')
        );

    }

    public function logout(
        Request $request
    ): RedirectResponse {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    private function configDriver(): mixed 
    {
        $callbackUrl = LaravelLocalization::localizeUrl(
            "/login/google/callback"
        );
        $config = [
            'client_id' => setting('go_client'),
            'client_secret' => setting('go_secret'),
            'redirect' => $callbackUrl
        ];
        $provider = Socialite::buildProvider(
            GoogleProvider::class,
            $config
        );

        return $provider;
    }

}
