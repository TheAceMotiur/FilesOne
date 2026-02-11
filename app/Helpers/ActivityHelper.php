<?php

namespace App\Helpers;

use Carbon\Carbon;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ActivityHelper
{

    /**
     * Get all activity logs
     * @return array
     */
    public static function getAll(
    ): array {
        $userType = Auth::user()->type;
        $limited = $userType == 1 ? true : false;

        $activities = DB::table('activity_logs')
            ->leftJoin('users', 'activity_logs.created_by_id', '=', 'users.id')
            ->select('activity_logs.*')
            ->addSelect('users.name as userName')
            ->addSelect('users.photo as userPhoto')
            ->where(function ($query) use ($limited): void {
                if ($limited) {
                    $query->where('activity_logs.created_by_id', Auth::id());
                }
            })
            ->orderBy('activity_logs.created_at', 'desc')
            ->get();

        // Create activity logs array
        $activitiesArr = [];

        if ($activities) {
            foreach ($activities as $activity) {

                $details = json_decode($activity->details, true);
                
                $activitiesArr[] = [
                    'date' => $activity->created_at,
                    'userName' => $activity->userName,
                    'userPhoto' => $activity->userPhoto,
                    'ip' => $activity->created_by_ip,
                    'os' => $details['os'],
                    'browser' => $details['browser'],
                    'action' => ucfirst($activity->action),
                ];
            }
        }

        return [
            true,
            $activitiesArr
        ];
    }

    /**
     * Save activity of a user
     * @param string $userId
     * @return string
     */
    public static function log(
        int $userId,
        string $action,
        mixed $notes = false
    ): bool {
        $request = request();
        $agent = new Agent();
        $data = [
            'device' => $agent->device(),
            'os' => $agent->platform(),
            'lang' => $agent->languages(),
            'browser' => $agent->browser(),
            'browserV' => $agent->version($agent->browser()),
        ];

        DB::table('activity_logs')->insertOrIgnore([
            'created_at' => Carbon::now()->toDateTimeString(),
            'created_by_id' => $userId,
            'created_by_ip' => $request->ip(),
            'action' => $action,
            'details' => json_encode($data),
            'additional' => $notes ? $notes : null,
        ]);

        return true;
    }

    /**
     * Clear all activities
     * @return string
     */
    public static function clear(): bool {
        DB::table('activity_logs')->truncate();
        return true;
    }

}
