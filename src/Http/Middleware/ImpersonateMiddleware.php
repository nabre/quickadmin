<?php

namespace Nabre\Quickadmin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
//use Illuminate\View\Component;

class ImpersonateMiddleware
{
    /**
     * Handle an incoming request.
     */
    function handle(Request $request, Closure $next)
    {
        if (impersonateCheck()) {
            $id = $request->session()->get('impersonate');
            auth()->onceUsingID($id);
        }
        return $next($request);
    }
}
