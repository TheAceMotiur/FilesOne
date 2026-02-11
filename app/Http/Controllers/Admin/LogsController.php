<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use App\Helpers\ActivityHelper;
use App\Helpers\AdminHelper;

class LogsController extends Controller
{
    public function logs(): View
    {
        return view('admin.logs.index', [
            'functions' => 'admin.logs.function',
            'sidebar' => 'logs',
            'pageName' => pageName([__('lang.logs')]),
        ]);
    }

    public function logs_post(): JsonResponse
    {
        $activities = ActivityHelper::getAll();

        $activitiesArr = [];
        if ($activities[0]) {
            foreach ( $activities[1] as $activity ) {
                $activitiesArr[] = [
                    'date' => dateFormat(
                        $activity['date'],
                    ),
                    'user' => AdminHelper::userBlock(
                        $activity['userName'],
                        img('user', $activity['userPhoto'])
                    ),
                    'ip' => $activity['ip'],
                    'os' => $activity['os'],
                    'browser' => $activity['browser'],
                    'action' => ucfirst($activity['action']),
                ];
            }
        }

        return response()->json([
            'result' => true,
            'data' => $activitiesArr
        ]);
    }

    public function logs_clear_post(): mixed
    {
        $clear = ActivityHelper::clear();

        if ($clear) {
            return back()
                ->with('success', __('lang.data_delete'));
        }

        return back()
            ->with('error', __('lang.data_delete_error'));
    }
}
