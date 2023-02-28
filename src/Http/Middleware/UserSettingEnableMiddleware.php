<?php

namespace Nabre\Quickadmin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Exception;
use Nabre\Repositories\Pages;

<<<<<<<< HEAD:src/Http/Middleware/UserSettingEnableMiddleware.php
class UserSettingEnableMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {

        if (!userSettingsEnabled()) {
========
class RegisterPageMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        if(!registerPageEnabled()){
>>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870:src/Http/Middleware/RegisterPageMiddleware.php
            abort(404);
        }

        return $next($request);
    }
}
