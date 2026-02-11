<?php

namespace App\Helpers;

use App\Models\Upload;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use App\Helpers\PlanHelper;
use App\Helpers\GoogleDriveHelper;
use Illuminate\Support\Facades\Log;

class UploadHelper
{
    private static $defaultStorage;

    public function __construct() 
    {
        self::$defaultStorage = defaultStorage()->storage_key;
    }

    public static function upload(
        object $file, 
        array $formData
    ): array {

        $canIUpload = PlanHelper::canIUpload(
            Auth::id(), 
            $file, 
            $file->getSize()
        );

        if (!$canIUpload[0]) {
            return [
                false,
                $canIUpload[1], 
            ];
        }

        $fileRandom = Str::random(40);
        $fileType = strtolower($file->getClientOriginalExtension());
        $fileName = "{$fileRandom}.{$fileType}";

        $upload = Storage::disk('temp')
            ->putFileAs(
                '/', 
                $file, 
                $fileName, 
                'private'
            );

        if (!$upload) {
            return [
                false,
                __('lang.file_upload_error'), 
            ];
        }

        $manipulate = self::start(
            $fileName,
            $formData
        );

        if ($manipulate[0]) {

            $fileKey = Crypt::encryptString($manipulate[1]);
            request()->session()->push('files', $fileKey);

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
    }

    public static function start(
        string $fileName, 
        array $formData
    ): array {
        $database = self::database(
            $fileName,
            $formData,
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
        array $formData
    ): array {
        $request = request();
        
        if (!Storage::disk('temp')->exists($fileName)) {
            return [
                false, 
                __('lang.file_not_found')
            ];
        }

        $userId = Auth::check()
            ? Auth::user()->id
            : null;
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
                // This allows fallback to the configured disk even if space check fails
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
    
}
