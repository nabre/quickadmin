<?php

namespace Nabre\Quickadmin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HttpRedirectSecure
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->secure() /*&& App::environment('production') */) {
            return redirect()->secure($request->getRequestUri());
        }
        return $next($request);
    }
}
