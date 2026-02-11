<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use App\Helpers\PaymentHelper;
use App\Helpers\PlanHelper;
use Illuminate\Support\Facades\Auth;
use App\Helpers\UserHelper;

class PaymentsController extends Controller
{
    public function payments(): View
    {
        return view('user.payments.all.index', [
            'functions' => 'user.payments.all.function',
            'sidebar' => 'payments_all',
            'pageName' => pageName([__('lang.payments'),__('lang.all')]),
        ]);
    }

    public function payments_post(): JsonResponse
    {
        $logs = PaymentHelper::getAll(Auth::id());

        $logsData = [];
        if ($logs[0]) {
            foreach ($logs[1] as $log) {

                $logsData[] = [
                    'date' => dateFormat($log['date']),
                    'plan' => $log['planName'],
                    'duration' => $log['planDuration'] == 1
                        ? __('lang.monthly')
                        : __('lang.yearly'),
                    'price' => paymentSetting('currency_icon') 
                        . $log['planPrice'],
                    'gateway' => $log['gateway'],
                    'status' => UserHelper::paymentLogsTableBadges(
                        $log['status'],
                        __('lang.completed'),
                        __('lang.pending'),
                        __('lang.rejected')
                    ),
                    'action' => UserHelper::paymentLogsTableButtons(
                        $log['id'],
                    ),
                ];
            }
        }

        return response()->json([
            'result' => true,
            'data' => $logsData,
        ]);
    }

    public function payments_single_post(
        int $paymentId
    ): JsonResponse {
        $payment = PaymentHelper::getSingle(
            Auth::id(),
            $paymentId
        );

        if ($payment[0]) {
            $currency = paymentSetting('currency_icon');
            $features = json_decode($payment[1]['planFeatures'], true);
            $features['countdown'] = isset($features['countdown']) && $features['countdown'] == 1
                ? __('lang.enabled')
                : __('lang.disabled');
            $features['api'] = isset($features['api']) && $features['api'] == 1
                ? __('lang.enabled')
                : __('lang.disabled');

            $paymentData = [
                'userIp' => $payment[1]['created_by_ip'],
                'transaction' => $payment[1]['transaction'],
                'planName' => $payment[1]['planName'],
                'planPrice' => $currency.$payment[1]['planPrice'],
                'planDuration' => $payment[1]['planDuration'],
                'planFeatures' => $features,
                'gatewayName' => $payment[1]['gateway'],
                'paymentInfo' => e($payment[1]['info']) ?? '-',
            ];

            return response()->json([
                'result' => true,
                'data' => $paymentData
            ]);
        }

        return response()->json([
            'result' => false,
            'data' => __('lang.data_not_found'),
        ]);
    }

    public function plan(): View
    {
        $plan = PlanHelper::myPlan(Auth::id());

        return view('user.payments.plan.index', [
            'functions' => 'user.payments.plan.function',
            'sidebar' => 'payments_plan',
            'pageName' => pageName([__('lang.payments'),__('lang.plan')]),
            'plan' => $plan,
        ]);
    }

}
