<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\SettingsFooter;
use Illuminate\Support\Facades\Auth;

class SettingsFooterController extends Controller
{
    public function footer(): View
    {
        return view('admin.settings.footer.index', [
            'functions' => 'admin.settings.footer.function',
            'sidebar' => 'footer_settings',
            'pageName' => pageName([__('lang.settings'), __('lang.footer')]),
        ]);
    }

    public function footer_post(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'about' => 'nullable|string|max:1000',
            'link-1-name' => 'nullable|string|max:255',
            'link-1-url' => 'nullable|url:http,https|max:1000',
            'link-2-name' => 'nullable|string|max:255',
            'link-2-url' => 'nullable|url:http,https|max:1000',
            'link-3-name' => 'nullable|string|max:255',
            'link-3-url' => 'nullable|url:http,https|max:1000',
            'link-4-name' => 'nullable|string|max:255',
            'link-4-url' => 'nullable|url:http,https|max:1000',
            'link-5-name' => 'nullable|string|max:255',
            'link-5-url' => 'nullable|url:http,https|max:1000',
            'link-6-name' => 'nullable|string|max:255',
            'link-6-url' => 'nullable|url:http,https|max:1000',
            'link-7-name' => 'nullable|string|max:255',
            'link-7-url' => 'nullable|url:http,https|max:1000',
            'link-8-name' => 'nullable|string|max:255',
            'link-8-url' => 'nullable|url:http,https|max:1000',
            'email' => 'nullable|email|max:255',
            'location' => 'nullable|string|max:255',
            'copyright' => 'nullable|string|max:255',
        ]);

        $inputs = $request->only([
            'about',
            'link-1-name',
            'link-1-url',
            'link-2-name',
            'link-2-url',
            'link-3-name',
            'link-3-url',
            'link-4-name',
            'link-4-url',
            'link-5-name',
            'link-5-url',
            'link-6-name',
            'link-6-url',
            'link-7-name',
            'link-7-url',
            'link-8-name',
            'link-8-url',
            'email',
            'location',
            'copyright',
        ]);

        $userId = Auth::id();
        $userIp = $request->ip();

        foreach ( $inputs as $key => $value ) {
            $dbKey = str_replace("-", "_", $key);
            SettingsFooter::where("name", $dbKey)
                ->update([
                    'updated_by_id' => $userId,
                    'updated_by_ip' => $userIp,
                    'value' => $value
                ]);
        }

        if (Cache::has('footerSettings')) {
            Cache::forget('footerSettings');
        }

        return back()
            ->with('success', __('lang.data_update'));
    }

}
