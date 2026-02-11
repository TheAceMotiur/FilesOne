<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SignatureCheck
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->hasValidSignature()) {
            return $next($request);  
        }
        
        abort(403);
    }
}
