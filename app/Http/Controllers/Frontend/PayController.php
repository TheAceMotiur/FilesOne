<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\PaymentGateways;
use App\Models\Payment;
use Carbon\Carbon;
use App\Helpers\SeoHelper;
use Illuminate\Support\Facades\Log;
use App\Payment\Stripe;
use App\Payment\Razorpay;
use App\Payment\Bank;

class PayController extends Controller
{
    public function pay(
        string $planString
    ): mixed {
        try {
            $planDecrypted = decrypt($planString);
            parse_str($planDecrypted, $params);
        } catch (\RuntimeException $e) {
            Log::error($e->getMessage());
            return abort(404);
        }

        if (
            isset($params['plan'])
            && isset($params['period'])
        ) {
            $planData = Plan::where('status', 1)
                ->where('id', $params['plan'])
                ->first();

            if (!$planData) {
                abort(404);
            }

            $planFeatures = $planData->features
                ? json_decode($planData->features, true)
                : [];

            // Stripe Gateway Data
            $stripeData = PaymentGateways::where('id', 2)
                ->first();
            
            // Razorpay Gateway Data
            $razorpayData = PaymentGateways::where('id', 3)
                ->first();

            // Bank Gateway Data
            $bankData = PaymentGateways::where('id', 1)
                ->first();

            // Currency
            $currency = Payment::where('name', 'currency_name')
                ->first()->value;

            // Plan dates
            $date = Carbon::now()
                ->setTimezone('Etc/Greenwich');
            $start = $date->format('Y-m-d');
            $end = $params['period'] == 1
                ? $date->addMonth()->format('Y-m-d')
                : $date->addYear()->format('Y-m-d');

            $seo = SeoHelper::pageSeo('pay');

            return view('frontend.pay.index', [
                'functions' => 'frontend.pay.function',
                'pageKey' => 'pay',
                'seoData' => $seo,
                'paymentType' => 'pay',
                'stripe' => $stripeData,
                'razorpay' => $razorpayData,
                'bank' => $bankData,
                'plan' => $planData,
                'features' => $planFeatures,
                'period' => $params['period'],
                'dates' => [$start, $end],
                'planString' => $planString,
                'myPlan' => myPlan(),
                'currency' => $currency,
            ]);

        } else {
            return abort(404);
        }
    }

    public function pay_post_stripe_token(
        string $planString
    ): mixed {
        $stripe = new Stripe();
        $token = $stripe->token($planString);

        if (!$token[0]) {
            return response()->json([
                'result' => false,
                'data' => $token[1], 
            ]);
        }

        return response()->json([
            'result' => true,
            'data' => $token[1], 
        ]);
    }

    public function pay_post_stripe_process(
        Request $request
    ): mixed {
        $intent = $request->get('payment_intent');
        $intent_secret = $request->get('payment_intent_client_secret');
        $status  = $request->get('redirect_status');

        if (!$intent || !$intent_secret || !$status) {
            return abort(404);
        }

        if ($status == 'succeeded') {

            $stripe = new Stripe();
            $start = $stripe->start($intent);

            if (!$start[0]) {
                return back()
                    ->with('error', __('lang.error'));
            }

            return redirect('/user/payments/plan');

        } else {
            return back()
                ->with('error', __('lang.error'));
        }
    }

    public function pay_post_razorpay_order(
        string $planString
    ): mixed {
        $razorpay = new Razorpay();
        $id = $razorpay->order($planString);

        if (!$id[0]) {
            return response()->json([
                'result' => false,
                'data' => $id[1], 
            ]);
        }

        return response()->json([
            'result' => true,
            'data' => $id[1], 
        ]);
    }

    public function pay_post_razorpay_process(
        string $planString,
        string $orderId
    ): mixed {
        if (!$orderId) {
            return abort(404);
        }

        $razorpay = new Razorpay();
        $check = $razorpay->start($planString, $orderId);

        if (!$check[0]) {
            return back()
                ->with('error', __('lang.error'));
        }

        return redirect('/user/payments/plan');
    }

    public function pay_post_bank(
        Request $request,
        string $planString
    ): mixed {
        $request->validate([
            'bank-info' => 'required|min:10|max:500',
        ]);

        $bank = new Bank();
        $start = $bank->pay($planString, $request->input('bank-info'));

        if (!$start[0]) {
            return back()
                ->with('error', $start[1]);
        }

        return back()
            ->with('success', $start[1]);
    }

}
