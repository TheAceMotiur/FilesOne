<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestCheck
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            return abort(403);
        }
        return $next($request);
    }
}
