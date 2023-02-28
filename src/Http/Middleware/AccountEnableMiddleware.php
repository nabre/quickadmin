<?php

namespace Nabre\Quickadmin\Http\Middleware;

use Closure;

class AccountEnableMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!userAccountEnabled()){
            abort(404);
        }

        return $next($request);
    }
}
