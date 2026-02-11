<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Language;
use Illuminate\Support\Facades\Auth;

class LanguageController extends Controller
{
    public function language(): View
    {
        $languages = $this->get_languages();

        return view('admin.language.index', [
            'functions' => 'admin.language.function',
            'sidebar' => 'language',
            'pageName' => pageName([__('lang.language')]),
            'languages' => $languages,
        ]);
    }

    public function language_post(
        Request $request
    ): mixed {
        $request->validate([
            'base-language' => 'required|max:10',
            'language-switcher' => 'required|in:1,0',
        ]);

        $inputs = $request->only([
            'base-language',
            'language-switcher',
        ]);

        $userId = Auth::id();
        $userIp = $request->ip();

        foreach ( $inputs as $key => $value ) {
            $dbKey = str_replace("-", "_", $key);
            Language::where("name", $dbKey)
                ->update([
                    'updated_by_id' => $userId,
                    'updated_by_ip' => $userIp,
                    'value' => $value
                ]);
        }

        if (Cache::has('languages')) {
            Cache::forget('languages');
        }
        if (Cache::has('langSetting')) {
            Cache::forget('langSetting');
        }

        return back()
            ->with('success', __('lang.data_update'));
    }

    public function language_table_post(): JsonResponse
    {
        $languages = $this->get_languages();

        return response()->json([
            'result' => true,
            'data' => $languages
        ]);
    }

    public function get_languages(): array
    {
        $langsArr = [];
        $langs = Storage::disk('lang')->directories();
        foreach ($langs as $lang) {
            $filesArr = Storage::disk('lang')->files($lang);
            if (
                in_array("{$lang}/lang.json", $filesArr) 
                && in_array("{$lang}/auth.php", $filesArr) 
                && in_array("{$lang}/lang.php", $filesArr) 
                && in_array("{$lang}/pagination.php", $filesArr) 
                && in_array("{$lang}/passwords.php", $filesArr) 
                && in_array("{$lang}/validation.php", $filesArr)
            ) {
                $langDetails = Storage::disk('lang')->json("{$lang}/lang.json");
                if (
                    isset($langDetails["langName"])
                    && isset($langDetails["langCode"]) 
                    && isset($langDetails["langDirection"]) 
                    && isset($langDetails["langFlag"])
                )
                $langsArr[] = [
                    'name' => $langDetails["langName"],
                    'code' => $langDetails["langCode"],
                    'direction' => strtoupper($langDetails["langDirection"]),
                    'flag' => $langDetails["langFlag"],
                ];
            }
        }

        return $langsArr;
    }

}
