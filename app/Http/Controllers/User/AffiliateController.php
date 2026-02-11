<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Helpers\AnalyticsHelper;
use Illuminate\Support\Facades\Auth;
use App\Models\Withdrawals;
use App\Helpers\UserHelper;
use App\Models\WithdrawalMethods;

class AffiliateController extends Controller
{

    public function statistics(): View
    {
        if (affiliateSetting('status') == 0) {
            return abort(404);
        }

        return view('user.affiliate.statistics.index', [
            'functions' => 'user.affiliate.statistics.function',
            'sidebar' => 'statistics',
            'pageName' => pageName([__('lang.statistics')]),
        ]);
    }

    public function statistics_post(): JsonResponse
    {
        if (affiliateSetting('status') == 0) {
            return response()->json([
                'result' => false,
                'data' => __('lang.error'),
            ]);
        }

        $revenueData = AnalyticsHelper::stats(Auth::id());

        $revenueArr = [];
        if ($revenueData[0]) {
            $currency = paymentSetting('currency_icon');
            foreach ($revenueData[1] as $revenue) {

                $revenueArr[] = [
                    'date' => dateFormat($revenue['date']),
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
    
    public function withdrawal(): View
    {
        if (affiliateSetting('status') == 0) {
            return abort(404);
        }

        $methods = WithdrawalMethods::where('status', 1)->get();

        return view('user.affiliate.withdrawal.index', [
            'functions' => 'user.affiliate.withdrawal.function',
            'sidebar' => 'withdrawal',
            'pageName' => pageName([__('lang.withdrawal')]),
            'methods' => $methods,
        ]);
    }

    public function withdrawal_post(): JsonResponse
    {
        if (affiliateSetting('status') == 0) {
            return response()->json([
                'result' => false,
                'data' => __('lang.error'),
            ]);
        }

        $withdrawalsModel = new Withdrawals();
        $withdrawals = $withdrawalsModel
            ->fetchUserWithdrawals(Auth::id());

        $withdrawalsArr = [];
        if ($withdrawals) {
            $currency = paymentSetting('currency_icon');
            foreach ($withdrawals as $withdrawal) {

                $withdrawalsArr[] = [
                    'date' => dateFormat(
                        $withdrawal['date'],
                    ),
                    'amount' => $currency . $withdrawal['amount'],
                    'gateway' => $withdrawal['methodName'],
                    'status' => UserHelper::withdrawalsTableBadges(
                        $withdrawal['status'],
                        __('lang.pending'),
                        __('lang.completed'),
                        __('lang.rejected'),
                    ),
                    'action' => UserHelper::withdrawalsTableButtons(
                        $withdrawal['id'],
                        $withdrawal['status']
                    ),
                ];
            }
        }

        return response()->json([
            'result' => true,
            'data' => $withdrawalsArr,
        ]);
    }

    public function withdrawal_request(
        Request $request
    ): RedirectResponse {
        if (affiliateSetting('status') == 0) {
            return back()
                ->with('error', __('lang.error'));
        }

        $request->validate([
            'method' => 'required|numeric',
            'amount' => 'required|numeric',
            'details' => 'required|max:10000',
        ]);

        $methodId = $request->input('method');
        $method = WithdrawalMethods::where('id', $methodId)
            ->first();

        if (!$method || $method->status == 0) {
            return back()
                ->with('error', __('lang.payment_method_unavailable'));
        }

        $userId = Auth::id();
        $revenueData = AnalyticsHelper::stats($userId);
        if (!$revenueData[0]) {
            return back()
                ->with('error', __('lang.error'));
        }

        $totalRevenue = $revenueData[2];
        $requestedAmount = $request->input('amount');

        if (round($requestedAmount, 4) < $method->minimum) {
            return back()
                ->with('error', __('lang.withdrawal_minimum_error'));
        }

        if ($totalRevenue < $request->input('amount')) {
            return back()
                ->with('error', __('lang.withdrawal_amount_error'));
        }

        $withdrawalExist = Withdrawals::where('created_by_id', $userId)
            ->where('status', 0)
            ->first();
        if ($withdrawalExist) {
            return back()
                ->with('error', __('lang.withdrawal_already_error'));
        }

        $userIp = $request->ip();
        $withdrawal = Withdrawals::create([
            'created_by_id' => $userId,
            'created_by_ip' => $userIp,
            'updated_by_id' => $userId,
            'updated_by_ip' => $userIp,
            'details' => $request->input('details'),
            'amount' => $request->input('amount'),
            'payment_method' => $request->input('method'),
            'status' => 0,
        ]);

        if (!$withdrawal) {
            return back()
                ->with('error', __('lang.data_add_error'));
        }

        return back()
            ->with('success', __('lang.data_add'));
    }

    public function withdrawal_cancel(
        int $withdrawalId
    ): RedirectResponse {
        if (affiliateSetting('status') == 0) {
            return back()
                ->with('error', __('lang.error'));
        }

        $withdrawal = Withdrawals::where('id', $withdrawalId)
            ->where('created_by_id', Auth::id())
            ->first();

        if (!$withdrawal) {
            return back()
                ->with('error', __('lang.data_not_found'));
        }

        if ($withdrawal->status == 1) {
            return back()
                ->with('error', __('lang.completed_withdrawal_cannot_cancel'));
        }

        $delete = Withdrawals::where('id', $withdrawalId)
            ->where('created_by_id', Auth::id())
            ->delete();

        if ($delete) {
            return back()
                ->with('success', __('lang.data_cancel'));
        }

        return back()
            ->with('error', __('lang.data_cancel_error'));
    }

    public function withdrawal_balance(): JsonResponse
    {
        if (affiliateSetting('status') == 0) {
            return back()
                ->with('error', __('lang.error'));
        }
        
        $revenueData = AnalyticsHelper::stats(Auth::id());

        if (!$revenueData[0]) {
            return response()->json([
                'result' => true,
                'data' => 0,
            ]);
        }

        return response()->json([
            'result' => true,
            'data' => $revenueData[2],
        ]);
    }

}
