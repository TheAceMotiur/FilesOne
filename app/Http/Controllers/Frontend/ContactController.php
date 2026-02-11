<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Helpers\SeoHelper;
use Illuminate\Support\Facades\Validator;
use Mail;
use App\Mail\ContactMail;
use App\Models\EmailLogs;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function contact(): View
    {
        $seo = SeoHelper::pageSeo('contact');

        return view('frontend.contact.index', [
            'functions' => 'frontend.contact.function',
            'pageKey' => 'contact',
            'seoData' => $seo,
        ]);
    }

    public function contact_post(
        Request $request
    ): bool|string {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:64',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:10000',
        ]);

        if ($validator->fails()) {
            return json_encode([
                'result' => false,
                'data' => __('lang.form_missing'),
                'input' => $validator->errors(),
            ]);
        }

        $mailData = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'subject' => $request->input('subject'),
            'message' => $request->input('message'),
            'ip' => $request->ip(),
        ];

        try {
            Mail::to(emailSetting('email'))
                ->send(new ContactMail($mailData));

            EmailLogs::create([
                'created_by_ip' => $request->ip(),
                'name' => $mailData['name'],
                'email' => $mailData['email'],
                'subject' => $mailData['subject'],
                'message' => $mailData['message'],
            ]);

        } catch (\Exception $e) {

            Log::error($e->getMessage());

            return json_encode([
                'result' => false,
                'data' => __('lang.error'),
            ]);
        }

        return json_encode([
            'result' => true,
            'data' => __('lang.email_send'),
        ]);
    }

}
