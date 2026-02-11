<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class PaymentHelper
{

    /**
     * Get all plan payments
     * @param int $userId
     * @param mixed $status
     * @return array
     */
    public static function getAll(
        int $userId,
        mixed $status = false,
    ): array {
        $userData = DB::table('users')
            ->where('id', $userId)
            ->first();

        if (!$userData) {
            return [
                false,
                __('lang.error')
            ];
        }

        $payments = DB::table('payment_logs')
            ->leftJoin(
                'users',
                'payment_logs.created_by_id',
                'users.id'
            )
            ->leftJoin(
                'payment_plans',
                'payment_logs.plan',
                'payment_plans.id'
            )
            ->leftJoin(
                'payment_gateways',
                'payment_logs.gateway',
                'payment_gateways.id'
            )
            ->select('payment_logs.*')
            ->addSelect('payment_plans.name as planName')
            ->addSelect(
                'payment_plans.price_monthly as priceMonthly',
                'payment_plans.price_yearly as priceYearly'
            )
            ->addSelect(
                'payment_plans.features as planFeatures',
            )
            ->addSelect('payment_gateways.name as gateway')
            ->addSelect('users.name as userName')
            ->addSelect(
                'users.id as userId',
                'users.photo as userPhoto',
                'users.email as userEmail',
            )
            ->when($status, function ($query) use ($status) {
                if ($status == 0) {
                    $query->where('payment_logs.status', 0);
                } elseif ($status == 1) {
                    $query->where('payment_logs.status', 1);
                }
            })
            ->where(function ($query) use ($userData) {
                if ($userData->type == 1) {
                    $query
                        ->where('payment_logs.created_by_id', $userData->id);
                }
            })
            ->orderBy('payment_logs.created_at', 'desc')
            ->get();

        // Create list array
        $paymentsArr = [];
        if ($payments) {
            foreach ( $payments as $payment ) {

                $userName = $payment->userName
                    ? $payment->userName
                    : __('lang.deleted_user');

                $userEmail = $payment->userEmail
                    ? $payment->userEmail
                    : __('lang.deleted_user');

                $price = $payment->duration == 1
                    ? $payment->priceMonthly
                    : $payment->priceYearly;

                $paymentsArr[] = [
                    'id' => $payment->id,
                    'date' => $payment->created_at,
                    'planName' => $payment->planName,
                    'planFeatures' => $payment->planFeatures,
                    'planDuration' => $payment->duration,
                    'priceMonthly' => $payment->priceMonthly,
                    'priceYearly' => $payment->priceYearly,
                    'planPrice' => $price,
                    'gateway' => $payment->gateway,
                    'userId' => $payment->userId ?? '-',
                    'userName' => $userName,
                    'userPhoto' => $payment->userPhoto,
                    'userEmail' => $userEmail,
                    'transaction' => $payment->transaction,
                    'status' => $payment->status,
                ];
            }
        }

        return [
            true,
            $paymentsArr
        ];
    }

    /**
     * Get single plan payment
     * @param int $userId
     * @param int $paymentId
     * @return array
     */
    public static function getSingle(
        int $userId,
        int $paymentId,
    ): array {
        $userData = DB::table('users')
            ->where('id', $userId)
            ->first();

        if (!$userData) {
            return [
                false,
                __('lang.error')
            ];
        }

        $payment = DB::table('payment_logs')
            ->leftJoin(
                'users',
                'payment_logs.created_by_id',
                'users.id'
            )
            ->leftJoin(
                'payment_plans',
                'payment_logs.plan',
                'payment_plans.id'
            )
            ->leftJoin(
                'payment_gateways',
                'payment_logs.gateway',
                'payment_gateways.id'
            )
            ->select('payment_logs.*')
            ->addSelect('payment_plans.name as planName')
            ->addSelect(
                'payment_plans.price_monthly as priceMonthly',
                'payment_plans.price_yearly as priceYearly'
            )
            ->addSelect(
                'payment_plans.features as planFeatures',
            )
            ->addSelect('payment_gateways.name as gateway')
            ->addSelect('users.name as userName')
            ->addSelect(
                'users.photo as userPhoto',
                'users.email as userEmail',
            )
            ->where('payment_logs.id', $paymentId)
            ->first();

        // Create payment data
        $paymentData = [];
        if ($payment) {

            $price = ($payment->duration == 1)
                ? $payment->priceMonthly
                : $payment->priceYearly;

            $paymentData = [
                'id' => $payment->id,
                'created_by_ip' => $payment->created_by_ip,
                'transaction' => $payment->transaction,
                'date' => $payment->created_at,
                'info' => $payment->info,
                'planName' => $payment->planName,
                'planFeatures' => $payment->planFeatures,
                'planDuration' => $payment->duration,
                'priceMonthly' => $payment->priceMonthly,
                'priceYearly' => $payment->priceYearly,
                'planPrice' => $price,
                'gateway' => $payment->gateway,
                'userName' => $payment->userName,
                'userPhoto' => $payment->userPhoto,
                'userEmail' => $payment->userEmail ?? '-',
                'status' => $payment->status,
            ];
        }

        return [
            true,
            $paymentData
        ];
    }

}
