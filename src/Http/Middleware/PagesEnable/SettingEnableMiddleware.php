<?php

namespace Nabre\Quickadmin\Http\Middleware\PagesEnable;

use Closure;
use Illuminate\Http\Request;
use Exception;
use Nabre\Repositories\Pages;

class SettingEnableMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {

        if (!settingsPageEnabled()) {
            abort(404);
        }

        return $next($request);
    }
}
