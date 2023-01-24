<?php

namespace Nabre\Quickadmin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Nabre\Quickadmin\Facades\Repositories\Page;


class GenerateMenu
{
    public function handle(Request $request, Closure $next): mixed
    {

        \Menu::make('AdminBar', function ($menu) {
            Page::add($menu, 'quickadmin.admin.dashboard.index', null, true, true);
        });

        \Menu::make('ManageBar', function ($menu) {
            Page::add($menu, 'quickadmin.manage.dashboard.index', null, true, true);
        });

        \Menu::make('UserBar', function ($menu) {
            Page::add($menu, 'quickadmin.user.dashboard.index', null, true, true);
        });

        \Menu::make('QuickBar', function ($menu) {
            Page::add($menu, 'welcome',null,true);
            Page::add($menu, 'quickadmin.user.rdr', null, true);
            Page::add($menu, 'quickadmin.manage.rdr', null, true);
            Page::add($menu, 'quickadmin.admin.rdr', null, true);
            Page::add($menu, 'login', null, true);
            Page::add($menu, 'logout', null, true);
        });

        \Menu::make('Breadcrumbs', function ($menu) {
         //   Page::add($menu, 'welcome');
            $idUser = Page::add($menu, 'quickadmin.user.rdr');
            Page::add($menu, 'quickadmin.user.dashboard.index', $idUser);
            $idManage = Page::add($menu, 'quickadmin.manage.rdr');
            Page::add($menu, 'quickadmin.manage.dashboard.index', $idManage);
            $idAdmin = Page::add($menu, 'quickadmin.admin.rdr');
            Page::add($menu, 'quickadmin.admin.dashboard.index', $idAdmin);
            $idAuth = Page::add($menu, 'quickadmin.authentication');
            Page::add($menu, 'login', $idAuth);
            Page::add($menu, 'logout', $idAuth);
        });

        return $next($request);
    }
}
