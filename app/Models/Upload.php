<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Upload extends Model
{
    use HasFactory;
    protected $table = "files";
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $fillable = [
        'created_at',
        'created_by_id',
        'created_by_ip',
        'updated_at',
        'updated_by_id',
        'updated_by_ip',
        'filename',
        'filesize',
        'filetype',
        'disk',
        'short_key',
        'autoremove',
        'password',
        'pageview',
        'unique_pageview',
        'download',
    ];

    public function fetchAllFiles(array $filters)
    {
        $filename = $filters['filename'] ?? false;
        $shortkey = $filters['shortkey'] ?? false;
        $uploader = $filters['uploader'] ?? false;
        $type = $filters['type'] ?? false;
        $disk = $filters['disk'] ?? false;
        $sort = $filters['sort'] ?? false;

        $userType = Auth::user()->type;
        $limited = $userType == 1 ? true : false;
       
        $files = DB::table('files')
            ->leftJoin('users', 'files.created_by_id', '=', 'users.id')
            ->select('files.*')
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
            ->where(function ($query) use ($type): void {
                if ($type) {
                    $query->where('files.filetype', $type);
                }
            })
            ->where(function ($query) use ($limited): void {
                if ($limited) {
                    $query->where('files.created_by_id', Auth::id());
                }
            })
            ->when($sort, function ($query) use ($sort): void {
                if ($sort == 'date_asc') {
                    $query->orderBy('files.created_at', 'asc');
                } elseif ($sort == 'date_desc') {
                    $query->orderBy('files.created_at', 'desc');
                } elseif ($sort == 'size_asc') {
                    $query->orderBy('files.filesize', 'asc');
                } elseif ($sort == 'size_desc') {
                    $query->orderBy('files.filesize', 'desc');
                }
            })
            ->when(!$sort, function ($query): void {
                $query->orderBy('files.created_at', 'desc');
            })
            ->get();

        $filesArr = [];

        if ($files) {
            foreach ($files as $file) {
                $filesArr[] = [
                    'created_at' => $file->created_at,
                    'filename' => $file->filename,
                    'filetype' => $file->filetype,
                    'filesize' => $file->filesize,
                    'userid' => $file->userId ?? null,
                    'username' => $file->userName ?? __('lang.deleted_user'),
                    'useremail' => $file->userEmail ?? null,
                    'disk' => $file->disk,
                    'short_key' => $file->short_key,
                    'visitor' => $file->created_by_id == null
                        ? true
                        : false,
                ];
            }
        }

        return $filesArr;
    }
}
