<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use App\Models\Settings;
use App\Models\Pages;
use App\Models\FooterSettings;
use App\Models\Payment;
use App\Models\EmailContents;
use App\Models\EmailSettings;
use App\Models\Language;
use App\Models\SettingsDownload;
use App\Models\Storages;
use App\Models\SettingsAffiliate;
use App\Models\FileReports;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\App;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class AppServiceProvider extends ServiceProvider
{
    use \Mcamara\LaravelLocalization\Traits\LoadsTranslatedCachedRoutes;

    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RouteServiceProvider::loadCachedRoutesUsing(
            fn() => $this->loadCachedRoutes()
        );
        Paginator::useBootstrapFive();

        if (
            env('INSTALLED') == '%installed%'
            && !request()->routeIs('install')
        ) {

            config(['session.driver' => 'database']);
            config(['cache.default' => 'database']);

            if (!Cache::has('setting')) {
                Cache::forever(
                    'setting',
                    Settings::all()
                );
            }

            if (!Cache::has('footerSettings')) {
                Cache::forever(
                    'footerSettings',
                    FooterSettings::all()
                );
            }

            if (!Cache::has('emailContent')) {
                Cache::forever(
                    'emailContent',
                    EmailContents::all()
                );
            }

            if (!Cache::has('pages')) {
                Cache::forever(
                    'pages',
                    Pages::all()
                );
            }

            if (!Cache::has('paymentSetting')) {
                Cache::forever(
                    'paymentSetting',
                    Payment::all()
                );
            }

            if (!Cache::has('emailSetting')) {
                Cache::forever(
                    'emailSetting',
                    EmailSettings::all()
                );
            }

            if (!Cache::has('langSetting')) {
                Cache::forever(
                    'langSetting',
                    Language::all()
                );
            }

            if (!Cache::has('storageSetting')) {
                Cache::forever(
                    'storageSetting',
                    Storages::all()
                );
            }

            if (!Cache::has('affiliateSetting')) {
                Cache::forever(
                    'affiliateSetting',
                    SettingsAffiliate::all()
                );
            }

            if (!Cache::has('fileReportsCount')) {
                Cache::forever(
                    'fileReportsCount',
                    FileReports::count()
                );
            }

            if (!Cache::has('languages')) {

                $langsArr = [];
                $langs = Storage::disk('lang')->directories();
                foreach ( $langs as $lang ) {
                    $filesArr = Storage::disk('lang')->files($lang);
                    if (
                        in_array("{$lang}/lang.json", $filesArr)
                        && in_array("{$lang}/auth.php", $filesArr)
                        && in_array("{$lang}/lang.php", $filesArr)
                        && in_array("{$lang}/pagination.php", $filesArr)
                        && in_array("{$lang}/passwords.php", $filesArr)
                        && in_array("{$lang}/validation.php", $filesArr)
                    ) {
                        $langDetails = Storage::disk('lang')
                            ->json("{$lang}/lang.json");
                        if (
                            isset($langDetails["langName"])
                            && isset($langDetails["langCode"])
                            && isset($langDetails["langDirection"])
                            && isset($langDetails["langFlag"])
                        )
                            $langsArr[] = [
                                'name' => $langDetails["langName"],
                                'code' => $langDetails["langCode"],
                                'direction' => strtoupper(
                                    $langDetails["langDirection"]
                                ),
                                'flag' => $langDetails["langFlag"],
                            ];
                    }
                }

                Cache::forever(
                    'languages',
                    $langsArr
                );
            }

            if (!Cache::has('downloadSettings')) {
                Cache::forever(
                    'downloadSettings',
                    SettingsDownload::all()
                );
            }

            App::setLocale(langSetting('base_language'));
            LaravelLocalization::setLocale(langSetting('base_language'));
        }
    }
}
