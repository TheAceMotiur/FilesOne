<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use App\Helpers\FileStatsHelper;
use App\Helpers\AnalyticsHelper;
use Illuminate\Support\Facades\Auth;

class OverviewController extends Controller
{
    public function overview(): View
    {
        return view('user.overview.index', [
            'functions' => 'user.overview.function',
            'sidebar' => 'overview',
            'pageName' => pageName([__('lang.overview')]),
        ]);
    }

    public function topCards(): JsonResponse
    {
        $statsHelper = new FileStatsHelper;

        $files = $statsHelper->myFileCount();
        $fileTypes = $statsHelper->myFileTypes();
        $quota = $statsHelper->myQuota();
        $revenue = AnalyticsHelper::stats(Auth::id());

        $currency = paymentSetting('currency_icon');
        $totalRevenue = "{$currency}{$revenue[2]}";

        return response()->json([
            'result' => true,
            'files' => $files,
            'file_types' => $fileTypes[0],
            'quota_total' => $quota['total_mb'],
            'quota_used' => $quota['non_empty_mb'],
            'quota_empty' => $quota['empty_mb'],
            'revenue' => $totalRevenue,
        ]);
    }

    public function fileTypesAnalytics(): JsonResponse
    {
        $statsHelper = new FileStatsHelper;
        $data = $statsHelper->myFileTypes();

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
        $data = $statsHelper->myQuota();

        return response()->json([
            'result' => true,
            'empty' => $data['empty'],
            'empty_mb' => $data['empty_mb'],
            'non_empty' => $data['non_empty'],
            'non_empty_mb' => $data['non_empty_mb'],
        ]);
    }

    public function visitorAnalytics(): JsonResponse
    {
        $revenue = AnalyticsHelper::monthlyStats(Auth::id());

        $months = [];
        $visitors = [];

        foreach ($revenue as $month => $data) {
            array_push($months, $month);
            array_push($visitors, $data ?? 0);
        }

        return response()->json([
            'months' => array_reverse($months),
            'visitors' => array_reverse($visitors),
        ]);
    }

}
