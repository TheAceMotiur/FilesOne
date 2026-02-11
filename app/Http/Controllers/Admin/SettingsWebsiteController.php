<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Rules\Domain;

class SettingsWebsiteController extends Controller
{
    public function website(): View
    {
        return view('admin.settings.website.index', [
            'functions' => 'admin.settings.website.function',
            'sidebar' => 'website_settings',
            'pageName' => pageName([__('lang.settings'), __('lang.website')]),
        ]);
    }

    public function settings_post_website(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'keywords' => 'required|string|max:1000',
            'og-title' => 'required|string|max:255',
            'og-image' => 'nullable|file|image|max:2048',
            'og-description' => 'required|string|max:1000',
        ]);

        $inputs = $request->only([
            'name',
            'description',
            'keywords',
            'og-title',
            'og-image',
            'og-description',
        ]);

        $userId = Auth::id();
        $userIp = $request->ip();

        foreach ( $inputs as $key => $value ) {
            if ($file = $request->file($key)) {

                $dbKey = str_replace("-", "_", $key);

                $oldFile = setting($dbKey);
                if (file_exists(public_path("uploads/img/other/{$oldFile}"))) {
                    @unlink(public_path("uploads/img/other/{$oldFile}"));
                }

                $fileName = 'og_' . Str::random(37) . '.' . $file->extension();
                $file->move(public_path('uploads/img/other'), $fileName);

                Settings::where("name", $dbKey)
                    ->update([
                        'updated_by_id' => $userId,
                        'updated_by_ip' => $userIp,
                        'value' => $fileName
                    ]);
            } else {
                $dbKey = str_replace("-", "_", $key);
                Settings::where("name", $dbKey)
                    ->update([
                        'updated_by_id' => $userId,
                        'updated_by_ip' => $userIp,
                        'value' => $value
                    ]);
            }
        }

        if (Cache::has('setting')) {
            Cache::forget('setting');
        }

        return back()
            ->with('success', __('lang.data_update'));
    }

    public function settings_post_logo(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'logo-light' => 'nullable|file|image|max:2048',
            'logo-dark' => 'nullable|file|image|max:2048',
            'favicon' => 'nullable|file|image|max:2048',
        ]);

        $inputs = $request->only([
            'logo-light',
            'logo-dark',
            'favicon',
        ]);

        $userId = Auth::id();
        $userIp = $request->ip();

        foreach ($inputs as $key => $value) {
            if ($file = $request->file($key)) {

                $dbKey = str_replace("-", "_", $key);

                $oldFile = setting($dbKey);
                if (file_exists(public_path("uploads/img/other/{$oldFile}"))) {
                    @unlink(public_path("uploads/img/other/{$oldFile}"));
                }

                if ($key == 'logo-light') {

                    $fileName = 'logo_light_'
                        . Str::random(29)
                        . '.'
                        . $file->extension();
                    $file->move(public_path('uploads/img/other'), $fileName);
                } elseif ($key == 'logo-dark') {

                    $fileName = 'logo_dark_'
                        . Str::random(30)
                        . '.'
                        . $file->extension();
                    $file->move(public_path('uploads/img/other'), $fileName);
                } elseif ($key == 'favicon') {

                    $fileName = 'favicon_'
                        . Str::random(32)
                        . '.'
                        . $file->extension();
                    $file->move(public_path('uploads/img/other'), $fileName);
                }

                Settings::where("name", $dbKey)
                    ->update([
                        'updated_by_id' => $userId,
                        'updated_by_ip' => $userIp,
                        'value' => $fileName
                    ]);
            }
        }

        if (Cache::has('setting')) {
            Cache::forget('setting');
        }

        return back()
            ->with('success', __('lang.data_update'));
    }

    public function settings_post_preferences(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'time-zone' => 'required|string|max:255',
            'time-format' => 'required|numeric|in:1,2',
            'loader' => 'required|numeric|in:1,0',
            'loader-style' => 'required|string|max:100',
            'lazyload' => 'required|numeric|in:1,0',
            'minify' => 'required|numeric|in:1,0',
            'defer' => 'required|numeric|in:1,0',
            'email-verification' => 'required|numeric|in:1,0',
            'default-color-mode' => 'required|numeric|in:1,2',
        ]);

        $inputs = $request->only([
            'time-zone',
            'time-format',
            'loader',
            'loader-style',
            'lazyload',
            'minify',
            'defer',
            'email-verification',
            'default-color-mode',
        ]);

        $userId = Auth::id();
        $userIp = $request->ip();

        foreach ( $inputs as $key => $value ) {
            $dbKey = str_replace("-", "_", $key);
            Settings::where("name", $dbKey)
                ->update([
                    'updated_by_id' => $userId,
                    'updated_by_ip' => $userIp,
                    'value' => $value
                ]);
        }

        if (Cache::has('setting')) {
            Cache::forget('setting');
        }

        return back()
            ->with('success', __('lang.data_update'));
    }

    public function settings_post_contact(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'fb-account' => [
                'nullable',
                'url:http,https',
                new Domain('facebook.com')
            ],
            'x-account' => [
                'nullable',
                'url:http,https',
                new Domain('x.com')
            ],
            'in-account' => [
                'nullable',
                'url:http,https',
                new Domain('instagram.com')
            ],
            'li-account' => [
                'nullable',
                'url:http,https',
                new Domain('linkedin.com')
            ],
        ]);

        $inputs = $request->only([
            'fb-account',
            'x-account',
            'in-account',
            'li-account',
        ]);

        $userId = Auth::id();
        $userIp = $request->ip();

        foreach ( $inputs as $key => $value ) {
            $dbKey = str_replace("-", "_", $key);
            Settings::where("name", $dbKey)
                ->update([
                    'updated_by_id' => $userId,
                    'updated_by_ip' => $userIp,
                    'value' => $value
                ]);
        }

        if (Cache::has('setting')) {
            Cache::forget('setting');
        }

        return back()
            ->with('success', __('lang.data_update'));
    }

    public function settings_post_auth(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'go-status' => 'required|numeric|in:1,0',
            'go-client' => 'nullable|string|max:255',
            'go-secret' => 'nullable|string|max:255',
        ]);

        $inputs = $request->only([
            'go-status',
            'go-client',
            'go-secret',
        ]);

        $userId = Auth::id();
        $userIp = $request->ip();

        foreach ( $inputs as $key => $value ) {
            $dbKey = str_replace("-", "_", $key);
            Settings::where("name", $dbKey)
                ->update([
                    'updated_by_id' => $userId,
                    'updated_by_ip' => $userIp,
                    'value' => $value
                ]);
        }

        if (Cache::has('setting')) {
            Cache::forget('setting');
        }

        return back()
            ->with('success', __('lang.data_update'));
    }

    public function settings_post_recaptcha(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'recaptcha-status' => 'required|numeric|in:1,0',
            'recaptcha-site' => 'nullable|string|max:255',
            'recaptcha-secret' => 'nullable|string|max:255',
        ]);

        $inputs = $request->only([
            'recaptcha-status',
            'recaptcha-site',
            'recaptcha-secret',
        ]);

        $userId = Auth::id();
        $userIp = $request->ip();

        foreach ( $inputs as $key => $value ) {
            $dbKey = str_replace("-", "_", $key);
            Settings::where("name", $dbKey)
                ->update([
                    'updated_by_id' => $userId,
                    'updated_by_ip' => $userIp,
                    'value' => $value
                ]);
        }

        if (Cache::has('setting')) {
            Cache::forget('setting');
        }

        return back()
            ->with('success', __('lang.data_update'));
    }

    public function settings_post_ip2location(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'ip2location-token' => 'nullable|string|max:255',
        ]);

        $inputs = $request->only([
            'ip2location-token',
        ]);

        $userId = Auth::id();
        $userIp = $request->ip();

        foreach ( $inputs as $key => $value ) {
            $dbKey = str_replace("-", "_", $key);
            Settings::where("name", $dbKey)
                ->update([
                    'updated_by_id' => $userId,
                    'updated_by_ip' => $userIp,
                    'value' => $value
                ]);
        }

        if (Cache::has('setting')) {
            Cache::forget('setting');
        }

        return back()
            ->with('success', __('lang.data_update'));
    }

    public function settings_post_additional(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'additional-css' => 'nullable|max:10000',
            'additional-js' => 'nullable|max:10000',
        ]);

        $inputs = $request->only([
            'additional-css',
            'additional-js',
        ]);

        $userId = Auth::id();
        $userIp = $request->ip();

        foreach ( $inputs as $key => $value ) {
            $dbKey = str_replace("-", "_", $key);
            Settings::where("name", $dbKey)
                ->update([
                    'updated_by_id' => $userId,
                    'updated_by_ip' => $userIp,
                    'value' => $value
                ]);
        }

        if (Cache::has('setting')) {
            Cache::forget('setting');
        }

        return back()
            ->with('success', __('lang.data_update'));
    }

    public function settings_post_cache(): RedirectResponse
    {
        Cache::flush();
        return back()
            ->with('success', __('lang.data_delete'));
    }

}
