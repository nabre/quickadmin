<?php

namespace Nabre\Quickadmin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GenerateMenu
{
    public function handle(Request $request, Closure $next): mixed
    {
        \Menu::make('MyNavBar', function ($menu) {
            $menu->add('Home',['route'=>'welcome']);
            $menu->add('About', 'about');
            $menu->add('Services', 'services');
            $menu->add('Contact', 'contact');
        });

        \Menu::make('QuickBar', function ($menu) {
            $menu->add('User', ['url'=>'user','title'=>'Utente']);
            $menu->add('Manage', 'manage');
            $menu->add('Admin', 'admin');
            $menu->add('Login', ['route'=>'login']);
            $menu->add('Logout', ['route'=>'logout']);
        });

        return $next($request);
    }
}
