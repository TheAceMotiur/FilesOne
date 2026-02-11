<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Mail;
use App\Mail\SubscriberVerificationMail;
use App\Models\Subscribers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class SubscriberController extends Controller
{

    public function subscribe(
        Request $request
    ) {
        $validator = Validator::make($request->all(), [
            'subscription-email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return json_encode([
                'result' => false,
                'data' => $validator->errors(),
            ]);
        }

        $email = $request->input('subscription-email');
        $subscriber = Subscribers::where('email', $email)
            ->first();

        if ($subscriber && $subscriber->verified == 1) {
            return json_encode([
                'result' => false,
                'data' => __('lang.subscriber_email_exist'),
            ]);
        }

        if ($subscriber && $subscriber->verified != 1) {
            $subscriber->delete();
        }

        $verificationCode = Str::random(100);
        $user_ip = $request->ip();
        $create = Subscribers::create([
            'created_by_ip' => $user_ip,
            'updated_by_ip' => $user_ip,
            'email' => $email,
            'verification_code' => $verificationCode,
            'verified' => 0,
        ]);

        if (!$create) {
            return json_encode([
                'result' => true,
                'data' => __('lang.error'),
            ]);
        }

        $verifyUrl = LaravelLocalization::localizeUrl(
            "/subscribe/{$verificationCode}"
        );
        $mailData = [
            'email' => $email,
            'verifyLink' => $verifyUrl,
            'ip' => $user_ip,
        ];

        try {
            Mail::to($email)
                ->send(new SubscriberVerificationMail($mailData));

            return json_encode([
                'result' => true,
                'data' => __('lang.auth_email_confirmation'),
            ]);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return json_encode([
                'result' => false,
                'data' => __('lang.error'),
            ]);
        }
    }

    public function subscribe_verify(
        Request $request,
        string $key
    ) {
        $subscriber = Subscribers::where('verification_code', $key)
            ->where('verified', 0)
            ->first();

        if (!$subscriber) {
            abort(404);
        }

        $update = Subscribers::where('verification_code', $key)
            ->update([
                'updated_by_ip' => $request->ip(),
                'verification_code' => NULL,
                'verified' => 1,
            ]);

        if (!$update) {
            return notice(
                __('lang.error_title'),
                __('lang.error')
            );
        }

        return notice(
            __('lang.success_title'),
            __('lang.subscriber_verified')
        );
    }

}
