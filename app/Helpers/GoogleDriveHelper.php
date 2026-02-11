<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\Storages;

class GoogleDriveHelper
{
    /**
     * Get available Google Drive storage accounts ordered by priority
     * 
     * @return array
     */
    public static function getGoogleDriveAccounts(): array
    {
        if (
            env('INSTALLED') == '%installed%' 
            && !request()->routeIs('install')
        ) {
            // Get all Google Drive storage configurations directly from database
            // Use cache if available, otherwise query database
            $accounts = Cache::remember('googleDriveAccounts', 3600, function () {
                return Storages::where('storage_key', 'like', 'google%')
                    ->orderBy('storage_key')
                    ->get()
                    ->toArray();
            });
            
            return $accounts;
        }
        
        return [];
    }
    
    /**
     * Check available space on a Google Drive account
     * 
     * @param string $diskName The disk name (e.g., 'google', 'google_2')
     * @return array [hasSpace: bool, availableBytes: int, errorMessage: string]
     */
    public static function checkAvailableSpace(string $diskName, int $requiredBytes): array
    {
        try {
            $disk = Storage::disk($diskName);
            
            // Get the Google Drive service
            $adapter = $disk->getAdapter();
            $service = $adapter->getService();
            
            // Get storage quota information
            $about = $service->about->get(['fields' => 'storageQuota']);
            $storageQuota = $about->getStorageQuota();
            
            $limit = $storageQuota->getLimit();
            $usage = $storageQuota->getUsage();
            
            // If limit is null, the account has unlimited storage (Google Workspace)
            if ($limit === null) {
                return [
                    'hasSpace' => true,
                    'availableBytes' => PHP_INT_MAX,
                    'errorMessage' => ''
                ];
            }
            
            $availableBytes = $limit - $usage;
            $hasSpace = $availableBytes >= $requiredBytes;
            
            return [
                'hasSpace' => $hasSpace,
                'availableBytes' => $availableBytes,
                'errorMessage' => $hasSpace ? '' : 'Insufficient storage space'
            ];
            
        } catch (\Exception $e) {
            Log::error("Error checking Google Drive space for {$diskName}: " . $e->getMessage());
            return [
                'hasSpace' => false,
                'availableBytes' => 0,
                'errorMessage' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Find the best Google Drive account for upload (considers both space and bandwidth)
     * Prioritizes accounts with lower bandwidth usage to distribute download load
     * 
     * @param int $requiredBytes The file size in bytes
     * @return array [success: bool, diskName: string, storageKey: string, message: string]
     */
    public static function findAvailableAccount(int $requiredBytes): array
    {
        $accounts = self::getGoogleDriveAccounts();
        
        if (empty($accounts)) {
            return [
                'success' => false,
                'diskName' => '',
                'storageKey' => '',
                'message' => 'No Google Drive accounts configured'
            ];
        }
        
        $viableAccounts = [];
        $spaceToo = []; // Fallback: accounts with just enough space (ignore bandwidth)
        
        foreach ($accounts as $account) {
            $diskName = $account['storage_key'];
            
            // Check both storage space and bandwidth availability
            try {
                // Check storage space first
                $spaceCheck = self::checkAvailableSpace($diskName, $requiredBytes);
                
                if (!$spaceCheck['hasSpace']) {
                    Log::warning("Google Drive {$diskName} has insufficient space. Required: " . 
                        number_format($requiredBytes / (1024 * 1024), 2) . " MB, Available: " .
                        number_format($spaceCheck['availableBytes'] / (1024 * 1024), 2) . " MB");
                    continue;
                }
                
                // Account has space - add to fallback list
                $spaceOnly[] = [
                    'diskName' => $diskName,
                    'bandwidth_used' => 0,
                    'available_space' => $spaceCheck['availableBytes']
                ];
                
                // Try bandwidth checking (but don't fail if it errors)
                try {
                    // Reset bandwidth if needed
                    self::resetBandwidthIfNeeded($account['id']);
                    
                    // Check bandwidth availability (refresh account data after potential reset)
                    $freshAccount = Storages::find($account['id']);
                    if (!$freshAccount) {
                        Log::warning("Account {$account['id']} not found in database, skipping bandwidth check");
                        continue; // Still have spaceOnly entry as fallback
                    }
                    
                    $freshAccountArray = $freshAccount->toArray();
                    if (!self::checkBandwidthAvailable($freshAccountArray, $requiredBytes)) {
                        Log::warning("Google Drive {$diskName} approaching bandwidth limit");
                        continue; // Still have spaceOnly entry as fallback
                    }
                    
                    // This account is viable with both space AND bandwidth - add to priority list
                    $viableAccounts[] = [
                        'diskName' => $diskName,
                        'bandwidth_used' => $freshAccountArray['bandwidth_used'] ?? 0,
                        'available_space' => $spaceCheck['availableBytes']
                    ];
                } catch (\Exception $bandwidthError) {
                    Log::warning("Error checking bandwidth for {$diskName}: " . $bandwidthError->getMessage());
                    // Continue - still have spaceOnly entry as fallback
                }
                
            } catch (\Exception $e) {
                Log::error("Error checking Google Drive {$diskName}: " . $e->getMessage());
                continue;
            }
        }
        
        // Prefer accounts that passed bandwidth checks, fallback to any account with space
        $accountsToUse = !empty($viableAccounts) ? $viableAccounts : $spaceOnly;
        
        if (empty($accountsToUse)) {
            return [
                'success' => false,
                'diskName' => '',
                'storageKey' => '',
                'message' => 'All Google Drive accounts are full or unavailable'
            ];
        }
        
        // Sort by bandwidth usage (lowest first) to distribute load
        usort($accountsToUse, function($a, $b) {
            return $a['bandwidth_used'] <=> $b['bandwidth_used'];
        });
        
        $selectedAccount = $accountsToUse[0];
        $diskName = $selectedAccount['diskName'];
        
        $bandwidthInfo = $selectedAccount['bandwidth_used'] > 0 
            ? "Bandwidth used: " . number_format($selectedAccount['bandwidth_used'] / (1024 * 1024 * 1024), 2) . " GB"
            : "Bandwidth tracking not available";
        
        Log::info("Selected Google Drive: {$diskName} for upload. " . 
            "Space: " . number_format($selectedAccount['available_space'] / (1024 * 1024), 2) . " MB, " .
            $bandwidthInfo);
        
        return [
            'success' => true,
            'diskName' => $diskName,
            'storageKey' => $diskName,
            'message' => 'Found available storage'
        ];
    }
    
    /**
     * Upload file to Google Drive with automatic account selection
     * 
     * @param string $fileName The file name
     * @param string $fileContent The file content
     * @param int $fileSize The file size in bytes
     * @return array [success: bool, diskName: string, message: string]
     */
    public static function uploadWithFallback(string $fileName, string $fileContent, int $fileSize): array
    {
        $accountResult = self::findAvailableAccount($fileSize);
        
        if (!$accountResult['success']) {
            return [
                'success' => false,
                'diskName' => '',
                'message' => $accountResult['message']
            ];
        }
        
        $diskName = $accountResult['diskName'];
        
        try {
            Storage::disk($diskName)->put($fileName, $fileContent);
            
            Log::info("Successfully uploaded {$fileName} to {$diskName}");
            
            return [
                'success' => true,
                'diskName' => $diskName,
                'message' => 'File uploaded successfully'
            ];
            
        } catch (\Exception $e) {
            Log::error("Failed to upload {$fileName} to {$diskName}: " . $e->getMessage());
            
            return [
                'success' => false,
                'diskName' => '',
                'message' => 'Upload failed: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Reset bandwidth counter if 24 hours have passed
     * 
     * @param int $accountId The storage account ID
     * @return void
     */
    public static function resetBandwidthIfNeeded(int $accountId): void
    {
        try {
            $account = Storages::find($accountId);
            
            if (!$account) {
                return;
            }
            
            // If bandwidth_reset_at is null or more than 24 hours ago, reset
            if (
                $account->bandwidth_reset_at === null || 
                now()->diffInHours($account->bandwidth_reset_at) >= 24
            ) {
                $account->bandwidth_used = 0;
                $account->bandwidth_reset_at = now();
                $account->save();
                
                Log::info("Reset bandwidth counter for {$account->storage_key}");
                
                // Clear cache
                Cache::forget('googleDriveAccounts');
            }
        } catch (\Exception $e) {
            Log::error("Error resetting bandwidth for account {$accountId}: " . $e->getMessage());
        }
    }
    
    /**
     * Check if account has bandwidth available
     * 
     * @param array $account The storage account data
     * @param int $fileSize The file size that will be downloaded
     * @return bool
     */
    public static function checkBandwidthAvailable(array $account, int $fileSize = 0): bool
    {
        // Reset bandwidth if needed before checking
        self::resetBandwidthIfNeeded($account['id']);
        
        // Refresh account data after potential reset
        $freshAccount = Storages::find($account['id']);
        
        if (!$freshAccount) {
            return false;
        }
        
        $bandwidthUsed = $freshAccount->bandwidth_used ?? 0;
        $bandwidthLimit = $freshAccount->bandwidth_limit ?? 751619276800; // 700GB default
        
        // Check if adding this file would exceed the limit
        // Leave 5% buffer to prevent hitting exact limit
        $safeLimit = $bandwidthLimit * 0.95;
        
        return ($bandwidthUsed + $fileSize) < $safeLimit;
    }
    
    /**
     * Find Google Drive account with lowest bandwidth usage for downloads
     * Rotates across accounts to prevent quota exhaustion
     * 
     * @param int $fileSize The file size in bytes
     * @return array [success: bool, diskName: string, accountId: int, message: string]
     */
    public static function getAvailableDownloadAccount(int $fileSize = 0): array
    {
        $accounts = self::getGoogleDriveAccounts();
        
        if (empty($accounts)) {
            return [
                'success' => false,
                'diskName' => '',
                'accountId' => 0,
                'message' => 'No Google Drive accounts configured'
            ];
        }
        
        // Reset bandwidth counters if needed and filter accounts with available bandwidth
        $availableAccounts = [];
        foreach ($accounts as $account) {
            if (self::checkBandwidthAvailable($account, $fileSize)) {
                // Refresh account to get updated bandwidth_used after potential reset
                $freshAccount = Storages::find($account['id'])->toArray();
                $availableAccounts[] = $freshAccount;
            }
        }
        
        if (empty($availableAccounts)) {
            Log::warning("All Google Drive accounts have exceeded bandwidth limits");
            
            return [
                'success' => false,
                'diskName' => '',
                'accountId' => 0,
                'message' => 'All Google Drive accounts have exceeded download quota. Please try again later.'
            ];
        }
        
        // Sort by lowest bandwidth usage (distribute load evenly)
        usort($availableAccounts, function($a, $b) {
            $usageA = $a['bandwidth_used'] ?? 0;
            $usageB = $b['bandwidth_used'] ?? 0;
            return $usageA <=> $usageB;
        });
        
        // Select account with lowest bandwidth usage
        $selectedAccount = $availableAccounts[0];
        $diskName = $selectedAccount['storage_key'];
        
        Log::info("Selected Google Drive {$diskName} for download. Bandwidth used: " . 
            number_format($selectedAccount['bandwidth_used'] / (1024 * 1024 * 1024), 2) . " GB / " .
            number_format($selectedAccount['bandwidth_limit'] / (1024 * 1024 * 1024), 2) . " GB");
        
        return [
            'success' => true,
            'diskName' => $diskName,
            'accountId' => $selectedAccount['id'],
            'message' => 'Found available account for download'
        ];
    }
    
    /**
     * Track download bandwidth usage for an account
     * 
     * @param int $accountId The storage account ID
     * @param int $bytesDownloaded The number of bytes downloaded
     * @return void
     */
    public static function trackDownload(int $accountId, int $bytesDownloaded): void
    {
        try {
            $account = Storages::find($accountId);
            
            if (!$account) {
                Log::error("Account {$accountId} not found for bandwidth tracking");
                return;
            }
            
            // Increment bandwidth usage
            $account->bandwidth_used += $bytesDownloaded;
            $account->save();
            
            $usageGB = $account->bandwidth_used / (1024 * 1024 * 1024);
            $limitGB = $account->bandwidth_limit / (1024 * 1024 * 1024);
            $percentage = ($account->bandwidth_used / $account->bandwidth_limit) * 100;
            
            Log::info("Tracked download for {$account->storage_key}: +" . 
                number_format($bytesDownloaded / (1024 * 1024), 2) . " MB. " .
                "Total: " . number_format($usageGB, 2) . " GB / " . 
                number_format($limitGB, 2) . " GB ({$percentage}%)");
            
            // Clear cache to ensure fresh data on next request
            Cache::forget('googleDriveAccounts');
            
            // Warn if approaching limit
            if ($percentage >= 85) {
                Log::warning("Google Drive {$account->storage_key} approaching bandwidth limit: {$percentage}%");
            }
            
        } catch (\Exception $e) {
            Log::error("Error tracking download bandwidth: " . $e->getMessage());
        }
    }
    
    /**
     * Get the storage account ID by disk name
     * 
     * @param string $diskName The disk name (e.g., 'google', 'google_2')
     * @return int|null The account ID or null if not found
     */
    public static function getAccountIdByDisk(string $diskName): ?int
    {
        try {
            $account = Storages::where('storage_key', $diskName)->first();
            return $account ? $account->id : null;
        } catch (\Exception $e) {
            Log::error("Error getting account ID for {$diskName}: " . $e->getMessage());
            return null;
        }
    }
}
