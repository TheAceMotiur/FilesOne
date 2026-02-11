<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class ThrottlerProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        RateLimiter::for('report', function (Request $request): Limit {
            return Limit::perMinute(10)->by($request->ip())
                ->response(function (Request $request): JsonResponse|RedirectResponse {
                
                $xhr = $request->ajax();
                $method = $request->method();
                
                if ($xhr) {
                    return response()->json([
                        'result' => false,
                        'data' => __('lang.throttler_error'), 
                    ]);
                } elseif ($method == 'POST') {
                    return back()
                        ->with('error', __('lang.throttler_error'));
                } else {
                    return abort(429);
                }
            });
        });

        RateLimiter::for('filePassword', function (Request $request): Limit {
            return Limit::perMinute(10)->by($request->ip())
                ->response(function (Request $request): JsonResponse|RedirectResponse {
                
                $xhr = $request->ajax();
                $method = $request->method();
                
                if ($xhr) {
                    return response()->json([
                        'result' => false,
                        'data' => __('lang.throttler_error'), 
                    ]);
                } elseif ($method == 'POST') {
                    return back()
                        ->with('error', __('lang.throttler_error'));
                } else {
                    return abort(429);
                }
            });
        });

        RateLimiter::for('restUpload', function (Request $request): Limit {
            return Limit::perMinute(10)->by($request->ip())
                ->response(function (Request $request): JsonResponse|RedirectResponse {
                return response()->json([
                    'result' => false,
                    'data' => __('lang.throttler_error'),
                ], 429);
            });
        });

        RateLimiter::for('restUploadUrl', function (Request $request): Limit {
            return Limit::perMinute(10)->by($request->ip())
                ->response(function (Request $request): JsonResponse|RedirectResponse {
                return response()->json([
                    'result' => false,
                    'data' => __('lang.throttler_error'),
                ], 429);
            });
        });
    }
}
