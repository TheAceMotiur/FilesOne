<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use App\Helpers\ActivityHelper;
use App\Helpers\UserHelper;

class LogsController extends Controller
{
    public function logs(): View
    {
        return view('user.logs.index', [
            'functions' => 'user.logs.function',
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
                    'user' => UserHelper::userBlock(
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

}
