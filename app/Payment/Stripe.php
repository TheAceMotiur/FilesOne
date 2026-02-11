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

class Stripe
{

    public function token(string $planString): mixed
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

    public function meta(string $pintent): mixed
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

            curl_setopt($ch, CURLOPT_URL, "https://api.stripe.com/v1/payment_intents/{$pintent}");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_USERPWD, $keys[2] . ':' . '');

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

            if (isset($response['metadata']) && $response['metadata']) {
                return [
                    true,
                    $response['metadata']
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

    public function start(string $payment_intent): mixed
    {
        try {
            $meta = $this->meta($payment_intent);
            if (!$meta[0]) {
                return [
                    false,
                    $meta[1]
                ];
            }

            $metaArr = $meta[1];
            $userId = $metaArr['userId'];
            $planId = $metaArr['planId'];
            $period = $metaArr['period'];

            $id = Auth::id();
            if ($userId != $id) {
                return [
                    false,
                    __('lang.error')
                ];
            }

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
                'created_by_id' => $id,
                'created_by_ip' => $ip,
                'updated_at' => $date,
                'updated_by_id' => $id,
                'updated_by_ip' => $ip,
                'plan' => $planId,
                'gateway' => 2,
                'duration' => $period,
                'transaction' => "STRIPE-{$key}",
                'info' => null,
                'revenue' => $amount,
                'status' => 1,
            ];

            $create = DB::table('payment_logs')->insert($paymentLog);

            if ($create) {

                $userData = User::where('id', $id)->first();

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
                    'gateway' => 'Stripe',
                    'revenue' => "{$amount}{$currency}",
                ];

                $mailDataUser = [
                    'plan' => $myNewPlan['name'],
                    'period' => $period,
                    'gateway' => 'Stripe',
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
            $stripeData = DB::table('payment_gateways')
                ->where('id', 2)
                ->first();

            if (
                $stripeData->status == 0
                || !$stripeData->public
                || !$stripeData->secret
            ) {
                return [
                    false,
                    __('lang.payment_method_unavailable')
                ];
            }

            return [
                true,
                $stripeData->public,
                $stripeData->secret
            ];
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
            $metadata = "metadata[userId]={$userId}&"
                . "metadata[planId]={$planId}&metadata[period]={$period}";

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/payment_intents');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "amount={$price}&currency={$currency}&{$metadata}");
            curl_setopt($ch, CURLOPT_USERPWD, $keys[2] . ':' . '');

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

            if (isset($response['client_secret']) && $response['client_secret']) {
                return [
                    true,
                    $response['client_secret']
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
