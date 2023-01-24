<?php

namespace Nabre\Quickadmin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Nabre\Quickadmin\Facades\Repositories\Page;


class GenerateMenu
{
    public function handle(Request $request, Closure $next): mixed
    {
        \Menu::make('MyNavBar', function ($menu) {
            Page::add($menu, 'welcome');
        });

        \Menu::make('QuickBar', function ($menu) {
            Page::add($menu, 'quickadmin.admin.rdr',null,true);
            Page::add($menu, 'login',null,true);
            Page::add($menu, 'logout',null,true);
        });

        \Menu::make('Breadcrumbs', function ($menu) {
            Page::add($menu, 'login');
            Page::add($menu, 'welcome');
            Page::add($menu, 'logout');
        });

        return $next($request);
    }
}
