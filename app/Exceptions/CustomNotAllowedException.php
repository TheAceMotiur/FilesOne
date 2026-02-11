<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;
use App\Helpers\SeoHelper;

class CustomNotAllowedException extends Exception
{
    public function render(): Response
    {
        $seo = SeoHelper::pageSeo('403');
        $pageData = [
            'functions' => 'frontend.notice.function',
            'page' => '403',
            'pageKey' => '403',
            'seoData' => $seo,
        ];
        return response()->view("frontend.notice.index", $pageData, 403);
    }
}
