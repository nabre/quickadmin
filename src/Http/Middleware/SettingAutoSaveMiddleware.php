<?php

namespace Nabre\Quickadmin\Http\Middleware;

use Closure;

class SettingAutoSaveMiddleware
{
    var $setting;
    /**
     * Create a new save settings middleware.
     */
    public function __construct()
    {
        $this->setting = app('setting');
    }

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
        $response = $next($request);

        $this->setting->save();

        return $response;
    }
}
