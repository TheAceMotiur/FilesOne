<?php

namespace App\Providers;

use Whitecube\LaravelCookieConsent\CookiesServiceProvider as ServiceProvider;
use Whitecube\LaravelCookieConsent\Facades\Cookies;

class CookiesServiceProvider extends ServiceProvider
{
    /**
     * Define the cookies users should be aware of.
     */
    protected function registerCookies(): void
    {
        // Register Laravel's base cookies under the "required" cookies section:
        Cookies::essentials()
            ->session()
            ->csrf();

        Cookies::essentials()
            ->name('locale')
            ->duration(1440);

        Cookies::essentials()
            ->name('color')
            ->duration(1440);

        // Register all Analytics cookies
        Cookies::analytics()
            ->name('analytics')
            ->google(setting('analytics_measurement'));

        // Register custom cookies under the pre-existing "optional" category:
        Cookies::optional()
            ->name('preferences')
            ->description('This cookie helps us remember user preferences')
            ->duration(1440);
    }
}
