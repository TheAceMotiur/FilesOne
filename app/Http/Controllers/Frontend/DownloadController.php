<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Upload;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use STS\ZipStream\Facades\Zip;
use Illuminate\Support\Facades\Log;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Helpers\GoogleDriveHelper;

class DownloadController extends Controller
{
    public function get_zip(
        Request $request
    ): mixed {
        try{
            sleep(1);
            $fileKeys = $request->input('keys');
            $fileKeysArr = explode(',',$fileKeys);

            if (count($fileKeysArr) > 0) {

                $zipKey = $this->randomKey();
                $zip = Zip::create("{$zipKey}.zip");

                foreach ($fileKeysArr AS $fileKey) {
                    $file = Upload::whereRaw("BINARY `short_key`= ?", $fileKey)
                        ->first();
                    if ($file) {
                        $zip->addFromDisk(
                            $file->disk, 
                            $file->filename,
                            "{$file->short_key}.{$file->filetype}"
                        );
                    }
                }

                if ($zip->saveToDisk("temp", "/")) {
                    $downloadUrl = LaravelLocalization::localizeUrl(
                        "/download-zip/{$zipKey}"
                    );
                    return response()->json([
                        'result' => true,
                        'data' => $downloadUrl,
                    ]);
                } else {
                    return response()->json([
                        'result' => false,
                        'data' => __('lang.error'),
                    ]);
                }
            }

            return response()->json([
                'result' => false,
                'data' => __('lang.file_not_found'),
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return [
                false,
                __('lang.error'),
            ];
        }

    }

    public function download_zip(string $zipKey): mixed {
        try{
            return Storage::disk('temp')->download("{$zipKey}.zip");
        } catch (\Exception $e) {
            Log::error($e);
            return [
                false,
                __('lang.error'),
            ];
        }
    }
    
    public function get_file(
        Request $request
    ): mixed {
        try{
            sleep(1);
            $fileKey = $request->input('keys');

            if ($fileKey) {
                $file = Upload::whereRaw("BINARY `short_key`= ?", $fileKey)
                    ->first();

                if ($file) {
                    $downloadUrl = LaravelLocalization::localizeUrl(
                        "/download-single/{$file->short_key}"
                    );
                    return response()->json([
                        'result' => true,
                        'data' => $downloadUrl,
                    ]);
                } else {
                    return response()->json([
                        'result' => false,
                        'data' => __('lang.file_not_found'),
                    ]);
                }
            }

            return response()->json([
                'result' => false,
                'data' => __('lang.file_not_found'),
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return [
                false,
                __('lang.error'),
            ];
        }

    }

    public function download_file(string $fileKey): mixed {
        try{
            $file = Upload::whereRaw("BINARY `short_key`= ?", $fileKey)
                ->first();
                
            if (!$file) {
                return response()->json([
                    'result' => false,
                    'data' => __('lang.file_not_found'),
                ]);
            }
            
            // Track bandwidth usage for Google Drive accounts (non-blocking)
            if (str_starts_with($file->disk, 'google')) {
                try {
                    $accountId = GoogleDriveHelper::getAccountIdByDisk($file->disk);
                    if ($accountId) {
                        // Check if account has bandwidth available before allowing download
                        $accounts = GoogleDriveHelper::getGoogleDriveAccounts();
                        $currentAccount = collect($accounts)->firstWhere('id', $accountId);
                        
                        if ($currentAccount) {
                            $bandwidthOk = GoogleDriveHelper::checkBandwidthAvailable($currentAccount, $file->filesize);
                            if (!$bandwidthOk) {
                                Log::warning("Bandwidth limit reached for {$file->disk}, but allowing download");
                                // Note: Not blocking download, just logging
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning("Bandwidth check failed: " . $e->getMessage());
                    // Continue with download even if bandwidth check fails
                }
            }
            
            $filename = "{$file->short_key}.{$file->filetype}";
            $response = Storage::disk($file->disk)
                ->download($file->filename, $filename);
            
            // Track bandwidth after successful download for Google Drive (non-blocking)
            if (str_starts_with($file->disk, 'google')) {
                try {
                    $accountId = GoogleDriveHelper::getAccountIdByDisk($file->disk);
                    if ($accountId) {
                        GoogleDriveHelper::trackDownload($accountId, $file->filesize);
                    }
                } catch (\Exception $e) {
                    Log::warning("Bandwidth tracking failed: " . $e->getMessage());
                    // Continue - download already succeeded
                }
            }
            
            return $response;
            
        } catch (\Exception $e) {
            Log::error($e);
            
            // Check if it's a Google Drive quota error
            if (str_contains($e->getMessage(), 'quota') || str_contains($e->getMessage(), 'limit')) {
                return response()->json([
                    'result' => false,
                    'data' => __('lang.download_quota_exceeded'),
                ], 429);
            }
            
            return [
                false,
                __('lang.error'),
            ];
        }
    }
    
    private function randomKey(): mixed {
        return Str::random(16);
    }

}
