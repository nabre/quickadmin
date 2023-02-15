<?php

namespace Nabre\Quickadmin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
<<<<<<< HEAD
=======
use Menu;
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
use Nabre\Quickadmin\Facades\Repositories\Menu\Page;


class GenerateMenu
{
    public function handle(Request $request, Closure $next): mixed
    {
<<<<<<< HEAD
        \Menu::make('AdminBar', function ($menu) {
            Page::add($menu, 'quickadmin.admin.dashboard.index', null, true, true);
=======
        Menu::make('AdminBar', function ($menu) {
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
            Page::add($menu, 'quickadmin.admin.settings.index', null, true, true);
            $idUsers = Page::add($menu, 'quickadmin.admin.users.rdr', null, true, true);
            Page::add($menu, 'quickadmin.admin.users.list.index', $idUsers, true, true);
            Page::add($menu, 'quickadmin.admin.users.roles.index', $idUsers, true, true);
            Page::add($menu, 'quickadmin.admin.users.permissions.index', $idUsers, true, true);
            Page::add($menu, 'quickadmin.admin.users.impersonate.index', $idUsers, true, true);
        }, ['active_element'   => 'link']);
<<<<<<< HEAD

        \Menu::make('BuilderBar', function ($menu) {
            $idBSet = Page::add($menu, 'quickadmin.builder.settings.rdr', null, true, true);
            Page::add($menu, 'quickadmin.builder.settings.list.index', $idBSet, true, true);
            Page::add($menu, 'quickadmin.builder.settings.type.index', $idBSet, true, true);
        }, ['active_element'   => 'link']);
=======
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870

        Menu::make('BuilderBar', function ($menu) {
            Page::add($menu, 'quickadmin.builder.settings.index', null, true, true);
            $idBSet = Page::add($menu, 'quickadmin.builder.settings.rdr', null, true, true);
            Page::add($menu, 'quickadmin.builder.settings.list.index', $idBSet, true, true);
            Page::add($menu, 'quickadmin.builder.settings.type.index', $idBSet, true, true);
        }, ['active_element'   => 'link']);

        Menu::make('ManageBar', function ($menu) {
            Page::add($menu, 'quickadmin.manage.dashboard.index', null, true, true);
<<<<<<< HEAD
=======
            Page::add($menu, 'quickadmin.manage.settings.index', null, true, true);
            Page::add($menu, 'quickadmin.manage.contacts.index', null, true, true);
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
        }, ['active_element'   => 'link']);

        Menu::make('UserBar', function ($menu) {
            Page::add($menu, 'quickadmin.user.dashboard.index', null, true, true);
            Page::add($menu, 'quickadmin.user.account.index', null, true, true);
            Page::add($menu, 'quickadmin.user.profile.index', null, true, true);
            Page::add($menu, 'quickadmin.user.settings.index', null, true, true);
        }, ['active_element'   => 'link']);

        Menu::make('QuickBar', function ($menu) {
            Page::add($menu, 'quickadmin.user.rdr', null, true);
            Page::add($menu, 'quickadmin.manage.rdr', null, true);
            Page::add($menu, 'quickadmin.admin.rdr', null, true);
            Page::add($menu, 'quickadmin.builder.rdr', null, true);
<<<<<<< HEAD
=======
            Page::add($menu, 'register', null, true);
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
            Page::add($menu, 'login', null, true);
            Page::add($menu, 'logout', null, true);
        }, ['active_element'   => 'link']);

<<<<<<< HEAD
        \Menu::make('Breadcrumbs', function ($menu) {
=======
        Menu::make('Breadcrumbs', function ($menu) {
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
            //  Page::add($menu, 'welcome');
            $idUser = Page::add($menu, 'quickadmin.user.rdr');
            Page::add($menu, 'quickadmin.user.dashboard.index', $idUser);
            Page::add($menu, 'quickadmin.user.account.index', $idUser);
            Page::add($menu, 'quickadmin.user.profile.index', $idUser);
<<<<<<< HEAD
            Page::add($menu, 'quickadmin.user.settings.index',$idUser);

            $idManage = Page::add($menu, 'quickadmin.manage.rdr');
            Page::add($menu, 'quickadmin.manage.dashboard.index', $idManage);

            $idAdmin = Page::add($menu, 'quickadmin.admin.rdr');
            Page::add($menu, 'quickadmin.admin.dashboard.index', $idAdmin);
            Page::add($menu, 'quickadmin.admin.settings.index', $idAdmin);

            $idBuilder = Page::add($menu, 'quickadmin.builder.rdr');
=======
            Page::add($menu, 'quickadmin.user.settings.index', $idUser);

            $idManage = Page::add($menu, 'quickadmin.manage.rdr');
            Page::add($menu, 'quickadmin.manage.dashboard.index', $idManage);
            Page::add($menu, 'quickadmin.manage.settings.index', $idManage);
            Page::add($menu, 'quickadmin.manage.contacts.index', $idManage);

            $idAdmin = Page::add($menu, 'quickadmin.admin.rdr');
            Page::add($menu, 'quickadmin.admin.settings.index', $idAdmin);

            $idBuilder = Page::add($menu, 'quickadmin.builder.rdr');
            Page::add($menu, 'quickadmin.builder.settings.index', $idBuilder);
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
            $idBSet = Page::add($menu, 'quickadmin.builder.settings.rdr', $idBuilder);
            Page::add($menu, 'quickadmin.builder.settings.list.index', $idBSet);
            Page::add($menu, 'quickadmin.builder.settings.type.index', $idBSet);

            $idUsers = Page::add($menu, 'quickadmin.admin.users.rdr', $idAdmin);
            Page::add($menu, 'quickadmin.admin.users.list.index', $idUsers);
            Page::add($menu, 'quickadmin.admin.users.roles.index', $idUsers);
            Page::add($menu, 'quickadmin.admin.users.permissions.index', $idUsers);
            Page::add($menu, 'quickadmin.admin.users.impersonate.index', $idUsers);
<<<<<<< HEAD
=======

>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
            $idLogin = Page::add($menu, 'login');
            Page::add($menu, 'password.request', $idLogin);
            Page::add($menu, 'password.reset', $idLogin);
            Page::add($menu, 'register');
            Page::add($menu, 'logout');
        });

        return $next($request);
    }
}
