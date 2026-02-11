<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FileReports extends Model
{
    use HasFactory;
    protected $table = "file_reports";
    protected $fillable = [
        'created_at',
        'created_by_id',
        'created_by_ip',
        'updated_at',
        'updated_by_id',
        'updated_by_ip',
        'file_id',
        'file_uploader',
        'reporter',
        'reason',
    ];

    public function fetchAllReports(array $filters)
    {
        $filename = $filters['filename'] ?? false;
        $shortkey = $filters['shortkey'] ?? false;
        $uploader = $filters['uploader'] ?? false;
        $disk = $filters['disk'] ?? false;
        $sort = $filters['sort'] ?? false;

        $reports = DB::table('file_reports')
            ->leftjoin('files', 'file_reports.file_id', '=', 'files.id')
            ->leftjoin('users', 'file_reports.file_uploader', '=', 'users.id')
            ->select('file_reports.*')
            ->addSelect('files.created_by_id as createdById')
            ->addSelect('files.filename as filename')
            ->addSelect('files.filesize as filesize')
            ->addSelect('files.filetype as filetype')
            ->addSelect('files.disk as disk')
            ->addSelect('files.created_at as uploaded')
            ->addSelect('files.short_key as short_key')
            ->addSelect('users.id as userId')
            ->addSelect('users.name as userName')
            ->addSelect('users.email as userEmail')
            ->where(function ($query) use ($filename): void {
                if ($filename) {
                    $query->where('files.filename', 'like', "%{$filename}%");
                }
            })
            ->where(function ($query) use ($shortkey): void {
                if ($shortkey) {
                    $query->where('files.short_key', 'like', "%{$shortkey}%");
                }
            })
            ->where(function ($query) use ($uploader): void {
                if ($uploader) {
                    $query->where('users.name', 'like', "%{$uploader}%");
                }
            })
            ->where(function ($query) use ($disk): void {
                if ($disk) {
                    $query->where('files.disk', $disk);
                }
            })
            ->when($sort, function ($query) use ($sort): void {
                if ($sort == 'date_asc') {
                    $query->orderBy('file_reports.created_at', 'asc');
                } elseif ($sort == 'date_desc') {
                    $query->orderBy('file_reports.created_at', 'desc');
                } elseif ($sort == 'size_asc') {
                    $query->orderBy('files.filesize', 'asc');
                } elseif ($sort == 'size_desc') {
                    $query->orderBy('files.filesize', 'desc');
                }
            })
            ->when(!$sort, function ($query): void {
                $query->orderBy('file_reports.created_at', 'desc');
            })
            ->get();

        $reportsArr = [];

        if ($reports) {
            foreach ($reports as $file) {
                $reportsArr[] = [
                    'id' => $file->id,
                    'created_at' => $file->created_at,
                    'reporter' => $file->reporter,
                    'reason' => $file->reason,
                    'uploaded' => $file->uploaded,
                    'userid' => $file->userId ?? null,
                    'username' => $file->userName ?? __('lang.deleted_user'),
                    'useremail' => $file->userEmail ?? null,
                    'filename' => $file->filename,
                    'filesize' => $file->filesize,
                    'filetype' => $file->filetype,
                    'disk' => $file->disk,
                    'short_key' => $file->short_key,
                    'visitor' => $file->createdById == null
                        ? true
                        : false,
                   
                ];
            }
        }

        return $reportsArr;
    }

    public function fetchSingleReport(string $reportId)
    {
        $report = DB::table('file_reports')
            ->where('id','=', $reportId)
            ->first();

        $reportData = [];
        if ($report) {
            $reportData = [
                'reporter' => $report->reporter,
                'reason' => $report->reason,
            ];
        }

        return $reportData;
    }
}
