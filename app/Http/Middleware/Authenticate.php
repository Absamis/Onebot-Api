<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as MiddlewareAuthenticate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticate extends MiddlewareAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    protected function redirectTo(Request $request)
    {
        if (!$request->expectsJson()) {
            abort(401, "Unauthorized");
        }
        if (!$request->user()->isActive)
            abort(403, "Action forbidden on this account. Kindly contact support");
    }
}
