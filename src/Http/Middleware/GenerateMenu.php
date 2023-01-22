<?php

namespace Nabre\Quickadmin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GenerateMenu
{
    public function handle(Request $request, Closure $next): mixed
    {
        \Menu::make('MyNavBar', function ($menu) {
            $menu->add('Home');
            $menu->add('About', 'about');
            $menu->add('Services', 'services');
            $menu->add('Contact', 'contact');
        });

        \Menu::make('QuickBar', function ($menu) {
            $menu->add('User', 'user');
            $menu->add('Manage', 'manage');
            $menu->add('Admin', 'admin');

        });

        return $next($request);
    }
}
