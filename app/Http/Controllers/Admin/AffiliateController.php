<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\SettingsAffiliate;
use App\Models\PayoutRates;
use App\Models\Withdrawals;
use App\Models\WithdrawalMethods;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AdminHelper;
use App\Helpers\AnalyticsHelper;
use Illuminate\Support\Facades\Cache;

class AffiliateController extends Controller
{
    public function settings(): View
    {
        return view('admin.affiliate.settings.index', [
            'functions' => 'admin.affiliate.settings.function',
            'sidebar' => 'affiliate_settings',
            'pageName' => pageName([
                __('lang.affiliate'),
                __('lang.settings')
            ]),
        ]);
    }

    public function settings_post(
        Request $request
    ): mixed {
        $request->validate([
            'status' => 'required|in:1,0',
            'type' => 'required|in:1,2',
        ]);

        $inputs = $request->only([
            'status',
            'type',
        ]);

        $userId = Auth::id();
        $userIp = $request->ip();

        foreach ( $inputs as $key => $value ) {
            $dbKey = str_replace("-", "_", $key);
            SettingsAffiliate::where("name", $dbKey)
                ->update([
                    'updated_by_id' => $userId,
                    'updated_by_ip' => $userIp,
                    'value' => $value
                ]);
        }

        if (Cache::has('affiliateSetting')) {
            Cache::forget('affiliateSetting');
        }

        return back()
            ->with('success', __('lang.data_update'));
    }
    
    public function statistics(): View
    {
        return view('admin.affiliate.statistics.index', [
            'functions' => 'admin.affiliate.statistics.function',
            'sidebar' => 'affiliate_statistics',
            'pageName' => pageName([
                __('lang.affiliate'),
                __('lang.statistics')
            ]),
        ]);
    }

    public function statistics_post(): JsonResponse
    {
        $revenueData = AnalyticsHelper::stats(Auth::id());

        $revenueArr = [];
        if ($revenueData[0]) {
            $currency = paymentSetting('currency_icon');
            foreach ($revenueData[1] as $revenue) {
                $revenueArr[] = [
                    'date' => dateFormat(
                        $revenue['date'],
                    ),
                    'file' => $revenue['fileName'],
                    'country' => $revenue['revenueData']['countryCode'] ?? '-',
                    'revenue' => $currency . $revenue['revenue'],
                ];
            }
        }

        return response()->json([
            'result' => true,
            'data' => $revenueArr,
            'total' => $revenueData[2],
        ]);
    }

    public function users(): View
    {
        return view('admin.affiliate.users.index', [
            'functions' => 'admin.affiliate.users.function',
            'sidebar' => 'affiliate_users',
            'pageName' => pageName([
                __('lang.affiliate'),
                __('lang.users')
            ]),
        ]);
    }

    public function users_post(): JsonResponse
    {
        $revenueData = AnalyticsHelper::statsAll();

        $revenueArr = [];
        if ($revenueData[0]) {
            $currency = paymentSetting('currency_icon');
            foreach ($revenueData[1] as $revenue) {
                $revenueArr[] = [
                    'id' => $revenue['id'],
                    'email' => $revenue['email'],
                    'revenue' => $revenue['revenue'] > 0
                        ? "{$currency}{$revenue['revenue']}"
                        : "{$currency}0",
                ];
            }
        }

        return response()->json([
            'result' => true,
            'data' => $revenueArr,
        ]);
    }
    
    public function payout_rates(): View
    {
        return view('admin.affiliate.payout_rates.all.index', [
            'functions' => 'admin.affiliate.payout_rates.all.function',
            'sidebar' => 'affiliate_payout_rates',
            'pageName' => pageName([
                __('lang.affiliate'),
                __('lang.payout_rates')
            ]),
        ]);
    }

