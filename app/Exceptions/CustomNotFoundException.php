<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;
use App\Helpers\SeoHelper;

class CustomNotFoundException extends Exception
{
    public function render(): Response
    {
        $seo = SeoHelper::pageSeo('404');
        $pageData = [
            'functions' => 'frontend.notice.function',
            'page' => '404',
            'pageKey' => '404',
            'seoData' => $seo,
        ];
        return response()->view("frontend.notice.index", $pageData, 404);
    }
}
