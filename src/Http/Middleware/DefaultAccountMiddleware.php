<?php

namespace Nabre\Quickadmin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Exception;
use Nabre\Repositories\Pages;

class DefaultAccountMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {
       /* $redirect='/user/profile';
        if(get_data(auth()->user(),'email')==config('auth.adminaccountdefault.email')){
            return redirect($redirect);
        }*/
        return $next($request);
    }
}
