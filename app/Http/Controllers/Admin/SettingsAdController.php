<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Ad;
use Illuminate\Support\Facades\Auth;

class SettingsAdController extends Controller
{
    public function ad(): View
    {
        return view('admin.settings.ad.index', [
            'functions' => 'admin.settings.ad.function',
            'sidebar' => 'ad_settings',
            'pageName' => pageName([__('lang.settings'), __('lang.ad')]),
        ]);
    }

    public function ad_post(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'top-area' => 'nullable|max:10000',
            'middle-area' => 'nullable|max:10000',
            'bottom-area' => 'nullable|max:10000',
        ]);

        $inputs = $request->only([
            'top-area',
            'middle-area',
            'bottom-area',
        ]);

        $userId = Auth::id();
        $userIp = $request->ip();

        foreach ($inputs as $key => $value) {
            $dbKey = str_replace("-", "_", $key);
            Ad::where("name", $dbKey)
                ->update([
                    'updated_by_id' => $userId,
                    'updated_by_ip' => $userIp,
                    'value' => $value
                ]);
        }

        if (Cache::has('ads')) {
            Cache::forget('ads');
        }

        return back()
            ->with('success', __('lang.data_update'));
    }

}
