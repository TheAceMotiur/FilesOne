<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use App\Helpers\SeoHelper;
use App\Models\PayoutRates;
use App\Models\Withdrawals;
use Carbon\Carbon;

class AffiliateController extends Controller
{
    public function affiliate(): View
    {
        if (affiliateSetting('status') == 0) {
            return abort(404);
        }
        
        $rates = PayoutRates::orderBy("country_name","asc")->get();
        $seo = SeoHelper::pageSeo('affiliate');
        $latestPayments = $this->latestPayments();

        return view('frontend.affiliate.index', [
            'functions' => 'frontend.affiliate.function',
            'pageKey' => 'affiliate',
            'seoData' => $seo,
            'rates' => $rates,
            'currency' => paymentSetting('currency_icon'),
            'latestPayments' => $latestPayments,
        ]);
    }

    private function latestPayments(): mixed
    {
        $dummyIncluded = true;

        $withdrawalsModel = new Withdrawals;
        $withdrawals = $withdrawalsModel->fetchAllWithdrawals(1);
        $dummyData = $this->latestPaymentsDummy();
        $currency = paymentSetting('currency_icon');
        $withdrawalsArr = [];

        if ($withdrawals) {
            
            if (isset($withdrawals[0]) && $withdrawals[0]) {
                if (
                    isset($withdrawals[0]['email']) 
                    && $withdrawals[0]['email']
                ) {
                    $withdrawalsArr[0] = [
                        'date' => dateFormat($withdrawals[0]['date'],'Y-m-d'),
                        'email' => censorEmail($withdrawals[0]['email']),
                        'amount' => $currency . $withdrawals[0]['amount'],
                    ];
                } else {
                    $withdrawalsArr[0] = $dummyIncluded ? $dummyData[0] : null;
                }
            } else {
                $withdrawalsArr[0] = $dummyIncluded ? $dummyData[0] : null;
            }

            if (isset($withdrawals[1]) && $withdrawals[1]) {
                if (
                    isset($withdrawals[1]['email']) 
                    && $withdrawals[1]['email']
                ) {
                    $withdrawalsArr[1] = [
                        'date' => dateFormat($withdrawals[1]['date'],'Y-m-d'),
                        'email' => censorEmail($withdrawals[1]['email']),
                        'amount' => $currency . $withdrawals[1]['amount'],
                    ];
                } else {
                    $withdrawalsArr[1] = $dummyIncluded ? $dummyData[1] : null;
                }
            } else {
                $withdrawalsArr[1] = $dummyIncluded ? $dummyData[1] : null;
            }

            if (isset($withdrawals[2]) && $withdrawals[2]) {
                if (
                    isset($withdrawals[2]['email']) 
                    && $withdrawals[2]['email']
                ) {
                    $withdrawalsArr[2] = [
                        'date' => dateFormat($withdrawals[2]['date'],'Y-m-d'),
                        'email' => censorEmail($withdrawals[2]['email']),
                        'amount' => $currency . $withdrawals[2]['amount'],
                    ];
                } else {
                    $withdrawalsArr[2] = $dummyIncluded ? $dummyData[2] : null;
                }
            } else {
                $withdrawalsArr[2] = $dummyIncluded ? $dummyData[2] : null;
            }

            if (isset($withdrawals[3]) && $withdrawals[3]) {
                if (
                    isset($withdrawals[3]['email']) 
                    && $withdrawals[3]['email']
                ) {
                    $withdrawalsArr[3] = [
                        'date' => dateFormat($withdrawals[3]['date'],'Y-m-d'),
                        'email' => censorEmail($withdrawals[3]['email']),
                        'amount' => $currency . $withdrawals[3]['amount'],
                    ];
                } else {
                    $withdrawalsArr[3] = $dummyIncluded ? $dummyData[3] : null;
                }
            } else {
                $withdrawalsArr[3] = $dummyIncluded ? $dummyData[3] : null;
            }

            if (isset($withdrawals[4]) && $withdrawals[4]) {
                if (
                    isset($withdrawals[4]['email']) 
                    && $withdrawals[4]['email']
                ) {
                    $withdrawalsArr[4] = [
                        'date' => dateFormat($withdrawals[4]['date'],'Y-m-d'),
                        'email' => censorEmail($withdrawals[4]['email']),
                        'amount' => $currency . $withdrawals[4]['amount'],
                    ];
                } else {
                    $withdrawalsArr[4] = $dummyIncluded ? $dummyData[4] : null;
                }
            } else {
                $withdrawalsArr[4] = $dummyIncluded ? $dummyData[4] : null;
            }

        } else {
            $withdrawalsArr = $dummyIncluded ? $dummyData : false;
        }

        return $withdrawalsArr ? array_filter($withdrawalsArr) : false;
    }

    private function latestPaymentsDummy(): array
    {
        $payments = [];
        $now = Carbon::now();
        $currency = paymentSetting('currency_icon');
        
        $payments[0] = [
            'date' => dateFormat($now->subDay(4),'Y-m-d'),
            'email' => 'eg****@gm****.com',
            'amount' => "{$currency}40",
        ];
        $payments[1] = [
            'date' => dateFormat($now->subDay(7),'Y-m-d'),
            'email' => 'ni****@gm****.com',
            'amount' => "{$currency}70",
        ];
        $payments[2] = [
            'date' => dateFormat($now->subDay(12),'Y-m-d'),
            'email' => 'il****@gm****.com',
            'amount' => "{$currency}20",
        ];
        $payments[3] = [
            'date' => dateFormat($now->subDay(16),'Y-m-d'),
            'email' => 'ah****@gm****.com',
            'amount' => "{$currency}60",
        ];
        $payments[4] = [
            'date' => dateFormat($now->subDay(25),'Y-m-d'),
            'email' => 'ka****@gm****.com',
            'amount' => "{$currency}100",
        ];

        return $payments;
    }

}
