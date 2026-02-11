<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use App\Helpers\SeoHelper;

class HomeController extends Controller
{
    public function home(): View
    {
        $seo = SeoHelper::pageSeo('home');

        $sliderImages = [
            widget('home','top_area','slider_image_1') ?? false,
            widget('home','top_area','slider_image_2') ?? false,
            widget('home','top_area','slider_image_3') ?? false,
            widget('home','top_area','slider_image_4') ?? false,
            widget('home','top_area','slider_image_5') ?? false,
            widget('home','top_area','slider_image_6') ?? false,
            widget('home','top_area','slider_image_7') ?? false,
            widget('home','top_area','slider_image_8') ?? false,
            widget('home','top_area','slider_image_9') ?? false,
            widget('home','top_area','slider_image_10') ?? false,
        ];

        return view('frontend.home.index', [
            'functions' => 'frontend.home.function',
            'modals' => 'frontend.home.modals',
            'pageKey' => 'home',
            'seoData' => $seo,
            'sliderImages' => $sliderImages,
        ]);
    }

}
