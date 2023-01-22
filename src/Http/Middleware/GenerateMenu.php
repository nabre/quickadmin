<?php

namespace Nabre\Quickadmin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GenerateMenu
{
    public function handle(Request $request, Closure $next): mixed
    {
        return $next($request);
    }
}