    public function payout_rates_data(): JsonResponse
    {
        $rates = PayoutRates::orderBy('country_name','asc')
            ->get();

        $ratesArr = [];
        if ($rates) {
            $currency = paymentSetting('currency_icon');
            foreach ($rates as $rate) {
                $ratesArr[] = [
                    'country_name' => $rate->country_name,
                    'country_code' => $rate->country_code,
                    'rate' => $currency . $rate->rate,
                    'action' => AdminHelper::payoutRatesTableButtons(
                        $rate->id
                    ),
                ];
            }
        }

        return response()->json([
            'result' => true,
            'data' => $ratesArr
        ]);
    }

    public function payout_rates_add(): View
    {
        $countriesJson = file_get_contents(public_path('countries.json'));

        return view('admin.affiliate.payout_rates.add.index', [
            'functions' => 'admin.affiliate.payout_rates.add.function',
            'sidebar' => 'affiliate_payout_rates',
            'pageName' => pageName([
                __('lang.affiliate'),
                __('lang.payout_rates')
            ]),
            'countries' => json_decode($countriesJson, true),
        ]);
    }

    public function payout_rates_add_post(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'country' => 'required|string|max:100',
            'rate' => 'required|decimal:0,5|min:0|max:1000',
        ]);

        $userId = Auth::user()->id;
        $userIp = $request->ip();
        $countryArr = explode(',', $request->input('country'));

        $rate = PayoutRates::where('country_name', $countryArr[1])
            ->orWhere('country_code', $countryArr[0])
            ->first();
    
        if ($rate) {
            return back()
                ->with('error', __('lang.country_already_added'));
        }

        $create = PayoutRates::create([
            'created_by_id' => $userId,
            'created_by_ip' => $userIp,
            'updated_by_id' => $userId,
            'updated_by_ip' => $userIp,
            'country_name' => $countryArr[1],
            'country_code' => $countryArr[0],
            'rate' => $request->input('rate'),
        ]);

        if ($create) {
            return back()
                ->with('success', __('lang.data_add'));
        }

        return back()
            ->with('error', __('lang.data_add_error'));
    }

    public function payout_rates_edit(
        int $rateId
    ): View {
        $rate = PayoutRates::where('id', $rateId)->first();
        if (!$rate) {
            abort(404);
        }
        $countriesJson = file_get_contents(public_path('countries.json'));

        return view('admin.affiliate.payout_rates.edit.index', [
            'functions' => 'admin.affiliate.payout_rates.edit.function',
            'sidebar' => 'affiliate_payout_rates',
            'pageName' => pageName([
                __('lang.affiliate'),
                __('lang.payout_rates')
            ]),
            'countries' => json_decode($countriesJson, true),
            'rate' => $rate,
        ]);
    }

    public function payout_rates_edit_post(
        Request $request, 
        int $rateId
    ): RedirectResponse {
        $rate = PayoutRates::where('id', $rateId)->first();

        if (!$rate) {
            return back()
                ->with('error', __('lang.data_not_found'));
        }

        $userId = Auth::user()->id;
        $userIp = $request->ip();
        $countryArr = explode(',', $request->input('country'));

        $rate = PayoutRates::where('id', '!=', $rateId)
            ->where(function($query) use ($countryArr) {
                $query->where('country_name', $countryArr[1])
                    ->orWhere('country_code', $countryArr[0]);
            })
            ->first();

        if ($rate) {
            return back()
                ->with('error', __('lang.country_already_added'));
        }

        $update = PayoutRates::where('id', $rateId)
            ->update([
                'updated_by_id' => $userId,
                'updated_by_ip' => $userIp,
                'country_name' => $countryArr[1],
                'country_code' => $countryArr[0],
                'rate' => $request->input('rate'),
            ]);

        if ($update) {
            return back()
                ->with('success', __('lang.data_update'));
        }

        return back()
            ->with('error', __('lang.data_update_error'));
    }

    public function payout_rates_delete(
        int $rateId
    ): RedirectResponse {
        $rate = PayoutRates::where('id', $rateId)->first();

        if (!$rate) {
            return back()
                ->with('error', __('lang.data_not_found'));
        }

        $delete = $rate->delete();

        if ($delete) {
            return back()
                ->with('success', __('lang.data_delete'));
        }

        return back()
            ->with('error', __('lang.data_delete_error'));
    }

    public function withdrawal_methods(): View
    {
        return view('admin.affiliate.withdrawal_methods.all.index', [
            'functions' => 'admin.affiliate.withdrawal_methods.all.function',
            'sidebar' => 'affiliate_withdrawal_methods',
            'pageName' => pageName([
                __('lang.affiliate'),
                __('lang.withdrawal_methods')
            ]),
        ]);
    }

    public function withdrawal_methods_data(): JsonResponse
    {
        $methods = WithdrawalMethods::orderBy('name','asc')
            ->get();

        $methodsArr = [];
        if ($methods->isNotEmpty()) {
            foreach ($methods as $rate) {
                $methodsArr[] = [
                    'method_name' => $rate->name,
                    'min_amount' => $rate->minimum,
                    'status' => AdminHelper::methodsTableBadges(
                        $rate->status,
                        __('lang.enabled'),
                        __('lang.disabled'),
                    ),
                    'action' => AdminHelper::methodsTableButtons(
                        $rate->id
                    ),
                ];
            }
        }

        return response()->json([
            'result' => true,
            'data' => $methodsArr
        ]);
    }

    public function withdrawal_methods_add(): View
    {
        return view('admin.affiliate.withdrawal_methods.add.index', [
            'functions' => 'admin.affiliate.withdrawal_methods.add.function',
            'sidebar' => 'affiliate_withdrawal_methods',
            'pageName' => pageName([
                __('lang.affiliate'),
                __('lang.withdrawal_methods')
            ]),
        ]);
    }

    public function withdrawal_methods_add_post(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'name' => 'required|string|max:100|unique:withdrawal_methods,name',
            'minimum' => 'required|numeric|min:0|max:10000',
            'status' => 'required|in:0,1',
            'description' => 'required|string',
        ]);

        $userId = Auth::user()->id;
        $userIp = $request->ip();
        $create = WithdrawalMethods::create([
            'created_by_id' => $userId,
            'created_by_ip' => $userIp,
            'updated_by_id' => $userId,
            'updated_by_ip' => $userIp,
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'minimum' => $request->input('minimum'),
            'status' => $request->input('status'),
        ]);

        if ($create) {
            return back()
                ->with('success', __('lang.data_add'));
        }

        return back()
            ->with('error', __('lang.data_add_error'));
    }

    public function withdrawal_methods_edit(
        int $methodId
    ): View {
        $method = WithdrawalMethods::where('id', $methodId)->first();
        if (!$method) {
            abort(404);
        }
        return view('admin.affiliate.withdrawal_methods.edit.index', [
            'functions' => 'admin.affiliate.withdrawal_methods.edit.function',
            'sidebar' => 'affiliate_withdrawal_methods',
            'pageName' => pageName([
                __('lang.affiliate'),
                __('lang.withdrawal_methods')
            ]),
            'method' => $method,
        ]);
    }

    public function withdrawal_methods_edit_post(
        Request $request, 
        int $methodId
    ): RedirectResponse {
        $method = WithdrawalMethods::where('id', $methodId)->first();
        if (!$method) {
            return back()
                ->with('error', __('lang.data_not_found'));
        }

        $userId = Auth::user()->id;
        $userIp = $request->ip();
        $update = WithdrawalMethods::where('id', $methodId)
            ->update([
                'updated_by_id' => $userId,
                'updated_by_ip' => $userIp,
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'minimum' => $request->input('minimum'),
                'status' => $request->input('status'),
            ]);

        if ($update) {
            return back()
                ->with('success', __('lang.data_update'));
        }

        return back()
            ->with('error', __('lang.data_update_error'));
    }

    public function withdrawal_methods_delete(
        int $methodId
    ): RedirectResponse {
        $method = WithdrawalMethods::where('id', $methodId)->first();

        if (!$method) {
            return back()
                ->with('error', __('lang.data_not_found'));
        }

        $delete = $method->delete();

        if ($delete) {
            Withdrawals::where('payment_method', $methodId)->delete();
            return back()
                ->with('success', __('lang.data_delete'));
        }

        return back()
            ->with('error', __('lang.data_delete_error'));
    }
    
}
