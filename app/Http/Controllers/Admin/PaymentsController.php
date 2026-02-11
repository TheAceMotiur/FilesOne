<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Models\PaymentGateways;
use App\Models\Payment;
use App\Models\PaymentLogs;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Helpers\AdminHelper;
use App\Helpers\PaymentHelper;
use App\Payment\Bank;

class PaymentsController extends Controller
{
    public function settings(): View
    {
        $bank = PaymentGateways::where('id', 1)->first();
        $stripe = PaymentGateways::where('id', 2)->first();
        $razorpay = PaymentGateways::where('id', 3)->first();

        return view('admin.payments.settings.index', [
            'functions' => 'admin.payments.settings.function',
            'sidebar' => 'payments_settings',
            'pageName' => pageName([__('lang.payments'), __('lang.settings')]),
            'bank'=> $bank,
            'stripe'=> $stripe,
            'razorpay'=> $razorpay
        ]);
    }

    public function settings_post(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'currency-name' => 'required|string|max:255',
            'currency-icon' => 'required|max:25',
        ]);

        foreach ($request->all() as $key => $value) {
            if (in_array($key, ['currency-name', 'currency-icon'])) {
                $dbKey = str_replace("-", "_", $key);
                $update = Payment::where('name', $dbKey)
                    ->update([
                        'updated_by_id' => Auth::id(),
                        'updated_by_ip' => $request->ip(),
                        'value' => $value,
                    ]);
            }
        }

        if (Cache::has('paymentSetting')) {
            Cache::forget('paymentSetting');
        }

        if (!$update) {
            return back()
                ->with('error', __('lang.data_update_error'));
        }

        return back()
            ->with('success', __('lang.data_update'));
    }

    public function settings_post_bank(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'bank-status' => 'required|in:1,0',
            'bank-info' => 'required_if:bank-status,1|nullable|max:1000',
        ]);

        $update = PaymentGateways::where('id', 1)
            ->update([
                'updated_by_id' => Auth::id(),
                'updated_by_ip' => $request->ip(),
                'status' => $request->input('bank-status'),
                'info' => $request->input('bank-info'),
            ]);

        if (!$update) {
            return back()
                ->with('error', __('lang.data_update_error'));
        }

        return back()
            ->with('success', __('lang.data_update'));
    }

    public function settings_post_stripe(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'stripe-status' => 'required|in:1,0',
            'stripe-public' => 'required_if:stripe-status,1|max:1000',
            'stripe-secret' => 'required_if:stripe-status,1|max:1000',
        ]);

        $update = PaymentGateways::where('id', 2)
            ->update([
                'updated_by_id' => Auth::id(),
                'updated_by_ip' => $request->ip(),
                'status' => $request->input('stripe-status'),
                'public' => $request->input('stripe-public'),
                'secret' => $request->input('stripe-secret'),
            ]);

        if (!$update) {
            return back()
                ->with('error', __('lang.data_update_error'));
        }

        return back()
            ->with('success', __('lang.data_update'));
    }

    public function settings_post_razorpay(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'razorpay-status' => 'required|in:1,0',
            'razorpay-public' => 'required_if:razorpay-status,1|max:1000',
            'razorpay-secret' => 'required_if:razorpay-status,1|max:1000',
        ]);

        $update = PaymentGateways::where('id', 3)
            ->update([
                'updated_by_id' => Auth::id(),
                'updated_by_ip' => $request->ip(),
                'status' => $request->input('razorpay-status'),
                'public' => $request->input('razorpay-public'),
                'secret' => $request->input('razorpay-secret'),
            ]);

        if (!$update) {
            return back()
                ->with('error', __('lang.data_update_error'));
        }

        return back()
            ->with('success', __('lang.data_update'));
    }

    public function plans(): View
    {
        return view('admin.payments.plans.all.index', [
            'functions' => 'admin.payments.plans.all.function',
            'sidebar' => 'payments_plans',
            'pageName' => pageName([__('lang.payments'), __('lang.plans')]),
        ]);
    }

    public function plans_post(): JsonResponse
    {
        $plans = Plan::orderBy('price_monthly')
            ->get();

        $paymentsArr = [];
        if ($plans->isNotEmpty()) {
            $currency = paymentSetting('currency_icon');
            foreach ($plans as $plan) {
                $paymentsArr[] = [
                    'name' => $plan->name,
                    'monthly_price' => $currency . ($plan->price_monthly ?? 0),
                    'yearly_price' => $currency . ($plan->price_yearly ?? 0),
                    'status' => AdminHelper::paymentPlansTableBadges(
                        $plan->status,
                        __('lang.enabled'),
                        __('lang.disabled'),
                    ),
                    'action' => AdminHelper::paymentPlansTableButtons(
                        $plan->id,
                    ),
                ];
            }
        }

        return response()->json([
            'result' => true,
            'data' => $paymentsArr
        ]);
    }

    public function plans_add(): View
    {
        return view('admin.payments.plans.add.index', [
            'functions' => 'admin.payments.plans.add.function',
            'sidebar' => 'payments_plans',
            'pageName' => pageName([__('lang.payments'), __('lang.plans')]),
        ]);
    }
    
    public function plans_add_post(
        Request $request
    ): RedirectResponse {
        $fileFormats = uploadableTypes();
        $request->validate([
            'name' => 'required|string|max:255|unique:payment_plans,name',
            'status' => 'required|in:1,0',
            'price-monthly' => 'required|numeric|min:0',
            'price-yearly' => 'required|numeric|min:0',
            'formats' => "required|array|in:{$fileFormats}",
            'disk' => 'required|numeric|min:1|max:1024000',
            'countdown' => 'required|in:0,1',
            'auto-deletion' => 'required|in:0,1,7,30,90,180,360',
            'api' => 'required|in:0,1',
        ]);

        $planFeatures = [
            'formats' => $request->input('formats'),
            'disk' => $request->input('disk'),
            'countdown' => $request->input('countdown'),
            'auto_deletion' => $request->input('auto-deletion'),
            'api' => $request->input('api'),
        ];

        $userId = Auth::id();
        $userIp = $request->ip();
        $create = Plan::create([
            'created_by_id' => $userId,
            'created_by_ip' => $userIp,
            'updated_by_id' => $userId,
            'updated_by_ip' => $userIp,
            'name' => $request->input('name'),
            'price_monthly' => $request->input('price-monthly'),
            'price_yearly' => $request->input('price-yearly'),
            'features' => json_encode($planFeatures),
            'status' => $request->input('status'),
        ]);

        if (!$create) {
            return back()
                ->with('error', __('lang.data_add_error'));
        }

        return back()
            ->with('success', __('lang.data_add'));
    }

    public function plans_edit(
        int $planId
    ): View {
        $planData = Plan::where('id', $planId)
            ->first();

        if (!$planData) {
            abort(404);
        }

        $planFeatures = $planData->features
            ? json_decode($planData->features, true)
            : [];
        $planFeatures['api'] ??= 0;
        $planFeatures['countdown'] ??= 0;

        return view('admin.payments.plans.edit.index', [
            'functions' => 'admin.payments.plans.edit.function',
            'sidebar' => 'payments_plans',
            'pageName' => pageName([__('lang.payments'), __('lang.plans')]),
            'plan' => $planData,
            'features' => $planFeatures,
        ]);
    }

    public function plans_edit_post(
        Request $request,
        int $planId
    ): RedirectResponse {
        $planData = Plan::where('id', $planId)
            ->first();

        if (!$planData) {
            return back()
                ->with('error', __('lang.data_not_found'));
        }

        $fileFormats = uploadableTypes();

        if ($planId == 1) {
            $request->validate([
                'name' => 'required|string|max:255|'
                    . 'unique:payment_plans,name,' . $planData->id,
                'formats' => "required|array|in:{$fileFormats}",
                'disk' => 'required|numeric|min:1|max:1024000',
                'countdown' => 'required|in:0,1',
                'auto-deletion' => 'required|in:0,1,7,30,90,180,360',
                'api' => 'required|in:1,0',
            ]);
        } else {
            $request->validate([
                'name' => 'required|string|max:255|'
                    . 'unique:payment_plans,name,' . $planData->id,
                'status' => 'required|in:1,0',
                'price-monthly' => 'required|numeric|min:0',
                'price-yearly' => 'required|numeric|min:0',
                'formats' => "required|array|in:{$fileFormats}",
                'disk' => 'required|numeric|min:1|max:1024000',
                'countdown' => 'required|in:0,1',
                'auto-deletion' => 'required|in:0,1,7,30,90,180,360',
                'api' => 'required|in:1,0',
            ]);
        }

        $planFeatures = [
            'formats' => $request->input('formats'),
            'disk' => $request->input('disk'),
            'countdown' => $request->input('countdown'),
            'auto_deletion' => $request->input('auto-deletion'),
            'api' => $request->input('api'),
        ];

        $update = Plan::where('id', $planData->id)
            ->update([
                'updated_by_id' => Auth::id(),
                'updated_by_ip' => $request->ip(),
                'name' => $request->input('name'),
                'price_monthly' => $planId == 1
                    ? NULL
                    : $request->input('price-monthly'),
                'price_yearly' => $planId == 1
                    ? NULL
                    : $request->input('price-yearly'),
                'features' => json_encode($planFeatures),
                'status' => $planId == 1
                    ? 1
                    : $request->input('status'),
            ]);

        if (!$update) {
            return back()
                ->with('error', __('lang.data_update_error'));
        }

        return back()
            ->with('success', __('lang.data_update'));
    }

    public function plans_delete(
        int $planId
    ): RedirectResponse {
        $planData = Plan::where('id', $planId)
            ->first();

        if (!$planData) {
            return back()
                ->with('error', __('lang.data_not_found'));
        }

        if ($planData->free == 1) {
            return back()
                ->with('error', __('lang.free_plan_delete_error'));
        }

        $delete = $planData->delete();

        if ($delete) {

            PaymentLogs::where('plan', $planId)->delete();

            return back()
                ->with('success', __('lang.data_delete'));
        }

        return back()
            ->with('error', __('lang.data_delete_error'));
    }

    public function logs(): View
    {
        return view('admin.payments.logs.index', [
            'functions' => 'admin.payments.logs.function',
            'sidebar' => 'payments_logs',
            'pageName' => pageName([__('lang.payments'), __('lang.logs')]),
        ]);
    }

    public function logs_post(): JsonResponse
    {
        $logs = PaymentHelper::getAll(Auth::id());

        $logsData = [];
        if ($logs[0]) {
            $currency = paymentSetting('currency_icon');
            foreach ($logs[1] as $log) {
                $logsData[] = [
                    'date' => dateFormat($log['date']),
                    'user' => $log['userId'] != '-'
                        ? AdminHelper::userDetails(
                            $log['userName'],
                            $log['userId'],
                            $log['userEmail'],
                            false
                        )
                        : $log['userName'],
                    'plan' => $log['planName'],
                    'duration' => $log['planDuration'] == 1
                        ? __('lang.monthly')
                        : __('lang.yearly'),
                    'price' => $currency . $log['planPrice'],
                    'gateway' => $log['gateway'],
                    'status' => AdminHelper::paymentLogsTableBadges(
                        $log['status'],
                        __('lang.completed'),
                        __('lang.pending'),
                        __('lang.rejected')
                    ),
                    'action' => AdminHelper::paymentLogsTableButtons(
                        $log['id'],
                        $log['status']
                    ),
                ];
            }
        }

        return response()->json([
            'result' => true,
            'data' => $logsData,
        ]);
    }

    public function logs_single_post(
        int $logId
    ): JsonResponse {
        $log = PaymentHelper::getSingle(Auth::id(),$logId);
        $currency = paymentSetting('currency_icon');
        if ($log[0]) {

            $features = json_decode($log[1]['planFeatures'], true);
            $features['countdown'] = isset($features['countdown']) && $features['countdown'] == 1
                ? __('lang.enabled')
                : __('lang.disabled');
            $features['api'] = isset($features['api']) && $features['api'] == 1
                ? __('lang.enabled')
                : __('lang.disabled');

            $logData = [
                'userIp' => $log[1]['created_by_ip'],
                'transaction' => $log[1]['transaction'],
                'planName' => $log[1]['planName'],
                'planPrice' => $currency . $log[1]['planPrice'],
                'planDuration' => $log[1]['planDuration'],
                'planFeatures' => $features,
                'gatewayName' => $log[1]['gateway'],
                'paymentInfo' => e($log[1]['info']) ?? '-',
            ];

            return response()->json([
                'result' => true,
                'data' => $logData
            ]);
        }

        return response()->json([
            'result' => false,
            'data' => __('lang.data_not_found'),
        ]);
    }

    public function logs_verify(
        int $logId
    ): RedirectResponse {
        $bank = new Bank();
        $verify = $bank->verify(Auth::id(), $logId);
  
        if (!$verify[0]) {
            return back()
                ->with('error', $verify[1]);
        }

        return back()
            ->with('success', __('lang.data_update'));
    }

    public function logs_reject(
        int $logId
    ): RedirectResponse {
        $bank = new Bank();
        $reject = $bank->reject(Auth::id(), $logId);
  
        if (!$reject[0]) {
            return back()
                ->with('error', $reject[1]);
        }

        return back()
            ->with('success', __('lang.data_update'));
    }

}
