<?php

namespace App\Payment;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;
use Mail;
use App\Mail\PlanPaymentAdminSuccess;
use App\Mail\PlanPaymentUserSuccess;
use App\Helpers\PlanHelper;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class Razorpay
{

    public function order(string $planString): mixed
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
                $price = $planData['period'] == 1
                    ? $plan[1]->price_monthly
                    : $plan[1]->price_yearly;
                return $this->curl(
                    $planData['plan'], 
                    $planData['period'], 
                    $price * 100
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

    private function curl(int $planId, int $period, int $price): mixed
    {
        try {
            $keys = $this->keys();
            if (!$keys[0]) {
                return [
                    false,
                    $keys[1]
                ];
            }

            $currency = $this->currency();
            $userId = Auth::id();
            $metadata = "notes[userId]={$userId}&"
                . "notes[planId]={$planId}&notes[period]={$period}";
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/orders');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "amount={$price}&currency={$currency}&{$metadata}");
            curl_setopt($ch, CURLOPT_USERPWD, $keys[1] . ':' . $keys[2]);
            
            $headers = array();
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                Log::error(curl_error($ch));
                return [
                    false,
                    __('lang.error'), 
                ];
            }
            curl_close($ch);

            $response = json_decode($result, true);

            if (isset($response['id']) && $response['id']) {
                return [
                    true,
                    $response['id']
                ];
            } elseif (isset($response['error']) && $response['error']) {
                Log::error($response['error']);
                return [
                    false,
                    __('lang.error'), 
                ];
            } else {
                return [
                    false,
                    __('lang.error'), 
                ];
            }

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return [
                false,
                __('lang.error'), 
            ];
        }
    }

    public function start(string $planString, string $orderId): mixed
    {
        try {
            $planData = $this->decrypter($planString);
            if (!$planData['plan'] || !$planData['period']) {
                return [
                    false,
                    __('lang.error'), 
                ];
            }

            $meta = $this->meta($orderId);
            if (!$meta[0]) {
                return [
                    false,
                    $meta[1]
                ];
            }

            $userId = Auth::id();
            $planId = $planData['plan'];
            $period = $planData['period'];

            $ip = request()->ip();
            $date = Carbon::now()->toDateTimeString();

            $plan = $this->plan($planId);
            $myPlan = PlanHelper::myPlan($userId);

            if ($myPlan['free'] && $plan[1]->free == 1) {
                return [
                    false,
                    __('lang.already_free_plan')
                ];
            }

            $amount = $period == 1
                ? $plan[1]->price_monthly
                : $plan[1]->price_yearly;

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
                'plan' => $planId,
                'gateway' => 3,
                'duration' => $period,
                'transaction' => "RAZORPAY-{$key}",
                'info' => null,
                'revenue' => $amount,
                'status' => 1,
            ];

            $create = DB::table('payment_logs')->insert($paymentLog);

            if ($create) {

                $userData = User::where('id', $userId)->first();

                $period = $period == 1
                    ? "Monthly"
                    : "Yearly";
                $currency = paymentSetting('currency_name');
                $myNewPlan = PlanHelper::myPlan($userId);

                $mailDataAdmin = [
                    'name' => $userData->name,
                    'email' => $userData->email,
                    'ip' => $ip,
                    'plan' => $myNewPlan['name'],
                    'period' => $period,
                    'gateway' => 'Razorpay',
                    'revenue' => "{$amount}{$currency}",
                ];

                $mailDataUser = [
                    'plan' => $myNewPlan['name'],
                    'period' => $period,
                    'gateway' => 'Razorpay',
                    'payment' => "{$amount}{$currency}",
                    'start' => $myNewPlan['start'],
                    'end' => $myNewPlan['end'],
                ];

                Mail::to(emailSetting('email'))
                    ->send(new PlanPaymentAdminSuccess($mailDataAdmin));
                Mail::to($userData->email)
                   ->send(new PlanPaymentUserSuccess($mailDataUser));


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

    public function meta(string $orderId): mixed
    {
        try {
            $keys = $this->keys();
            if (!$keys[0]) {
                return [
                    false,
                    $keys[1]
                ];
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.razorpay.com/v1/orders/order_{$orderId}");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_USERPWD, $keys[1] . ':' . $keys[2]);
            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                Log::error(curl_error($ch));
                return [
                    false,
                    __('lang.error'), 
                ];
            }
            curl_close($ch);
            
            $response = json_decode($result, true);

            if (isset($response['status']) && $response['status'] && $response['status'] == 'paid') {
                return [
                    true
                ];
            } elseif (isset($response['error']) && $response['error']) {
                Log::error($response['error']);
                return [
                    false,
                    __('lang.error'), 
                ];
            } else {
                return [
                    false,
                    __('lang.error'), 
                ];
            }

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

    private function keys(): mixed
    {
        try {
            $razorpayData = DB::table('payment_gateways')
                ->where('id', 3)
                ->first();

            if (
                $razorpayData->status == 0
                || !$razorpayData->public
                || !$razorpayData->secret
            ) {
                return [
                    false,
                    __('lang.payment_method_unavailable')
                ];
            }

            return [
                true,
                $razorpayData->public,
                $razorpayData->secret
            ];
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return [
                false,
                __('lang.error'), 
            ];
        }
    }

    private function currency(): mixed
    {
        try {
            return DB::table('payment_settings')
                ->where('name', 'currency_name')
                ->first()->value;
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
