<?php

namespace App\Helpers;

use App\Models\Upload;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Helpers\PlanHelper;
use App\Helpers\GoogleDriveHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class UploadUrlRestHelper
{
    private static $defaultStorage;

    public function __construct() 
    {
        self::$defaultStorage = defaultStorage()->storage_key;
    }

    public static function upload(
        string $fileUrl, 
        array $formData
    ): array {
        $request = request();
        $token = $request->header('token');

        if (!$token) {
            return [
                false,
                __('lang.user_token_missing')
            ];
        }

        $userData = DB::table('users')
            ->where('api_token', $token)
            ->first();

        if (!$userData) {
            return [
                false,
                __('lang.user_token_wrong')
            ];
        }

        $fileName = pathinfo($fileUrl, PATHINFO_BASENAME) ?? false;
        $fileExt = pathinfo($fileUrl, PATHINFO_EXTENSION) ?? false;
        
        if (!$fileName || !$fileExt) {
            return [
                false,
                __('lang.file_url_incorrect'),
            ];
        }

        $fileExt = strtolower($fileExt);

        // Check file url status
        $fileHeaders = self::fileStatus($fileUrl);
        if ($fileHeaders != '200') {
            return [
                false,
                __('lang.cannot_retrieved_file'),
            ];
        }

        // Check file size
        $fileSize = self::fileSize($fileUrl);
        $fileSize = (int) intval($fileSize) / 1024;
        $maxSize = config("upload.MAX_FILE_SIZE");
        if ($fileSize > $maxSize) {
            return [
                false,
                __("lang.file_max_size",['var' => formatKiloBytes($maxSize)]),
            ];
        }

        // Check file format (Extension)
        $allowedTypes = uploadableTypes('array');
        if (!in_array($fileExt, $allowedTypes)) {
            return [
                false,
                __('lang.file_cannot_upload'),
            ];
        }

        // Check user permission for api usage
        $canIRest = PlanHelper::canIRest(
            $userData->id, 
        );
        if (!$canIRest[0]) {
            return [
                false,
                $canIRest[1], 
            ];
        }

        // Check user permission for upload process
        $canIUpload = PlanHelper::canIUpload(
            $userData->id, 
            $fileName, 
            $fileSize
        );
        if (!$canIUpload[0]) {
            return [
                false,
                $canIUpload[1], 
            ];
        }

        // Start upload
        try {
            $contents = self::fileBody($fileUrl);
            if (!$contents) {
                return [
                    false,
                    __('lang.cannot_retrieved_file'),
                ];
            }

            $fileRandom = Str::random(40);
            $fileName = "{$fileRandom}.{$fileExt}";
            $upload = Storage::disk('temp')->put($fileName, $contents);

            if (!$upload) {
                return [
                    false,
                    __('lang.file_upload_error'),
                ];
            }
            
            $manipulate = self::start(
                $fileName,
                $formData,
                $userData->id
            );

            if ($manipulate[0]) {
                return [
                    true,
                    $manipulate[1],
                ];
            } else {
                Storage::disk('temp')
                    ->delete($fileName);

                return [
                    false,
                    $manipulate[1], 
                ];
            }

        } catch (\Exception $e) {
            Log::error($e);
            return [
                false,
                __('lang.cannot_retrieved_file'),
            ];
        }

    }

    public static function start(
        string $fileName, 
        array $formData,
        int $userId
    ): array {
        $database = self::database(
            $fileName,
            $formData,
            $userId,
        );

        if ($database[0]) {
            return [
                true,
                $database[1],
            ];
        } else {
            return [
                false,
                $database[1],
            ];
        }
    }

    public static function database(
        string $fileName, 
        array $formData,
        int $userId,
    ): array {
        $request = request();

        if (!Storage::disk('temp')->exists($fileName)) {
            return [
                false, 
                __('lang.file_not_found')
            ];
        }

        $userIp = $request->ip();
        $shortKey = shortKey('files', 'short_key', 6);
        $fileNameArr = explode('.', $fileName);
        $fileSize = Storage::disk('temp')
            ->size($fileName);

        $fileData['created_by_id'] = $userId;
        $fileData['created_by_ip'] = $userIp;
        $fileData['updated_by_id'] = $userId;
        $fileData['updated_by_ip'] = $userIp;
        $fileData['filename'] = $fileName;
        $fileData['filesize'] = $fileSize;
        $fileData['filetype'] = $fileNameArr[1];
        $fileData['short_key'] = $shortKey;
        
        // Determine which storage to use
        $selectedDisk = self::$defaultStorage;
        
        // If default storage is Google Drive, check for available space across accounts
        if (str_starts_with(self::$defaultStorage, 'google')) {
            $accountResult = GoogleDriveHelper::findAvailableAccount($fileSize);
            
            if ($accountResult['success']) {
                $selectedDisk = $accountResult['storageKey'];
                Log::info("Selected Google Drive: {$selectedDisk} for file: {$fileName}");
            } else {
                // If no Google Drive account has space or none configured, try to use the default storage key
                Log::warning("Google Drive fallback failed: {$accountResult['message']}. Using default: " . self::$defaultStorage);
                $selectedDisk = self::$defaultStorage;
            }
        }
        
        $fileData['disk'] = $selectedDisk;
        
        if (isset($formData['auto-remove']) && $formData['auto-remove']) {
            $fileData['autoremove'] = $formData['auto-remove'];
        }

        if (isset($formData['password']) && $formData['password']) {
            $fileData['password'] = $formData['password'];
        }

        $create = Upload::create($fileData);

        if ($create) {

            try{
                // Use the selected disk (which might be a different Google Drive account)
                $usedDisk = $fileData['disk'];
                
                Storage::disk($usedDisk)->put(
                    $fileName,
                    Storage::disk('temp')->get($fileName)
                );

                Storage::disk('temp')->delete($fileName);

                return [
                    true, 
                    $shortKey,
                ];
            } catch (\Exception $e) {
                Log::error($e);
                Upload::where('id', $create->id)->delete();
                return [
                    false,
                    __('lang.file_upload_error'),
                ];
            }
        }

        return [
            false, 
            __('lang.file_upload_error'),
        ];
    }
    
    private static function fileBody($url)
    {
        return Http::get($url)->body();
    }

    private static function fileStatus($url)
    {
        $response = Http::get($url);
        return $response->successful()
            ? true
            : false;
    }

    private static function fileSize($url)
    {
        $response = Http::get($url);
        return $response->header('Content-Length');
    }

}
