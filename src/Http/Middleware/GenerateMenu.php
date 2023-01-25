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
            $idUsers=Page::add($menu, 'quickadmin.admin.users.rdr', null, true, true);
            Page::add($menu, 'quickadmin.admin.users.list.index', $idUsers, true, true);
            Page::add($menu, 'quickadmin.admin.users.roles.index', $idUsers, true, true);
            Page::add($menu, 'quickadmin.admin.users.permissions.index', $idUsers, true, true);
        },['active_element'   => 'link']);

        \Menu::make('ManageBar', function ($menu) {
            Page::add($menu, 'quickadmin.manage.dashboard.index', null, true, true);
        },['active_element'   => 'link']);

        \Menu::make('UserBar', function ($menu) {
            Page::add($menu, 'quickadmin.user.dashboard.index', null, true, true);
        },['active_element'   => 'link']);

        \Menu::make('QuickBar', function ($menu) {
            Page::add($menu, 'quickadmin.user.rdr', null, true);
            Page::add($menu, 'quickadmin.manage.rdr', null, true);
            Page::add($menu, 'quickadmin.admin.rdr', null, true);
            Page::add($menu, 'login', null, true);
            Page::add($menu, 'logout', null, true);
        },['active_element'   => 'link']);

        \Menu::make('Breadcrumbs', function ($menu) {
           // Page::add($menu, 'welcome');
            $idUser = Page::add($menu, 'quickadmin.user.rdr');
            Page::add($menu, 'quickadmin.user.dashboard.index', $idUser);
            $idManage = Page::add($menu, 'quickadmin.manage.rdr');
            Page::add($menu, 'quickadmin.manage.dashboard.index', $idManage);
            $idAdmin = Page::add($menu, 'quickadmin.admin.rdr');
            Page::add($menu, 'quickadmin.admin.dashboard.index', $idAdmin);
            $idUsers=Page::add($menu, 'quickadmin.admin.users.rdr', $idAdmin);
            Page::add($menu, 'quickadmin.admin.users.list.index', $idUsers);
            Page::add($menu, 'quickadmin.admin.users.roles.index', $idUsers);
            Page::add($menu, 'quickadmin.admin.users.permissions.index', $idUsers);
            $idLogin=Page::add($menu, 'login');
            Page::add($menu, 'password.request', $idLogin);
            Page::add($menu, 'logout');
        });

        return $next($request);
    }
}
