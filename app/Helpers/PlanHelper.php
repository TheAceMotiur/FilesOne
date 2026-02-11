<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class PlanHelper
{

    /**
     * Fetch active user plan
     * @param int $userId
     * @return mixed
     */
    public static function myPlan(
        mixed $userId
    ): array {
        if (isset($userId)) {
            $userData = DB::table('users')
                ->where('id', $userId)
                ->first();
        }

        return self::getPlan($userData->id ?? null);
    }

    /**
     * Check user rest api permissions
     * @param mixed $userId
     * @return array
     */
    public static function canIRest(
        mixed $userId,
    ): array {

        if (isset($userId)) {
            $userData = DB::table('users')
                ->where('id', $userId)
                ->first();

            if ($userData && $userData->type == 2) {
                return [true];
            }
        }

        // Fetch user current plan
        $plan = self::myPlan($userId);
        $features = $plan['features'];

        // Check api permissions
        if (isset($features['api']) && $features['api'] == 1) {
            return [true];
        }

        return [
            false,
            __('lang.rest_not_authorized'),
        ];
    }

    /**
     * Fetch active user plan
     * @param mixed $userId
     * @param mixed $file
     * @param int $fileSize
     * @return array
     */
    public static function canIUpload(
        mixed $userId,
        mixed $file,
        int $fileSize,
    ): array {

        if (isset($userId)) {
            $userData = DB::table('users')
                ->where('id', $userId)
                ->first();

            if ($userData && $userData->type == 2) {
                return [true];
            }
        }

        // Fetch user current plan
        $plan = self::myPlan($userId);
        $features = $plan['features'];

        // Check file format
        $allowed = $features['formats'];
        if (gettype($file) == 'object') {
            $extension = strtolower($file->getClientOriginalExtension());
        } elseif (gettype($file) == 'string') {
            $filenameArr = explode('.', $file);
            $extension = strtolower($filenameArr[1]);
        }

        if (!in_array($extension, $allowed)) {
            return [
                false,
                __('lang.cannot_upload_filetype')
            ];
        }

        // Check user disk and server quota
        $diskSize = config('upload.SERVER_QUOTA') * 1024;

        $allFiles = DB::table('files')
            ->select('filesize')
            ->get();

        $allFilesSize = 0;
        if ($allFiles->isNotEmpty()) {
            foreach ($allFiles as $file) {
                $allFilesSize += $file->filesize;
            }
        }

        if (($allFilesSize + $fileSize) > $diskSize) {
            return [
                false,
                __('lang.server_quota_full')
            ];
        }

        $userDiskSize = $features['disk'] * (1024 * 1024);

        $userFiles = Auth::check()
            ? DB::table('files')
                ->select('filesize')
                ->where('created_by_id',Auth::id())
                ->get()
            : false;

        $userFilesSize = 0;
        if ($userFiles && $userFiles->isNotEmpty()) {
            foreach ($userFiles as $file) {
                $userFilesSize += $file->filesize;
            }
        }

        if (($userFilesSize + $fileSize) > $userDiskSize) {
            return [
                false,
                __('lang.cannot_upload_disk')
            ];
        }

        return [true];
    }

    /**
     * Fetch active user plan
     * @param mixed $userId
     * @return array
     */
    private static function getPlan(
        mixed $userId
    ): array {
        
        if (is_null($userId)) {
            // Get free plan
            $freePlan = DB::table('payment_plans')
                ->select('*')
                ->where('id', 1)
                ->first();

            return [
                'name' => $freePlan->name,
                'price' => null,
                'period' => null,
                'start' => null,
                'end' => null,
                'remaining' => null,
                'features' => json_decode(
                    $freePlan->features,
                    true
                ),
                'free' => true,
            ];
        }

        // Get user data
        $planData = DB::table('payment_logs')
            ->join(
                'payment_plans',
                'payment_logs.plan',
                'payment_plans.id'
            )
            ->select('payment_logs.*')
            ->addSelect(
                'payment_plans.price_monthly as priceMonthly',
                'payment_plans.price_yearly as priceYearly'
            )
            ->addSelect(
                'payment_plans.name as planName',
                'payment_plans.features as planFeatures'
            )
            ->where('payment_logs.created_by_id', $userId)
            ->where('payment_logs.status', 1)
            ->orderBy('payment_logs.created_at', 'DESC')
            ->first();

        if ($planData) {

            $price = $planData->duration == 1
                ? $planData->priceMonthly
                : $planData->priceYearly;

            $timezone = setting('time_zone');
            $now = Carbon::now()
                ->locale(LaravelLocalization::getCurrentLocale())
                ->setTimezone($timezone ?? 'Etc/Greenwich');
            $start = Carbon::parse($planData->created_at)
                ->locale(LaravelLocalization::getCurrentLocale())
                ->setTimezone($timezone ?? 'Etc/Greenwich');

            $expire = $planData->duration == 1
                ? $start->addMonths(1)
                : $start->addYears(1);

            if ($expire->greaterThan($now)) {

                $difference = $expire->diff($now)->format('%m:%d:%H');
                $diff = explode(':', $difference);

                return [
                    'name' => $planData->planName,
                    'price' => $price,
                    'period' => $planData->duration,
                    'start' => dateFormat($planData->created_at),
                    'end' => dateFormat($expire),
                    'remaining' => $diff,
                    'features' => json_decode(
                        $planData->planFeatures,
                        true
                    ),
                    'free' => false,
                ];
            }
        }

        // Get free plan
        $freePlan = DB::table('payment_plans')
            ->select('*')
            ->where('id', 1)
            ->first();

        return [
            'name' => $freePlan->name,
            'price' => null,
            'period' => null,
            'start' => null,
            'end' => null,
            'remaining' => null,
            'features' => json_decode(
                $freePlan->features,
                true
            ),
            'free' => true,
        ];
    }

    /**
     * Fetch user's auto deletion time based on plan
     * @param mixed $userId
     * @return string
     */
    public static function autoDeletion(
        mixed $userId
    ): string {
        $plan = self::myPlan($userId);
        $autoDeletion = $plan['features']['auto_deletion'];
        return $autoDeletion ?? false;
    }
}
