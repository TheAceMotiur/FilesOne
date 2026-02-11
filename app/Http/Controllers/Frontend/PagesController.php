<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Helpers\SeoHelper;
use App\Models\Pages;

class PagesController extends Controller
{
    public function loadPage(string $path): mixed
    {
        if ($page = Pages::where('url', $path)->first()) {

            if ($page->status == 0) {
                return abort(404);
            }

            $content = json_decode($page->content, true);
            $seo = SeoHelper::pageSeo($page->page_key);

            return view('frontend.pages.index', [
                'functions' => 'frontend.pages.function',
                'page' => $page,
                'content' => $content,
                'pageKey' => $page->page_key,
                'seoData' => $seo,
            ]);
        }

        return abort(404);
    }
}
