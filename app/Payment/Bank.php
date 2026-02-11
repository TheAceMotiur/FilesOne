<?php

namespace App\Payment;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Mail;
use App\Mail\PlanPaymentAdminPending;
use App\Mail\PlanPaymentUserPending;
use App\Mail\PlanPaymentUserVerified;
use App\Mail\PlanPaymentUserRejected;
use App\Helpers\PlanHelper;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class Bank
{

    public function pay(string $planString, string $info): mixed
    {
        try {
            $planData = $this->decrypter($planString);
            if (!$planData['plan'] || !$planData['period']) {
                return [
                    false,
                    __('lang.error'), 
                ];
            }

            $plan = $this->plan($planData['plan']);
            if (!$plan[0]) {
                return [
                    false,
                    $plan[1]
                ];
            } else {
                return $this->start(
                    Auth::id(),
                    $planData['plan'], 
                    $planData['period'], 
                    $info,
                );
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return [
                false,
                __('lang.error'), 
            ];
        }
    }

    public function start(
        int $userId,
        int $planId,
        int $period,
        string $info
    ): array {
        try {
            $user = $this->user($userId);
            if (!$user[0]) {
                return [
                    false,
                    $user[1]
                ];
            }

            $plan = $this->plan($planId);
            if (!$plan[0]) {
                return [
                    false,
                    $plan[1]
                ];
            }
            $amount = $period == 1
                ? $plan[1]->price_monthly
                : $plan[1]->price_yearly;
                
            // Bank Gateway Data
            $bank = DB::table('payment_gateways')
                ->where('id', 1)
                ->first();

            if (!$bank) {
                return [
                    false,
                    __('lang.payment_method_unavailable')
                ];
            }

            // Check pending bank payments
            $pending = DB::table('payment_logs')
                ->where('created_by_id', $userId)
                ->where('gateway', 1)
                ->where('status', 0)
                ->first();
            if ($pending) {
                return [
                    false,
                    __('lang.already_pending_bank_payment')
                ];
            }

            $myPlan = PlanHelper::myPlan($userId);
            if ($myPlan['free'] && $plan[1]->free == 1) {
                return [
                    false,
                    __('lang.already_free_plan')
                ];
            }

            $date = Carbon::now()
                ->setTimezone('Etc/Greenwich')
                ->toDateTimeString();
            $ip = request()->ip();
            $key = generateCode(
                'payment_logs',
                'transaction',
                12
            );

            $paymentLog = [
                'created_at' => $date,
                'created_by_id' => $userId,
                'created_by_ip' => $ip,
                'updated_at' => $date,
                'updated_by_id' => $userId,
                'updated_by_ip' => $ip,
                'plan' => $plan[1]->id,
                'gateway' => $bank->id,
                'duration' => $period,
                'transaction' => "BANK-{$key}",
                'info' => $info,
                'revenue' => $amount,
                'status' => 0,
            ];

            $create = DB::table('payment_logs')->insert($paymentLog);

            if ($create) {

                $period = $period == 1
                    ? "Monthly"
                    : "Yearly";
                $currency = paymentSetting('currency_name');
                $timezone = setting('time_zone');

                $start = Carbon::parse($date)
                    ->locale(LaravelLocalization::getCurrentLocale())
                    ->setTimezone($timezone ?? 'Etc/Greenwich');
                $expire = $period == 1
                    ? $start->addMonths(1)
                    : $start->addYears(1);

                $mailDataAdmin = [
                    'name' => $user[1]->name,
                    'email' => $user[1]->email,
                    'ip' => $ip,
                    'plan' => $plan[1]->name,
                    'period' => $period,
                    'gateway' => 'Bank',
                    'revenue' => "{$amount}{$currency}",
                    'info' => $info,
                ];

                $mailDataUser = [
                    'plan' => $plan[1]->name,
                    'period' => $period,
                    'gateway' => 'Bank',
                    'payment' => "{$amount}{$currency}",
                    'start' => $start,
                    'end' => $expire,
                    'info' => $info,
                ];

                Mail::to(emailSetting('email'))
                    ->send(new PlanPaymentAdminPending($mailDataAdmin));
                Mail::to($user[1]->email)
                    ->send(new PlanPaymentUserPending($mailDataUser));

                return [
                    true,
                    __('lang.plan_assigned')
                ];
            }

            return [
                false,
                __('lang.error')
            ];
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return [
                false,
                __('lang.error'), 
            ];
        }
    }

    public static function verify(
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

        $paymentData = DB::table('payment_logs')
            ->where('id', $paymentId)
            ->first();

        if (!$paymentData) {
            return [
                false,
                __('lang.data_not_found')
            ];
        }

        if ($paymentData->status != 0) {
            return [
                false,
                __('lang.payment_already_approved')
            ];
        }

        $buyer = DB::table('users')
            ->where('id', $paymentData->created_by_id)
            ->first();

        if (!$buyer) {
            return [
                false,
                __('lang.cannot_verify_payment')
            ];
        }

        $planData = DB::table('payment_plans')
            ->where('id', $paymentData->plan)
            ->first();

        if (!$planData) {
            return [
                false,
                __('lang.error')
            ];
        }

        $request = request();
        $now = Carbon::now()
            ->timezone('Etc/Greenwich')
            ->toDateTimeString();

        $verify = DB::table('payment_logs')
            ->where('id', $paymentId)
            ->update([
                'created_at' => $now,
                'updated_at' => $now,
                'updated_by_id' => $userData->id,
                'updated_by_ip' => $request->ip(),
                'status' => 1
            ]);

        if ($verify) {

            try {
                $myPlan = PlanHelper::myPlan($buyer->id);

                $period = $paymentData->duration == 1
                    ? __('lang.monthly')
                    : __('lang.yearly');
                $currency = paymentSetting('currency_name');

                $mailDataUser = [
                    'plan' => $planData->name,
                    'period' => $period,
                    'gateway' => __('lang.bank'),
                    'payment' => "{$paymentData->revenue}{$currency}",
                    'info' => $paymentData->info,
                    'start' => $myPlan['start'],
                    'end' => $myPlan['end'],
                ];

                Mail::to($buyer->email)
                    ->send(new PlanPaymentUserVerified($mailDataUser));

                return [true];

            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return [
                    false,
                    __('lang.error')
                ];
            }
        }

        return [
            false,
            __('lang.error')
        ];
    }

    public static function reject(
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

        $paymentData = DB::table('payment_logs')
            ->where('id', $paymentId)
            ->first();

        if (!$paymentData) {
            return [
                false,
                __('lang.data_not_found')
            ];
        }

        if ($paymentData->status != 0) {
            return [
                false,
                __('lang.payment_already_approved')
            ];
        }

        $buyer = DB::table('users')
            ->where('id', $paymentData->created_by_id)
            ->first();

        if (!$buyer) {
            DB::table('payment_logs')
                ->where('id', $paymentId)
                ->delete();
            return [true];
        }

        $planData = DB::table('payment_plans')
            ->where('id', $paymentData->plan)
            ->first();

        if (!$planData) {
            return [
                false,
                __('lang.error')
            ];
        }

        $update = DB::table('payment_logs')
            ->where('id', $paymentId)
            ->update([
                'status' => 2
            ]);

        if ($update) {

            try {
                $period = $paymentData->duration == 1
                    ? __('lang.monthly')
                    : __('lang.yearly');
                $currency = paymentSetting('currency_name');

                $mailDataUser = [
                    'plan' => $planData->name,
                    'period' => $period,
                    'gateway' => 'Bank Payment',
                    'payment' => "{$paymentData->revenue}{$currency}",
                    'info' => $paymentData->info,
                ];

                Mail::to($buyer->email)
                    ->send(new PlanPaymentUserRejected($mailDataUser));

                return [true];

            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return [
                    false,
                    __('lang.error')
                ];
            }
            
        }

        return [
            false,
            __('lang.error')
        ];
    }

    private function user(int $userId): mixed
    {
        try {
            $user = DB::table('users')
                ->where('id', $userId)
                ->first();

            if (!$user) {
                return [
                    false,
                    __('lang.error')
                ];
            }

            return [
                true,
                $user
            ];
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return [
                false,
                __('lang.error'), 
            ];
        }
    }

    private function plan(int $planId): mixed
    {
        try {
            $plan = DB::table('payment_plans')
                ->where('id', $planId)
                ->first();

            if (!$plan) {
                return [
                    false,
                    __('lang.error')
                ];
            }

            return [
                true,
                $plan
            ];
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return [
                false,
                __('lang.error'), 
            ];
        }
    }

    private function decrypter(string $planString): mixed
    {
        try {
            $planDecrypted = decrypt($planString);
            parse_str($planDecrypted, $params);
            return $params;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return [
                false,
                __('lang.error'), 
            ];
        }
    }

}
