<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Models\Withdrawals;
use App\Helpers\AdminHelper;
use App\Models\User;

class WithdrawalController extends Controller
{
    public function withdrawals(): View
    {
        return view('admin.withdrawals.index', [
            'functions' => 'admin.withdrawals.function',
            'sidebar' => 'withdrawals',
            'pageName' => pageName([__('lang.withdrawals')]),
        ]);
    }

    public function withdrawals_post(): JsonResponse
    {
        $withdrawalsModel = new Withdrawals;
        $withdrawalsData = $withdrawalsModel->fetchAllWithdrawals();

        if ($withdrawalsData) {
            $withdrawalsArr = [];
            $currency = paymentSetting('currency_icon');
            foreach ($withdrawalsData as $withdrawalData) {

                $withdrawalsArr[] = [
                    'date' => dateFormat($withdrawalData['date']),
                    'user' => $withdrawalData['userId'] != '-'
                        ? AdminHelper::userDetails(
                            $withdrawalData['name'],
                            $withdrawalData['userId'],
                            $withdrawalData['email'],
                            false
                        )
                        : $withdrawalData['name'],
                    'amount' => $currency . $withdrawalData['amount'],
                    'gateway' => $withdrawalData['methodName'],
                    'status' => AdminHelper::withdrawalsTableBadges(
                        $withdrawalData['status'],
                        __('lang.pending'),
                        __('lang.verified'),
                        __('lang.rejected'),
                    ),
                    'action' => AdminHelper::withdrawalsTableButtons(
                        $withdrawalData['id'],
                        $withdrawalData['status'],
                    ),
                ];
            }

            return response()->json([
                'result' => true,
                'data' => $withdrawalsArr
            ]);
        }

        return response()->json([
            'result' => false,
        ]);
    }

    public function withdrawals_single_post(
        int $withdrawalId
    ): JsonResponse {
        $withdrawalsModel = new Withdrawals;
        $withdrawal = $withdrawalsModel->fetchWithdrawal($withdrawalId);

        if ($withdrawal) {
            $withdrawalData = [
                'gateway' => $withdrawal['gateway'],
                'amount' => $withdrawal['amount'],
                'info' => $withdrawal['details'],
                'userIp' => $withdrawal['created_by_ip'],
            ];

            return response()->json([
                'result' => true,
                'data' => $withdrawalData
            ]);
        }

        return response()->json([
            'result' => false,
        ]);
    }
    
    public function withdrawals_reject(
        int $withdrawalId
    ): RedirectResponse {
        $withdrawalData = Withdrawals::where('id', $withdrawalId)
            ->first();
        $userData = User::where('id', $withdrawalData->created_by_id)
            ->first();

        if (!$withdrawalData) {
            return back()
                ->with('error', __('lang.data_not_found'));
        }
        
        if (!$userData) {
            Withdrawals::where('id', $withdrawalId)
                ->delete();
            return back()
                ->with('success', __('lang.data_delete'));
        }

        $update = Withdrawals::where('id', $withdrawalId)
            ->update([
                'status' => 2
            ]);

        if ($update) {
            return back()
                ->with('success', __('lang.data_update'));
        }

        return back()
            ->with('error', __('lang.data_update_error'));
    }

    public function withdrawals_verify(
        int $withdrawalId
    ): RedirectResponse {
        $withdrawalData = Withdrawals::where('id', $withdrawalId)
            ->first();
        $userData = User::where('id', $withdrawalData->created_by_id)
            ->first();

        if (!$withdrawalData) {
            return back()
                ->with('error', __('lang.data_not_found'));
        }
        
        if (!$userData) {
            return back()
                ->with('error', __('lang.cannot_verify_withdrawal'));
        }

        $update = Withdrawals::where('id', $withdrawalId)
            ->update(['status' => 1]);

        if ($update) {
            return back()
                ->with('success', __('lang.data_update'));
        }

        return back()
            ->with('error', __('lang.data_update_error'));
    }

}
