<?php

namespace Nabre\Quickadmin\Http\Middleware\PagesEnable;

use Closure;

class ContactMiddleware
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
        if(!userProfileModelExist()){
            abort(404);
        }

        return $next($request);
    }
}
