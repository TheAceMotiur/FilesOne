<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Storages;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class SettingsStorageController extends Controller
{
    public function storage(): View
    {
        $defaultStorage = storageSetting(1);
        $amazonS3 = storageSetting(2);
        $cloudflareR2 = storageSetting(3);
        $wasabi = storageSetting(4);
        $ftp = storageSetting(5);
        
        // Get all Google Drive accounts dynamically
        $googleDrives = Storages::where('storage_key', 'like', 'google%')
            ->orderBy('storage_key')
            ->get();

        return view('admin.settings.storage.index', [
            'functions' => 'admin.settings.storage.function',
            'sidebar' => 'storage_settings',
            'pageName' => pageName([__('lang.settings'), __('lang.storage')]),
            'defaultStorage' => $defaultStorage,
            'amazonS3' => $amazonS3,
            'cloudflareR2' => $cloudflareR2,
            'wasabi' => $wasabi,
            'ftp' => $ftp,
            'googleDrives' => $googleDrives,
        ]);
    }

    public function storage_post(
        Request $request
    ): RedirectResponse {
        $storageData = Storages::get();
        $storageIds = [];
        foreach ($storageData as $value) {
            array_push($storageIds, $value->id);
        }

        $defaultStorageId = $request->input('default-storage');
        if (!in_array($defaultStorageId, $storageIds)) {
            return back()
                ->with('error', __('lang.data_update_error'));
        }

        foreach ($storageData as $value) {
            Storages::where('id', $value->id)
                ->update([
                    'default' => null,
                ]);
        }

        $update = Storages::where('id', $defaultStorageId)
            ->update([
                'default' => true,
            ]);

        if ($update) {

            if (Cache::has('storageSetting')) {
                Cache::forget('storageSetting');
            }

            return back()
                ->with('success', __('lang.data_update'));
        }

        return back()
            ->with('error', __('lang.data_update_error'));
    }

    public function storage_s3_post(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'amazon-key' => 'required|string|max:100',
            'amazon-secret' => 'required|string|max:100',
            'amazon-region' => 'required|string|max:100',
            'amazon-bucket' => 'required|string|max:100',
            'amazon-url' => 'required|url|max:100',
        ]);

        $userId = Auth::id();
        $userIp = $request->ip();
        $value = json_encode([
            "access_key_id" => $request->input('amazon-key'),
            "secret_access_key" => $request->input('amazon-secret'),
            "default_region" => $request->input('amazon-region'),
            "bucket" => $request->input('amazon-bucket'),
            "url" => $request->input('amazon-url'),
        ]);

        $update = Storages::where('id', 2)
            ->update([
                'updated_by_id' => $userId,
                'updated_by_ip' => $userIp,
                'value' => $value
            ]);

        if ($update) {

            if (Cache::has('storageSetting')) {
                Cache::forget('storageSetting');
            }

            return back()
                ->with('success', __('lang.data_update'));
        }

        return back()
            ->with('error', __('lang.data_update_error'));
    }

    public function storage_r2_post(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'cloudflare-key' => 'required|string|max:100',
            'cloudflare-secret' => 'required|string|max:100',
            'cloudflare-bucket' => 'required|string|max:100',
            'cloudflare-url' => 'required|url|max:100',
            'cloudflare-endpoint' => 'required|url|max:100',
        ]);

        $userId = Auth::id();
        $userIp = $request->ip();
        $value = json_encode([
            "access_key_id" => $request->input('cloudflare-key'),
            "secret_access_key" => $request->input('cloudflare-secret'),
            "bucket" => $request->input('cloudflare-bucket'),
            "endpoint" => $request->input('cloudflare-endpoint'),
            "url" => $request->input('cloudflare-url'),
        ]);

        $update = Storages::where('id', 3)
            ->update([
                'updated_by_id' => $userId,
                'updated_by_ip' => $userIp,
                'value' => $value
            ]);

        if ($update) {

            if (Cache::has('storageSetting')) {
                Cache::forget('storageSetting');
            }

            return back()
                ->with('success', __('lang.data_update'));
        }

        return back()
            ->with('error', __('lang.data_update_error'));
    }

    public function storage_wasabi_post(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'wasabi-key' => 'required|string|max:100',
            'wasabi-secret' => 'required|string|max:100',
            'wasabi-region' => 'required|string|max:100',
            'wasabi-bucket' => 'required|string|max:100',
            'wasabi-url' => 'required|url|max:100',
        ]);

        $userId = Auth::id();
        $userIp = $request->ip();
        $value = json_encode([
            "access_key_id" => $request->input('wasabi-key'),
            "secret_access_key" => $request->input('wasabi-secret'),
            "default_region" => $request->input('wasabi-region'),
            "bucket" => $request->input('wasabi-bucket'),
            "url" => $request->input('wasabi-url'),
        ]);

        $update = Storages::where('id', 4)
            ->update([
                'updated_by_id' => $userId,
                'updated_by_ip' => $userIp,
                'value' => $value
            ]);

        if ($update) {

            if (Cache::has('storageSetting')) {
                Cache::forget('storageSetting');
            }

            return back()
                ->with('success', __('lang.data_update'));
        }

        return back()
            ->with('error', __('lang.data_update_error'));
    }

    public function storage_ftp_post(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'ftp-host' => 'required|string|max:100',
            'ftp-username' => 'required|string|max:100',
            'ftp-password' => 'required|string|max:100',
            'ftp-port' => 'required|numeric',
            'ftp-path' => 'required|string|max:100',
            'ftp-url' => 'required|string|max:100',
        ]);

        $userId = Auth::id();
        $userIp = $request->ip();
        $value = json_encode([
            "host" => $request->input('ftp-host'),
            "username" => $request->input('ftp-username'),
            "password" => $request->input('ftp-password'),
            "port" => $request->input('ftp-port'),
            "path" => $request->input('ftp-path'),
            "url" => $request->input('ftp-url'),
        ], JSON_UNESCAPED_SLASHES);

        $update = Storages::where('id', 5)
            ->update([
                'updated_by_id' => $userId,
                'updated_by_ip' => $userIp,
                'value' => $value
            ]);

        if ($update) {

            if (Cache::has('storageSetting')) {
                Cache::forget('storageSetting');
            }

            return back()
                ->with('success', __('lang.data_update'));
        }

        return back()
            ->with('error', __('lang.data_update_error'));
    }
    
    public function storage_google_post(
        Request $request,
        int $id
    ): RedirectResponse {
        $request->validate([
            'google-client-id' => 'required|string|max:500',
            'google-client-secret' => 'required|string|max:500',
            'google-refresh-token' => 'required|string|max:500',
            'google-folder' => 'nullable|string|max:100',
            'bandwidth-limit' => 'nullable|numeric|min:1|max:10000',
        ]);

        $userId = Auth::id();
        $userIp = $request->ip();
        $value = json_encode([
            "client_id" => $request->input('google-client-id'),
            "client_secret" => $request->input('google-client-secret'),
            "refresh_token" => $request->input('google-refresh-token'),
            "folder" => $request->input('google-folder'),
        ]);
        
        // Convert GB to bytes for bandwidth limit
        $bandwidthLimitGB = $request->input('bandwidth-limit', 700);
        $bandwidthLimitBytes = $bandwidthLimitGB * 1024 * 1024 * 1024;

        $update = Storages::where('id', $id)
            ->update([
                'updated_by_id' => $userId,
                'updated_by_ip' => $userIp,
                'value' => $value,
                'bandwidth_limit' => $bandwidthLimitBytes
            ]);

        if ($update) {

            if (Cache::has('storageSetting')) {
                Cache::forget('storageSetting');
            }
            
            if (Cache::has('googleDriveAccounts')) {
                Cache::forget('googleDriveAccounts');
            }

            return back()
                ->with('success', __('lang.data_update'));
        }

        return back()
            ->with('error', __('lang.data_update_error'));
    }
    
    public function storage_google_add(): View
    {
        return view('admin.settings.storage.google-add', [
            'functions' => 'admin.settings.storage.function',
            'sidebar' => 'storage_settings',
            'pageName' => pageName([__('lang.settings'), __('lang.storage'), __('lang.add_google_drive')]),
        ]);
    }
    
    public function storage_google_add_post(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'name' => 'required|string|max:100',
            'google-client-id' => 'required|string|max:500',
            'google-client-secret' => 'required|string|max:500',
            'google-refresh-token' => 'required|string|max:500',
            'google-folder' => 'nullable|string|max:100',
        ]);

        $userId = Auth::id();
        $userIp = $request->ip();
        
        // Find the next available Google Drive storage key
        $existingKeys = Storages::where('storage_key', 'like', 'google%')
            ->pluck('storage_key')
            ->toArray();
        
        $nextNumber = 1;
        if (in_array('google', $existingKeys)) {
            $nextNumber = 2;
            while (in_array("google_{$nextNumber}", $existingKeys)) {
                $nextNumber++;
            }
        }
        
        $storageKey = $nextNumber === 1 ? 'google' : "google_{$nextNumber}";
        
        $value = json_encode([
            "client_id" => $request->input('google-client-id'),
            "client_secret" => $request->input('google-client-secret'),
            "refresh_token" => $request->input('google-refresh-token'),
            "folder" => $request->input('google-folder'),
        ]);

        $create = Storages::create([
            'created_by_id' => $userId,
            'created_by_ip' => $userIp,
            'updated_by_id' => $userId,
            'updated_by_ip' => $userIp,
            'name' => $request->input('name'),
            'value' => $value,
            'storage_key' => $storageKey,
            'default' => 0,
        ]);

        if ($create) {

            if (Cache::has('storageSetting')) {
                Cache::forget('storageSetting');
            }

            return redirect(LaravelLocalization::localizeUrl('/admin/settings/storage?tab=storages'))
                ->with('success', __('lang.data_added'));
        }

        return back()
            ->with('error', __('lang.data_add_error'));
    }
    
    public function storage_google_delete(
        int $id
    ): RedirectResponse {
        $storage = Storages::find($id);
        
        if (!$storage || !str_starts_with($storage->storage_key, 'google')) {
            return back()
                ->with('error', __('lang.data_delete_error'));
        }
        
        // Prevent deletion if it's the default storage
        if ($storage->default == 1) {
            return back()
                ->with('error', __('lang.cannot_delete_default_storage'));
        }
        
        // Check if any files are using this storage
        $filesCount = \App\Models\Upload::where('disk', $storage->storage_key)->count();
        
        if ($filesCount > 0) {
            return back()
                ->with('error', __('lang.cannot_delete_storage_with_files') . " ({$filesCount} files)");
        }
        
        $delete = Storages::where('id', $id)->delete();

        if ($delete) {

            if (Cache::has('storageSetting')) {
                Cache::forget('storageSetting');
            }

            return back()
                ->with('success', __('lang.data_delete'));
        }

        return back()
            ->with('error', __('lang.data_delete_error'));
    }
    
    public function storage_google_test(
        int $id
    ): RedirectResponse {
        $storage = Storages::find($id);
        
        if (!$storage || !str_starts_with($storage->storage_key, 'google')) {
            return back()
                ->with('error', __('lang.storage_not_found'));
        }
        
        try {
            $features = json_decode($storage->value, true);
            
            // Test by checking Google Drive space
            $disk = Storage::disk($storage->storage_key);
            $adapter = $disk->getAdapter();
            $service = $adapter->getService();
            
            $about = $service->about->get(['fields' => 'storageQuota,user']);
            $storageQuota = $about->getStorageQuota();
            $user = $about->getUser();
            
            $limit = $storageQuota->getLimit();
            $usage = $storageQuota->getUsage();
            
            $limitFormatted = $limit ? number_format($limit / (1024 * 1024 * 1024), 2) . ' GB' : 'Unlimited';
            $usageFormatted = number_format($usage / (1024 * 1024 * 1024), 2) . ' GB';
            $availableFormatted = $limit ? number_format(($limit - $usage) / (1024 * 1024 * 1024), 2) . ' GB' : 'Unlimited';
            
            $message = "Connection successful! User: {$user->getEmailAddress()}, Total: {$limitFormatted}, Used: {$usageFormatted}, Available: {$availableFormatted}";
            
            return back()
                ->with('success', $message);
                
        } catch (\Exception $e) {
            Log::error("Google Drive test failed for {$storage->storage_key}: " . $e->getMessage());
            return back()
                ->with('error', 'Connection failed: ' . $e->getMessage());
        }
    }
    
    public function temp_files(): RedirectResponse {
        $tempFiles = Storage::disk('temp')->allFiles();

        foreach ($tempFiles as $file) {
            Storage::disk('temp')->delete($file);
        }

        return back()
            ->with('success', __('lang.data_delete'));
    }

}
