<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use App\Helpers\SeoHelper;
use App\Models\Plan;

class PricingController extends Controller
{
    public function pricing(): View
    {
        $plans = Plan::select(
            'id',
            'name',
            'price_monthly',
            'price_yearly',
            'features',
            'status',
            'free'
        )
        ->where('status', 1)
        ->get();

        $seo = SeoHelper::pageSeo('pricing');

        
        return view('frontend.pricing.index', [
            'functions' => 'frontend.pricing.function',
            'pageKey' => 'pricing',
            'seoData' => $seo,
            'plans' => $plans,
        ]);
    }

}
