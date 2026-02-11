<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Helpers\SeoHelper;
use App\Models\Upload;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Helpers\AnalyticsHelper;
use App\Models\FileReports;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Helpers\GoogleDriveHelper;

class FileController extends Controller
{
    public function file(
        Request $request,
        string $fileKey
    ): View {
        $filekeyArr = explode('.', $fileKey);
        $fileByKey = Upload::whereRaw("BINARY `short_key`= ?", $filekeyArr[0])
            ->first();
        $fileByName = Upload::whereRaw("BINARY `filename`= ?", $fileKey)
            ->first();

        if (!$fileByKey && !$fileByName) {
            abort(404);
        }

        $fileData = $fileByKey ?: $fileByName;
        $fName = $fileData->filename;
        $fKey = $fileData->short_key;

        $check = isset($fileData->password) && $fileData->password
            ? (
                (Auth::check() && $fileData->created_by_id == Auth::id()) 
                    ? true 
                    : $this->checkPermission($fKey)) 
            : true;

        $fileExist = Storage::disk($fileData->disk)
            ->exists($fName);

        $rand = rand();
        $fileUrl = $fileExist 
            ? url("/get/{$fKey}?v={$rand}")
            : url("/assets/image/file-deleted.webp");

        if (
            $fileExist 
            && $check 
            && affiliateSetting('status') == 1 
            && affiliateSetting('type') == 1
        ) {
            AnalyticsHelper::makeAffiliate(
                $fileData->id, 
                $request->ip()
            );
        }

        AnalyticsHelper::makeAnalytics(
            $fileData->id, 
            $request->ip()
        );
        $analyticsGet = AnalyticsHelper::getAnalytics(
            $fileData->id
        );

        if (!$fileExist) {
            $fileData->filetype = 'webp';
        }

        $fileId = Crypt::encryptString($fileData->id);
        $seo = SeoHelper::pageSeo('file', $fileData);

        return view('frontend.file.index', [
            'functions' => 'frontend.file.function',
            'pageKey' => 'file',
            'seoData' => $seo,
            'permission' => $check,
            'file' => $fileData,
            'fileId' => $fileId,
            'fileUrl' => $fileUrl,
            'downloadUrl' => $fileUrl,
            'analytics' => $analyticsGet ?? false,
            'fileExist' => $fileExist,
            'randomClass' => $this->randomKey(),
            'randomFunction' => $this->randomKey(),
        ]);
    }

