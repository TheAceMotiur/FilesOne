<?php

namespace App\Helpers;

use App\Models\Upload;
use Illuminate\Support\Facades\Auth;
use App\Helpers\PlanHelper;

class FileStatsHelper
{
    /**
     * Generate all file count for admin
     * @param mixed $disk
     * @return mixed
     */
    public function fileCount(
        mixed $disk = false,
    ): mixed {
        if ($disk) {
            return Upload::where('disk', $disk)->count();
        }
        return Upload::count();
    }

    /**
     * Generate user's file count for user
     * @param mixed $disk
     * @return mixed
     */
    public function myFileCount(
        mixed $disk = false
    ): mixed {
        if ($disk) {
            return Upload::where('created_by_id', Auth::id())
                ->where('disk', $disk)
                ->count();
        }
        return Upload::where('created_by_id', Auth::id())
            ->count();
    }

    /**
     * Generate total file size for admin
     * @param mixed $disk
     * @return mixed
     */
    public function fileSize(
        mixed $disk = false
    ): mixed {
        if ($disk) {
            $files = Upload::where('disk', $disk)->get();
            $totalSize = 0;

            foreach ($files as $file) {
                $totalSize += $file->filesize;
            }

            return formatBytes($totalSize);
        }

        $files = Upload::get();
        $totalSize = 0;

        foreach ($files as $file) {
            $totalSize += $file->filesize;
        }

        return formatBytes($totalSize);
    }

    /**
     * Generate user's file size for user
     * @param mixed $disk
     * @return mixed
     */
    public function myFileSize(
        mixed $disk = false
    ): mixed {
        if ($disk) {
            $files = Upload::where('created_by_id', Auth::id())
                ->where('disk', $disk)
                ->get();
            $totalSize = 0;
            
            foreach ($files as $file) {
                $totalSize += $file->filesize;
            }

            return formatBytes($totalSize);
        }

        $files = Upload::where('created_by_id', Auth::id())
            ->get();
        $totalSize = 0;
        
        foreach ($files as $file) {
            $totalSize += $file->filesize;
        }

        return formatBytes($totalSize);
    }

    /**
     * Generate all file types for admin
     * @param mixed $disk
     * @return array
     */
    public function fileTypes(
        mixed $disk = false
    ): array {
        if ($disk) {
            $types = Upload::selectRaw('COUNT(filetype) AS count')
                ->addSelect('filetype')
                ->where('disk', $disk)
                ->groupBy('filetype')
                ->get();
            return [count($types),$types];
        }

        $types = Upload::selectRaw('COUNT(filetype) AS count')
            ->addSelect('filetype')
            ->groupBy('filetype')
            ->get();
        return [count($types),$types];
    }

    /**
     * Generate user's file types for user
     * @param mixed $disk
     * @return array
     */
    public function myFileTypes(
        mixed $disk = false
    ): array {
        if ($disk) {
            $types = Upload::selectRaw('COUNT(filetype) AS count')
                ->addSelect('filetype')
                ->where('created_by_id', Auth::id())
                ->where('disk', $disk)
                ->groupBy('filetype')
                ->get();
            return [count($types),$types];
        }

        $types = Upload::selectRaw('COUNT(filetype) AS count')
            ->addSelect('filetype')
            ->where('created_by_id', Auth::id())
            ->groupBy('filetype')
            ->get();
        return [count($types),$types];
    }

    /**
     * Generate user's file types list (as array) for user
     * @param mixed $disk
     * @return array
     */
    public function myFileTypesList(
        mixed $disk = false
    ): array {
        if ($disk) {
            $types = Upload::selectRaw('DISTINCT(filetype) AS type')
                ->where('created_by_id', Auth::id())
                ->where('disk', $disk)
                ->groupBy('filetype')
                ->get();
            $typesArr = [];
            foreach ($types as $type) {
                array_push($typesArr,$type->type);
            }
            return $typesArr;
        }

        $types = Upload::selectRaw('DISTINCT(filetype) AS type')
            ->where('created_by_id', Auth::id())
            ->groupBy('filetype')
            ->get();
        $typesArr = [];
        foreach ($types as $type) {
            array_push($typesArr,$type->type);
        }
        return $typesArr;
    }

    /**
     * Generate users (uploaders) count
     * @param mixed $disk
     * @return mixed
     */
    public function uploaders(): mixed {
        $uploaders = Upload::selectRaw('COUNT(DISTINCT(created_by_id))')
            ->groupBy('created_by_id')
            ->get();
        return count($uploaders);
    }

    /**
     * Generate quota analytics
     * @param mixed $disk
     * @return array
     */
    public function quota(
        mixed $disk = false,
    ): array {
        $files = $disk
            ? Upload::where('disk', $disk)->get()
            : Upload::get();
        $fileSize = 0;

        foreach ($files as $file) {
            $fileSize += $file->filesize;
        }

        $total = config('upload.SERVER_QUOTA') * 1024;
        $empty = $total - $fileSize;
        $non_empty = $fileSize;

        $empty_percentage = round(($empty / $total) * 100, 2);
        $non_empty_percentage = round(($non_empty / $total) * 100, 2);

        return [
            'total' => $total,
            'total_mb' => formatBytes2($total, 'mb'),
            'empty' => $empty,
            'empty_mb' => formatBytes2($empty, 'mb'),
            'non_empty' => $non_empty,
            'non_empty_mb' => formatBytes2($non_empty, 'mb'),
            'empty_percentage' => $empty_percentage,
            'non_empty_percentage' => $non_empty_percentage,
        ];
    }

    /**
     * Generate user quota analytics
     * @param mixed $disk
     * @return array
     */
    public function myQuota(
        mixed $disk = false,
    ): array {
        $userId = Auth::id();

        $files = $disk
            ? Upload::where('created_by_id', $userId)
                ->where('disk', $disk)
                ->get()
            : Upload::where('created_by_id', $userId)
                ->get();

        $fileSize = 0;
        
        foreach ($files as $file) {
            $fileSize += $file->filesize;
        }

        // Fetch user current plan
        $plan = PlanHelper::myPlan($userId);
        $features = $plan['features'];
        $total = $features['disk'] * 1024 * 1024;

        $empty = $total - $fileSize;
        $non_empty = $fileSize;

        $empty_percentage = round(($empty / $total) * 100, 2);
        $non_empty_percentage = round(($non_empty / $total) * 100, 2);

        return [
            'total' => $total,
            'total_mb' => formatBytes2($total, 'mb'),
            'empty' => $empty,
            'empty_mb' => formatBytes2($empty, 'mb'),
            'non_empty' => $non_empty,
            'non_empty_mb' => formatBytes($non_empty),
            'empty_percentage' => $empty_percentage,
            'non_empty_percentage' => $non_empty_percentage,
        ];
    }

}
