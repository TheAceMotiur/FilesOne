<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PaymentLogs extends Model
{
    use HasFactory;

    protected $table = 'payment_logs';
    protected $fillable = [
        'created_at',
        'created_by_id',
        'created_by_ip',
        'updated_at',
        'updated_by_id',
        'updated_by_ip',
        'plan',
        'gateway',
        'duration',
        'transaction',
        'info',
        'revenue',
        'status',
    ];

    /**
     * Get all plan payments
     * @return array
     */
    public static function getAll() 
    {
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
            ->orderBy('payment_logs.updated_at', 'desc')
            ->get();

        // Create advice list array
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

        return $paymentsArr;
    }

    /**
     * Get single plan payment
     * @param int $paymentId
     * @return array
     */
    public static function getSingle(
        int $paymentId,
    ) {
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

            $userName = $payment->userName
                ? $payment->userName
                : __('lang.deleted_user');

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
                'userName' => $userName,
                'userPhoto' => $payment->userPhoto,
                'userEmail' => $payment->userEmail ?? '-',
                'userPhone' => $payment->userPhone ?? '-',
                'status' => $payment->status,
            ];
        }

        return $paymentData;
    }

}
