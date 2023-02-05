<?php

namespace Nabre\Quickadmin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Exception;
use Nabre\Repositories\Pages;

class UserSettingEnableMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {

        if (!userSettingsEnabled()) {
            abort(404);
        }

        return $next($request);
    }
}
