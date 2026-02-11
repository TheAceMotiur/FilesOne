<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Models\Upload;
use App\Helpers\PaginationHelper;
use App\Helpers\UserHelper;
use App\Helpers\FileStatsHelper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Helpers\GoogleDriveHelper;

class FilesController extends Controller
{
    public function all(): View
    {
        $statsHelper = new FileStatsHelper;
        $fileTypes = $statsHelper->myFileTypesList();

        return view('user.files.index', [
            'functions' => 'user.files.function',
            'sidebar' => 'files',
            'pageName' => pageName([__('lang.files')]),
            'fileTypes' => $fileTypes,
        ]);
    }

    public function all_post(
        Request $request
    ): JsonResponse {
        $filters = [
            'shortkey' => $request->input('shortkey') ?? null,
            'type' => $request->input('type') ?? null,
            'sort' => $request->input('sort') ?? null,
        ];

        $uploadModel = new Upload;
        $files = $uploadModel->fetchAllFiles($filters);
        $filesArr = [];

        if ($files) {
            foreach ($files as $file) {
                $filesArr[] = [
                    'uploaddate' => dateFormat($file['created_at']),
                    'filekey' => $file['short_key'],
                    'filetype' => $file['filetype'],
                    'filesize' => formatBytes($file['filesize']),
                    'action' => UserHelper::filesTableButtons(
                        $file['short_key'],
                    ),
                ];
            }
        }

        $paginater = new PaginationHelper;
        $data = $filesArr
            ? $paginater->paginate($filesArr, 5)->withQueryString()
            : [];

        return response()->json([
            'result' => true,
            'data' => $data,
        ]);
    }

    public function all_view_post(
        string $fileKey
    ): mixed {
        $fileData = Upload::whereRaw("BINARY `short_key`= ?", $fileKey)
            ->where('created_by_id', Auth::id())
            ->first();
            
        if (!$fileData) {
            return response()->json([
                'result' => false,
                'data' => __('lang.file_not_found'),
            ]);
        }

        return response()->json([
            'result' => true,
            'filename' => $fileData->filename,
            'filetype' => $fileData->filetype,
        ]);
    }

    public function all_download_post(
        string $fileKey
    ): mixed {
        $fileData = Upload::whereRaw("BINARY `short_key`= ?", $fileKey)
            ->where('created_by_id', Auth::id())
            ->first();
        if (!$fileData) {
            return back()
                ->with('error', __('lang.file_not_found'));
        }

        $disk = $fileData->disk;
        $filename = $fileData->filename;

        if (Storage::disk($disk)->exists($filename)) {
            // Track bandwidth usage for Google Drive accounts (non-blocking)
            if (str_starts_with($disk, 'google')) {
                try {
                    $accountId = GoogleDriveHelper::getAccountIdByDisk($disk);
                    if ($accountId) {
                        $accounts = GoogleDriveHelper::getGoogleDriveAccounts();
                        $currentAccount = collect($accounts)->firstWhere('id', $accountId);
                        
                        if ($currentAccount) {
                            $bandwidthOk = GoogleDriveHelper::checkBandwidthAvailable($currentAccount, $fileData->filesize);
                            if (!$bandwidthOk) {
                                Log::warning("Bandwidth limit reached for {$disk}, but allowing user download");
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning("Bandwidth check failed: " . $e->getMessage());
                }
            }
            
            try {
                $downloadName = "{$fileData->short_key}.{$fileData->filetype}";
                $response = Storage::disk($disk)
                    ->download($filename, $downloadName);
                
                // Track bandwidth after successful download (non-blocking)
                if (str_starts_with($disk, 'google')) {
                    try {
                        $accountId = GoogleDriveHelper::getAccountIdByDisk($disk);
                        if ($accountId) {
                            GoogleDriveHelper::trackDownload($accountId, $fileData->filesize);
                        }
                    } catch (\Exception $e) {
                        Log::warning("Bandwidth tracking failed: " . $e->getMessage());
                    }
                }
                
                return $response;
                
            } catch (\Exception $e) {
                Log::error("User download error: " . $e->getMessage());
                
                if (str_contains($e->getMessage(), 'quota') || str_contains($e->getMessage(), 'limit')) {
                    return back()->with('error', __('lang.download_quota_exceeded'));
                }
                
                return back()->with('error', __('lang.error'));
            }
        }

        return back()
            ->with('error', __('lang.file_not_found'));
    }

    public function all_delete_post(
        string $fileKey
    ): RedirectResponse {
        $fileData = Upload::whereRaw("BINARY `short_key`= ?", $fileKey)
            ->where('created_by_id', Auth::id())
            ->first();
        if (!$fileData) {
            return back()
                ->with('error', __('lang.file_not_found'));
        }

        $disk = $fileData->disk;
        $filename = $fileData->filename;
        
        if (Storage::disk($disk)->exists($filename)) {
            $delete = Storage::disk($disk)->delete($filename);

            if ($delete) {
                Upload::where('disk', $disk)
                    ->whereRaw("BINARY `filename`= ?", $filename)
                    ->delete();

                return back()
                    ->with('success', __('lang.file_deleted'));
            }
            return back()
                ->with('error', __('lang.file_deleted_error'));
        }

        $delete = Upload::where('disk', $disk)
            ->whereRaw("BINARY `filename`= ?", $filename)
            ->delete();

        if ($delete) {
            return back()
                ->with('success', __('lang.file_deleted'));
        }

        return back()
            ->with('error', __('lang.file_not_found'));
    }

    public function all_stats_post(): JsonResponse
    {
        $statsHelper = new FileStatsHelper;
        $count = $statsHelper->myFileCount();
        $size = $statsHelper->myFileSize();
        $types = $statsHelper->myFileTypes();

        return response()->json([
            'result' => true,
            'count' => $count,
            'size' => $size,
            'types' => $types[0],
        ]);
    }

}
