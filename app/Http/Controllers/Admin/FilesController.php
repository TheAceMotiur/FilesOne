<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Models\Upload;
use App\Models\FileReports;
use App\Models\Storages;
use Illuminate\Http\Request;
use App\Helpers\PaginationHelper;
use App\Helpers\AdminHelper;
use Illuminate\Support\Facades\Storage;
use App\Helpers\FileStatsHelper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Helpers\GoogleDriveHelper;

class FilesController extends Controller
{
    public function all(): View
    {
        $storages = Storages::pluck('name', 'storage_key');

        return view('admin.files.all.index', [
            'functions' => 'admin.files.all.function',
            'sidebar' => 'files_all',
            'pageName' => pageName([__('lang.files'),__('lang.all')]),
            'storages' => $storages,
        ]);
    }

    public function all_post(
        Request $request
    ): JsonResponse {
        $filters = [
            'filename' => $request->input('filename') ?? null,
            'shortkey' => $request->input('shortkey') ?? null,
            'uploader' => $request->input('uploader') ?? null,
            'disk' => $request->input('disk') ?? null,
            'sort' => $request->input('sort') ?? null,
        ];

        $uploadModel = new Upload;
        $files = $uploadModel->fetchAllFiles($filters);
        $filesArr = [];

        if ($files) {
            foreach ($files as $file) {
                $filesArr[] = [
                    'uploaddate' => dateFormat($file['created_at']),
                    'filedetails' => $this->shorten($file['filename']),
                    'filekey' => $file['short_key'],
                    'filesize' => isset($file['filesize']) && $file['filesize']
                        ? formatBytes($file['filesize'])
                        : '-',
                    'uploader' => AdminHelper::userDetails(
                        $file['username'],
                        $file['userid'],
                        $file['useremail'],
                        $file['visitor'],
                    ),
                    'storage' => isset($file['disk']) && $file['disk']
                        ? AdminHelper::filesTableBadges($file['disk'])
                        : '-',
                    'action' => AdminHelper::filesTableButtons(
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
                                Log::warning("Bandwidth limit reached for {$disk}, but allowing admin download");
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
                Log::error("Admin download error: " . $e->getMessage());
                
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
        $count = $statsHelper->fileCount();
        $size = $statsHelper->fileSize();
        $types = $statsHelper->fileTypes();
        $uploaders = $statsHelper->uploaders();

        return response()->json([
            'result' => true,
            'count' => $count,
            'size' => $size,
            'types' => $types,
            'uploaders' => $uploaders,
        ]);
    }

    public function reports(): View
    {
        $storages = Storages::pluck('name', 'storage_key');

        return view('admin.files.reports.index', [
            'functions' => 'admin.files.reports.function',
            'sidebar' => 'files_reports',
            'pageName' => pageName([__('lang.files'),__('lang.reports')]),
            'storages' => $storages,
        ]);
    }

    public function reports_post(
        Request $request
    ): JsonResponse {
        $filters = [
            'filename' => $request->input('filename') ?? null,
            'shortkey' => $request->input('shortkey') ?? null,
            'uploader' => $request->input('uploader') ?? null,
            'disk' => $request->input('disk') ?? null,
            'sort' => $request->input('sort') ?? null,
        ];

        $reportsModel = new FileReports;
        $files = $reportsModel->fetchAllReports($filters);
        $filesArr = [];

        if ($files) {
            foreach ($files as $f) {
                $filesArr[] = [
                    'reportDate' => dateFormat($f['created_at']),
                    'fileDetails' => isset($f['filename']) && $f['filename']
                        ? $this->shorten($f['filename'])
                        : __('lang.deleted_file'),
                    'filekey' => isset($f['short_key']) && $f['short_key']
                        ? $f['short_key']
                        : '-',
                    'fileSize' => isset($f['filesize']) && $f['filesize']
                        ? formatBytes($f['filesize'])
                        : '-',
                    'uploader' => AdminHelper::userDetails(
                        $f['username'],
                        $f['userid'],
                        $f['useremail'],
                        $f['visitor'],
                    ),
                    'storage' => isset($f['disk']) && $f['disk']
                        ? AdminHelper::reportsTableBadges($f['disk'])
                        : '-',
                    'action' => AdminHelper::reportsTableButtons(
                        $f['id'],
                        $f['short_key'],
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

    public function reports_get_post(
        string $reportId
    ): JsonResponse {
        $reportsModel = new FileReports;
        $report = $reportsModel->fetchSingleReport($reportId);

        if ($report) {

            $reportData = [
                'reporter' => $report['reporter'],
                'reason' => $report['reason'],
            ];

            return response()->json([
                'result' => true,
                'data' => $reportData,
            ]);
        }

        return response()->json([
            'result' => false,
        ]);
    }
    
    public function reports_delete_post(
        int $reportId, 
    ): RedirectResponse {
        $report = FileReports::where('id', $reportId)
            ->first();

        if (!$report) {
            return back()
                ->with('error', __('lang.data_not_found'));
        }

        $deleteReport = FileReports::where('id', $reportId)
            ->delete();

        if ($deleteReport) {
            if (Cache::has('fileReportsCount')) {
                Cache::forget('fileReportsCount');
            }
            return back()
                ->with('success', __('lang.data_delete'));
        }

        return back()
            ->with('error', __('lang.file_not_found'));
    }

    private function shorten(
        string $filename
    ): string {
        $filenameArr = explode('.', $filename);
        $fileType = end($filenameArr);
        $filenameShort = substr($filename, 0, 5);
        return
            '<span>'
                . "{$filenameShort}....{$fileType}"
                . '<i '
                    . 'class="form-help fa-solid fa-circle-question ms-1" '
                    . 'data-bs-container="body" '
                    . 'data-bs-toggle="popover" '
                    . 'data-bs-placement="bottom" '
                    . 'data-bs-html="true" '
                    . 'data-bs-content="'
                    . "<span class='user-select-all'>{$filename}</span>"
                    . '"></i>'
            . '</span>';
    }

}