    public function file_download(
        string $fileKey
    ): mixed {
        $fileData = Upload::whereRaw("BINARY `short_key`= ?", $fileKey)
            ->first();

        if (!$fileData) {
            return response()->json([
                'result' => false,
                'errors' => __('lang.file_not_found'),
            ]);
        }

        if (Storage::disk($fileData->disk)->exists($fileData->filename)) {

            if (
                affiliateSetting('status') == 1 
                && affiliateSetting('type') == 2
            ) {
                AnalyticsHelper::makeAffiliate(
                    $fileData->id, 
                    request()->ip()
                );
            }

            AnalyticsHelper::makeDownloadAnalytics(
                $fileData->id
            );
            
            // Track bandwidth usage for Google Drive accounts (non-blocking)
            if (str_starts_with($fileData->disk, 'google')) {
                try {
                    $accountId = GoogleDriveHelper::getAccountIdByDisk($fileData->disk);
                    if ($accountId) {
                        // Check if account has bandwidth available before allowing download
                        $accounts = GoogleDriveHelper::getGoogleDriveAccounts();
                        $currentAccount = collect($accounts)->firstWhere('id', $accountId);
                        
                        if ($currentAccount) {
                            $bandwidthOk = GoogleDriveHelper::checkBandwidthAvailable($currentAccount, $fileData->filesize);
                            if (!$bandwidthOk) {
                                Log::warning("Bandwidth limit reached for {$fileData->disk}, but allowing download");
                                // Note: Not blocking download, just logging
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning("Bandwidth check failed: " . $e->getMessage());
                    // Continue with download even if bandwidth check fails
                }
            }
            
            try {
                $filename = "{$fileData->short_key}.{$fileData->filetype}";
                $response = Storage::disk($fileData->disk)
                    ->download($fileData->filename, $filename);
                
                // Track bandwidth after successful download for Google Drive (non-blocking)
                if (str_starts_with($fileData->disk, 'google')) {
                    try {
                        $accountId = GoogleDriveHelper::getAccountIdByDisk($fileData->disk);
                        if ($accountId) {
                            GoogleDriveHelper::trackDownload($accountId, $fileData->filesize);
                        }
                    } catch (\Exception $e) {
                        Log::warning("Bandwidth tracking failed: " . $e->getMessage());
                        // Continue - download already succeeded
                    }
                }
                
                return $response;
                
            } catch (\Exception $e) {
                Log::error("Download error for file {$fileData->short_key}: " . $e->getMessage());
                
                // Check if it's a Google Drive quota error
                if (str_contains($e->getMessage(), 'quota') || str_contains($e->getMessage(), 'limit')) {
                    return response()->json([
                        'result' => false,
                        'errors' => __('lang.download_quota_exceeded'),
                    ], 429);
                }
                
                return response()->json([
                    'result' => false,
                    'errors' => __('lang.error'),
                ]);
            }
        }

        return response()->json([
            'result' => false,
            'errors' => __('lang.file_not_found'),
        ]);
    }

    public function file_get_source(
        Request $request,
    ): bool|string {
        try {
            $countDown = countdownTime();
            if ($countDown > 0) {
                sleep($countDown);
            } else {
                sleep(2);
            }
    
            $validator = Validator::make($request->all(), [
                'id' => 'required|string',
                'g-recaptcha-response' => setting('recaptcha_status') == 1
                    ? 'required|recaptchav3:getsource,0.1'
                    : 'nullable',
            ]);
    
            if ($validator->fails()) {
                $errors = $validator->errors();
                if ($errors->has('g-recaptcha-response')) {
                    return json_encode([
                        'result' => false,
                        'data' => __('lang.recaptcha_insufficient_score'),
                    ]);
                } else {
                    return json_encode([
                        'result' => false,
                        'data' => __('lang.error'),
                    ]);
                }
            }
    
            $id = $request->input('id');
    
            try {
                $fileId = Crypt::decryptString($id);
            } catch (DecryptException $e) {
                return json_encode([
                    'result' => false,
                    'data' => __('lang.error'),
                ]);
            }
    
            $fileData = Upload::where('id', $fileId)
                ->first();
    
            if (!$fileData) {
                return json_encode([
                    'result' => false,
                    'data' => __('lang.file_not_found'),
                ]);
            }

            $filename = Crypt::encryptString($fileData->filename);

            return json_encode([
                'result' => true,
                'data' => $filename,
            ]);
        } catch (\Throwable $e) {
            return json_encode([
                'result' => false,
                'data' => __('lang.error'),
            ]);
        }

    }

    public function file_get_link(
        Request $request,
    ): bool|string {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return json_encode([
                'result' => false,
                'data' => __('lang.form_missing'),
            ]);
        }

        $filename = $request->input('name');

        try {
            $filename = Crypt::decryptString($filename);

            $fileData = Upload::whereRaw("BINARY `filename`= ?", $filename)
                ->first();

            if (!$fileData) {
                return json_encode([
                    'result' => false,
                    'data' => __('lang.file_not_found'),
                ]);
            }

            $downloadUrl = URL::temporarySignedRoute(
                'fileDownload', 
                now()->addMinutes(60), 
                ['filekey' => $fileData->short_key]
            );

            return json_encode([
                'result' => true,
                'data' => $downloadUrl,
            ]);

        } catch (DecryptException $e) {
            return json_encode([
                'result' => false,
                'data' => __('lang.error'),
            ]);
        }
    }

    public function file_password(
        Request $request, 
        string $fileKey
    ): RedirectResponse {
        $request->validate([
            'file-password' => 'required|min:3|max:15',
        ]);

        $fileData = Upload::whereRaw("BINARY `short_key`= ?", $fileKey)
            ->first();

        if (!$fileData) {
            return back()->withErrors([
                'error' => __('lang.file_not_found'),
            ]);
        }

        if ($fileData->password != $request->input('file-password')) {
            return back()->withErrors([
                'error' => __('lang.password_incorrect'),
            ]);
        }

        $fileKey = Crypt::encryptString($fileKey);
        $request->session()->push('files', $fileKey);

        return back();
    }

    public function clear(
        Request $request
    ): void {
        $request->session()->forget('files');
    }

    public function report(
        Request $request,
        string $fileKey
    ): JsonResponse {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'reason' => 'required|string|max:10000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'errors' => $validator->errors(),
            ]);
        }

        $fileData = Upload::whereRaw("BINARY `short_key`= ?", $fileKey)
            ->first();

        if (!$fileData) {
            return response()->json([
                'result' => false,
                'data' => __('lang.file_not_found'), 
            ]);
        }

        $userId = Auth::id();
        $userIp = $request->ip();
        $create = FileReports::create([
            'created_by_id' => $userId,
            'created_by_ip' => $userIp,
            'updated_by_id' => $userId,
            'updated_by_ip' => $userIp,
            'file_id' => $fileData->id,
            'file_uploader' => $fileData->created_by_id,
            'reporter' => $request->input('email'),
            'reason' => $request->input('reason'),
        ]);

        if ($create) {
            if (Cache::has('fileReportsCount')) {
                Cache::forget('fileReportsCount');
            }
            return response()->json([
                'result' => true,
                'data' => __('lang.report_send'), 
            ]);
        }

        return response()->json([
            'result' => false,
            'data' => __('lang.report_send_error'), 
        ]);
    }

    private function checkPermission(
        string $fileKey
    ): bool {
        if (Auth::check() && Auth::user()->type == 2) {
            $permission = true;
        } else {
            $filesArr = request()->session()->get('files');

            if (isset($filesArr) && $filesArr) {
                $decryptedArr = [];
                foreach ($filesArr as $file) {
                    try {
                        $decrypted = Crypt::decryptString($file);
                        array_push($decryptedArr, $decrypted);
                    } catch (DecryptException $e) {
                        // Ignore errors
                    }
                }
    
                $permission = in_array($fileKey, $decryptedArr)
                    ? true
                    : false;
            } else {
                $permission = false;
            }
        }

        return $permission;
    }

    private function randomKey() {
        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $charactersNumber = strlen($characters);
        $codeLength = 8;
    
        $code = '';
        while (strlen($code) < $codeLength) {
            $position = rand(0, $charactersNumber - 1);
            $character = $characters[$position];
            $code = $code.$character;
        }
    
        return $code;
    }
    
}
