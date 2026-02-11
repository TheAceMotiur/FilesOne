<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;
use App\Helpers\SeoHelper;

class CustomServerErrorException extends Exception
{
    public function render(): Response
    {
        $seo = SeoHelper::pageSeo('500');
        $pageData = [
            'functions' => 'frontend.notice.function',
            'page' => '500',
            'pageKey' => '500',
            'seoData' => $seo,
        ];
        return response()->view("frontend.notice.index", $pageData, 500);
    }
}
