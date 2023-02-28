<?php

namespace Nabre\Quickadmin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Exception;
use Nabre\Repositories\Pages;

class RegisterPageMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        if(!registerPageEnabled()){
            abort(404);
        }

        return $next($request);
    }
}
