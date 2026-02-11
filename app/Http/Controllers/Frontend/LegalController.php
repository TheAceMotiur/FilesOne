<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use App\Models\Pages;
use App\Helpers\SeoHelper;

class LegalController extends Controller
{
    public function terms_of_use(): View {

        $page = Pages::where('page_key', 'terms_of_use')->first();
        $content = json_decode($page->content, true);
        $seo = SeoHelper::pageSeo($page->page_key);

        return view('frontend.terms_of_use.index', [
            'functions' => 'frontend.terms_of_use.function',
            'page' => $page,
            'content' => $content,
            'pageKey' => $page->page_key,
            'seoData' => $seo,
        ]);
    }

    public function privacy_policy(): View {

        $page = Pages::where('page_key', 'privacy_policy')->first();
        $content = json_decode($page->content, true);
        $seo = SeoHelper::pageSeo($page->page_key);

        return view('frontend.privacy_policy.index', [
            'functions' => 'frontend.privacy_policy.function',
            'page' => $page,
            'content' => $content,
            'pageKey' => $page->page_key,
            'seoData' => $seo,
        ]);
    }
}
