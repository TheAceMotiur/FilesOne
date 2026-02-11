<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Models\EmailSettings;
use App\Models\EmailContents;
use App\Models\EmailLogs;
use App\Helpers\AdminHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class EmailsController extends Controller
{
    public function emails(): View
    {
        return view('admin.emails.index', [
            'functions' => 'admin.emails.function',
            'sidebar' => 'emails',
            'pageName' => pageName([__('lang.emails')]),
        ]);
    }

    public function emails_post(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'email' => 'required|email|max:64',
            'email-noreply' => 'required|email|max:64',
            'email-logo' => 'nullable|file|image|max:2048',
            'smtp-host' => 'required|string|max:1000',
            'smtp-port' => 'required|numeric|max_digits:5',
            'smtp-encryption' => 'required|in:none,tls,ssl',
            'smtp-user' => 'required|string|max:255',
            'smtp-password' => 'required|string|max:255',
        ]);

        $inputs = $request->only([
            'email',
            'email-noreply',
            'email-logo',
            'smtp-host',
            'smtp-port',
            'smtp-encryption',
            'smtp-user',
            'smtp-password',
        ]);

        $userId = Auth::id();
        $userIp = $request->ip();

        foreach ($inputs as $key => $value) {
            if ($file = $request->file($key)) {

                $dbKey = str_replace("-", "_", $key);

                $oldFile = emailSetting('email_logo');
                if (file_exists(public_path("uploads/img/other/{$oldFile}"))) {
                    @unlink(public_path("uploads/img/other/{$oldFile}"));
                }

                $fileName = 'email_logo_'
                    . Str::random(29)
                    . '.'
                    . $file->extension();
                $file->move(public_path('uploads/img/other'), $fileName);

                EmailSettings::where("name", $dbKey)
                    ->update([
                        'updated_by_id' => $userId,
                        'updated_by_ip' => $userIp,
                        'value' => $fileName,
                    ]);
            } else {

                $dbKey = str_replace("-", "_", $key);
                EmailSettings::where("name", $dbKey)
                    ->update([
                        'updated_by_id' => $userId,
                        'updated_by_ip' => $userIp,
                        'value' => $value,
                    ]);
            }
        }

        if (Cache::has('emailSetting')) {
            Cache::forget('emailSetting');
        }

        return back()
            ->with('success', __('lang.data_update'));
    }

    public function emails_content(): JsonResponse
    {
        $emailContents = EmailContents::get();

        $contentsArr = [];
        if ($emailContents->isNotEmpty()) {
            foreach ($emailContents as $emailContent) {
                $contentsArr[] = [
                    'name' => ucwords(
                        str_replace('_', ' ', $emailContent['name'])
                    ),
                    'action' => AdminHelper::emailContentsTableButtons(
                        $emailContent['id']
                    ),
                ];
            }
        }

        return response()->json([
            'result' => true,
            'data' => $contentsArr
        ]);
    }

    public function email_contents_edit(
        int $emailId
    ): View {
        $emailContent = EmailContents::where('id', $emailId)
            ->first();

        if (!$emailContent) {
            abort(404);
        }

        return view('admin.emails.edit.index', [
            'functions' => 'admin.emails.edit.function',
            'sidebar' => 'emails',
            'pageName' => pageName([__('lang.emails')]),
            'email' => $emailContent,
            'tags' => explode(',', $emailContent->variables),
            'content_name' => $emailContent->name,
        ]);
    }

    public function email_contents_edit_post(
        Request $request,
        int $emailId
    ): RedirectResponse {
        $request->validate([
            'content' => 'required|max:10000',
        ]);

        $emailContent = EmailContents::where('id', $emailId)
            ->first();

        if (!$emailContent) {
            return back()
                ->with('error', __('lang.data_not_found'));
        }

        $update = EmailContents::where("id", $emailId)
            ->update([
                'updated_by_id' => Auth::id(),
                'updated_by_ip' => $request->ip(),
                'content' => $request->input('content'),
            ]);

        if (!$update) {
            return back()
                ->with('error', __('lang.data_update_error'));
        }

        if (Cache::has('emailContent')) {
            Cache::forget('emailContent');
        }

        return back()
            ->with('success', __('lang.data_update'));
    }

    public function emails_logs_data(): JsonResponse
    {
        $emailLogs = EmailLogs::orderByDesc('created_at')->get();

        $logsArr = [];
        if ($emailLogs->isNotEmpty()) {
            foreach ($emailLogs as $emailLog) {
                $logsArr[] = [
                    'date' => dateFormat($emailLog->created_at),
                    'name' => e($emailLog->name),
                    'email' => e($emailLog->email),
                    'action' => AdminHelper::emailLogsTableButtons(
                        $emailLog->id
                    ),
                ];
            }
        }

        return response()->json([
            'result' => true,
            'data' => $logsArr
        ]);
    }

    public function emails_logs_data_single(
        int $logId
    ): JsonResponse {
        $emailLog = EmailLogs::where('id', $logId)
            ->first();

        $logsArr = [
            'subject' => e($emailLog->subject),
            'message' => e($emailLog->message),
        ];

        return response()->json([
            'result' => true,
            'data' => $logsArr,
        ]);
    }

    public function emails_logs_delete(
        int $logId
    ): RedirectResponse {
        $email = EmailLogs::where('id', $logId)
            ->first();

        if (!$email) {
            return back()
                ->with('error', __('lang.data_not_found'));
        }

        if ($email->delete()) {
            return back()
                ->with('success', __('lang.data_delete'));
        }

        return back()
            ->with('error', __('lang.data_delete_error'));
    }

}
