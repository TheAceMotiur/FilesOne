<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\SettingsDownload;
use Illuminate\Support\Facades\Auth;

class SettingsDownloadController extends Controller
{
    public function download(): View
    {
        return view('admin.settings.download.index', [
            'functions' => 'admin.settings.download.function',
            'sidebar' => 'download_settings',
            'pageName' => pageName([__('lang.settings'), __('lang.download')]),
        ]);
    }

    public function download_post(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'countdown' => 'required|numeric|min:0|max:600',
            'adblock-blocker' => 'required|in:0,1',
        ]);

        $inputs = $request->only([
            'countdown',
            'adblock-blocker',
        ]);

        $userId = Auth::id();
        $userIp = $request->ip();

        foreach ($inputs as $key => $value) {
            $dbKey = str_replace("-", "_", $key);
            SettingsDownload::where("name", $dbKey)
                ->update([
                    'updated_by_id' => $userId,
                    'updated_by_ip' => $userIp,
                    'value' => $value
                ]);
        }

        if (Cache::has('downloadSettings')) {
            Cache::forget('downloadSettings');
        }

        return back()
            ->with('success', __('lang.data_update'));
    }

    public function ads_post(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'top-area' => 'nullable',
            'middle-area' => 'nullable',
            'bottom-area' => 'nullable',
            'js-codes' => 'nullable',
        ]);

        $inputs = $request->only([
            'top-area',
            'middle-area',
            'bottom-area',
            'js-codes',
        ]);

        $userId = Auth::id();
        $userIp = $request->ip();

        foreach ($inputs as $key => $value) {
            $dbKey = str_replace("-", "_", $key);
            SettingsDownload::where("name", $dbKey)
                ->update([
                    'updated_by_id' => $userId,
                    'updated_by_ip' => $userIp,
                    'value' => $value
                ]);
        }

        if (Cache::has('downloadSettings')) {
            Cache::forget('downloadSettings');
        }

        return back()
            ->with('success', __('lang.data_update'));
    }
}
