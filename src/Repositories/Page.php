<?php

namespace Nabre\Quickadmin\Repositories;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

class Page
{
    function middleware(array $middleware)
    {
        $user = Auth::user();
        foreach ($middleware as $mid) {
            @list($auth, $name) = explode(":", $mid);
            switch ($auth) {
                case "auth":
                    if (!Auth::check()) {
                        return false;
                    }
                    break;
                case "verified":
                    if (is_null($user) || !$user->hasVerifiedEmail()) {
                        return false;
                    }
                    break;
                case "role":
                    if (is_null($user) || !$user->hasAnyRole($name)) {
                        return false;
                    }
                    break;
                case "permission":
                    if (is_null($user) || !$user->hasPermissionTo($name)) {
                        return false;
                    }
                    break;
                case "guest":
                    if (Auth::check()) {
                        return false;
                    }
                    break;
                case "web":
                    break;
                case "registration":

                    break;
                    /*    case "usersettingcompile":
                    if (!Pages::userSettingCompile()) {
                        return false;
                    }
                    break;*/
                case "abort":
                    if (!in_array($name, [401, 403, 200])) {
                        return false;
                    }
                    break;
            }
        }

        return true;
    }

    public function route($name)
    {
        $route = Route::getRoutes()->getByName($name);

        if (is_null($route)) {
            return false;
        }
        return $this->middleware($route->middleware());
    }
}
