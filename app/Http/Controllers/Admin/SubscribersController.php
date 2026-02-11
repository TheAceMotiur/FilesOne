<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Models\Subscribers;
use App\Helpers\AdminHelper;
use Illuminate\Http\Request;
use Mail;
use App\Mail\NewsletterMail;
use Illuminate\Support\Facades\Log;

class SubscribersController extends Controller
{
    public function subscribers(): View
    {
        return view('admin.subscribers.index', [
            'functions' => 'admin.subscribers.function',
            'sidebar' => 'subscribers',
            'pageName' => pageName([__('lang.subscribers')]),
        ]);
    }

    public function subscribers_data(): JsonResponse
    {
        $subscribers = Subscribers::orderByDesc('created_at')->get();

        $subscribersArr = [];
        if ($subscribers->isNotEmpty()) {
            foreach ( $subscribers as $subscriber ) {
                $subscribersArr[] = [
                    'email' => $subscriber->email,
                    'date' => dateFormat($subscriber->created_at),
                    'verified' => AdminHelper::subscribersTableBadges(
                        $subscriber->verified,
                        __('lang.yes'),
                        __('lang.no')
                    ),
                    'action' => AdminHelper::subscribersTableButtons(
                        $subscriber->id
                    ),
                ];
            }
        }

        return response()->json([
            'result' => true,
            'data' => $subscribersArr
        ]);
    }

    public function subscribers_delete(
        int $subscriberId
    ): RedirectResponse {
        $subscriber = Subscribers::where('id', $subscriberId)
            ->first();

        if (!$subscriber) {
            return back()
                ->with('error', __('lang.data_not_found'));
        }

        if ($subscriber->delete()) {
            return back()
                ->with('success', __('lang.data_delete'));
        }

        return back()
            ->with('error', __('lang.data_delete_error'));
    }

    public function subscribers_send(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'subject' => 'required|max:255',
            'message' => 'required|max:10000',
        ]);

        $subscribers = Subscribers::where('verified', 1)
            ->get();

        if ($subscribers->isEmpty()) {
            return back()
                ->with('error', __('lang.data_not_found'));
        }

        foreach ( $subscribers as $key => $subscriber ) {

            if (($key % 10) == 0) {
                sleep(1);
            }

            $mailData = [
                'subject' => $request->input('subject'),
                'message' => $request->input('message'),
            ];

            try {
                Mail::to($subscriber->email)
                    ->send(new NewsletterMail($mailData));
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return back()
                    ->with('error', __('lang.email_send_error'));
            }
        }

        return back()
            ->with('success', __('lang.email_send'));
    }

}
