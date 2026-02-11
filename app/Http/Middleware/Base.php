<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Base
{
    public function handle(Request $request, Closure $next): Response
    {
        if (env('INSTALLED') == '%not_installed%') {
            $segments = $request->segments();
            if (!in_array('install', $segments)) { 
                    return redirect()->route('installer');
            }
            return $next($request);
        }

        return $next($request);
    }

}
