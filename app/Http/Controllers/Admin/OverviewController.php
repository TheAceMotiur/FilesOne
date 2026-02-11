<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Models\BlogPost;
use App\Models\BlogComment;
use App\Models\PaymentLogs;
use App\Helpers\FileStatsHelper;

class OverviewController extends Controller
{
    public function overview(): View
    {
        return view('admin.overview.index', [
            'functions' => 'admin.overview.function',
            'sidebar' => 'overview',
            'pageName' => pageName([__('lang.overview')]),
        ]);
    }

    public function registrationAnalytics(): JsonResponse
    {
        $usersModel = new User;
        $users = $usersModel->registrationAnalytics();

        $months = [];
        $register = [];

        foreach ($users as $month => $data) {
            array_push($months, $month);
            array_push($register, $data ?? 0);
        }

        return response()->json([
            'months' => array_reverse($months),
            'users' => array_reverse($register),
        ]);
    }

    public function fileTypesAnalytics(): JsonResponse
    {
        $statsHelper = new FileStatsHelper;
        $data = $statsHelper->fileTypes();

        $types = [];
        foreach ($data[1] as $value) {
            $types[] = [
                'x' => ucfirst($value->filetype),
                'y' => $value->count,
            ];
        }

        return response()->json([
            'result' => true,
            'types' => $types,
        ]);
    }

    public function quotaAnalytics(): JsonResponse
    {
        $statsHelper = new FileStatsHelper;
        $data = $statsHelper->quota();

        return response()->json([
            'result' => true,
            'empty' => $data['empty'],
            'empty_mb' => $data['empty_mb'],
            'non_empty' => $data['non_empty'],
            'non_empty_mb' => $data['non_empty_mb'],
        ]);
    }
    
    public function topCards(): JsonResponse
    {
        $statsHelper = new FileStatsHelper;

        $users = User::where('type', 1)->count();
        $files = $statsHelper->fileCount();
        $fileTypes = $statsHelper->fileTypes();
        $fileSize = $statsHelper->fileSize();

        $posts = BlogPost::count();
        $comments = BlogComment::count();
        $sales = PaymentLogs::where('status', 1)->get();

        $revenue = 0;
        foreach ($sales as $sale) {
            $revenue += $sale->revenue;
        }
        $currency = paymentSetting('currency_icon');
        $revenue = "{$currency}{$revenue}";

        return response()->json([
            'result' => true,
            'users' => $users,
            'files' => $files,
            'file_types' => $fileTypes[0],
            'file_size' => $fileSize,
            'posts' => $posts,
            'comments' => $comments,
            'sales' => count($sales),
            'revenue' => $revenue,
        ]);
    }

}
