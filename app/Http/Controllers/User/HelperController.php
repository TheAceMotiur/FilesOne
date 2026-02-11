<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

    public function collapse_sidebar(
        Request $request
    ): void {
        if ($request->collapsed == 1 || $request->collapsed == 0) {
            $request->session()->put(
                'sidebar-collapsed',
                $request->collapsed
            );
        }
    }

}
