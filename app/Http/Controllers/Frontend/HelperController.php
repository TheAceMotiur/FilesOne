<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\SeoHelper;

class HelperController extends Controller
{
    public function theme_mode(
        Request $request
    ): void {
        $mode = $request->input('mode');

        if ($mode == 'dark' || $mode == 'light') {
            $request->session()->put(
                'color',
                $mode
            );

            $selected = $request->input('selected');
            if ($selected) {
                $request->session()->put(
                    'color_selected',
                    true
                );
            }
        }
    }

    public function maintenance()
    {
        $seo = SeoHelper::pageSeo('maintenance');

        return view('frontend.notice.index', [
            'functions' => 'frontend.notice.function',
            'page' => 'maintenance',
            'pageKey' => 'maintenance',
            'seoData' => $seo,
        ]);
    }

}
