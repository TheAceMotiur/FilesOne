<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Middleware\AdminCheck;
use App\Http\Middleware\UserCheck;
use App\Http\Middleware\GuestCheck;
use App\Http\Middleware\FilePrivacy;
use App\Http\Middleware\Minify;
use App\Http\Middleware\Base;
use App\Http\Middleware\SignatureCheck;
use \Symfony\Component\HttpKernel\Exception\HttpException;
use App\Exceptions\CustomNotFoundException;
use App\Exceptions\CustomNotAllowedException;
use App\Exceptions\CustomServerErrorException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        using: function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::prefix(LaravelLocalization::setLocale())
                ->group(function () {
                    Route::middleware([
                            'web',
                            'localeSessionRedirect',
                            'localizationRedirect',
                            'localeViewPath',
                            'auth.admin',
                        ])
                        ->prefix('admin')
                        ->group(base_path('routes/admin.php'));
                    });

            Route::prefix(LaravelLocalization::setLocale())
                ->group(function () {
                    Route::middleware([
                            'web',
                            'localeSessionRedirect',
                            'localizationRedirect',
                            'localeViewPath',
                            'auth.user',
                        ])
                        ->prefix('user')
                        ->group(base_path('routes/user.php'));
                    });

            Route::prefix(LaravelLocalization::setLocale())
                ->middleware([
                    'web',
                    'localeSessionRedirect',
                    'localizationRedirect',
                    'localeViewPath',
                ])
                ->group(base_path('routes/frontend.php'));

            if (env('INSTALLED') == '%not_installed%') {
                Route::prefix(LaravelLocalization::setLocale())
                    ->middleware([
                        'web',
                        'localeSessionRedirect',
                        'localizationRedirect',
                        'localeViewPath',
                    ])
                    ->group(base_path('routes/installer.php'));
            }

            if (file_exists(base_path('routes/update.php'))) {
                Route::middleware('web')
                    ->group(base_path('routes/update.php'));
            }

        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            /**** OTHER MIDDLEWARE ALIASES ****/
            'localize'              => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class,
            'localizationRedirect'  => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
            'localeSessionRedirect' => \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
            'localeCookieRedirect'  => \Mcamara\LaravelLocalization\Middleware\LocaleCookieRedirect::class,
            'localeViewPath'        => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class,
            'auth.admin'            => AdminCheck::class,
            'auth.user'             => UserCheck::class,
            'auth.guest'            => GuestCheck::class,
            'filePrivacy'           => FilePrivacy::class,
            'signatureCheck'        => SignatureCheck::class,
        ]);
        $middleware->prepend(Minify::class);
        $middleware->prepend(Base::class);
        $middleware->validateCsrfTokens(except: [
            '*/install',
            '*/install/*',
            '*/color-mode',
            '*/stripe/*',
            '*/razorpay/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (HttpException $exception) {
            if ($exception->getStatusCode() == 403) {
                return (new CustomNotAllowedException())->render();
            }
            if ($exception->getStatusCode() == 404) {
                return (new CustomNotFoundException())->render();
            }
            if ($exception->getStatusCode() == 500) {
                return (new CustomServerErrorException())->render();
            }
        });
    })->create();