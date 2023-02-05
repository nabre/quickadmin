<?php

namespace Nabre\Quickadmin\Http\Middleware;

use Closure;

class ProfileEnableMiddleware
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
        if(!userProfileEnabled()){
            abort(404);
        }

        return $next($request);
    }
}
