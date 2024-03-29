<?php

namespace Nabre\Quickadmin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Menu;
use Nabre\Quickadmin\Facades\Repositories\Menu\Page;


class GenerateMenu
{
    public function handle(Request $request, Closure $next): mixed
    {
        Menu::make('AdminBar', function ($menu) {
            Page::add($menu, 'quickadmin.admin.settings.index', null, true, true);
            $idUsers = Page::add($menu, 'quickadmin.admin.users.rdr', null, true, true);
            Page::add($menu, 'quickadmin.admin.users.list.index', $idUsers, true, true);
            Page::add($menu, 'quickadmin.admin.users.roles.index', $idUsers, true, true);
            Page::add($menu, 'quickadmin.admin.users.permissions.index', $idUsers, true, true);
            Page::add($menu, 'quickadmin.admin.users.impersonate.index', $idUsers, true, true);
        }, ['active_element'   => 'link']);

        Menu::make('BuilderBar', function ($menu) {
            Page::add($menu, 'quickadmin.builder.settings.index', null, true, true);
            $idBSet = Page::add($menu, 'quickadmin.builder.settings.rdr', null, true, true);
            Page::add($menu, 'quickadmin.builder.settings.list.index', $idBSet, true, true);
            Page::add($menu, 'quickadmin.builder.settings.type.index', $idBSet, true, true);
            Page::add($menu, 'quickadmin.builder.settings.refresh.index', $idBSet, true, true);
        }, ['active_element'   => 'link']);

        Menu::make('ManageBar', function ($menu) {
            Page::add($menu, 'quickadmin.manage.dashboard.index', null, true, true);
            Page::add($menu, 'quickadmin.manage.settings.index', null, true, true);
            Page::add($menu, 'quickadmin.manage.contacts.index', null, true, true);
        }, ['active_element'   => 'link']);

        Menu::make('UserBar', function ($menu) {
            Page::add($menu, 'quickadmin.user.dashboard.index', null, true, true);
            Page::add($menu, 'quickadmin.user.account.index', null, true, true);
            Page::add($menu, 'quickadmin.user.profile.index', null, true, true);
            Page::add($menu, 'quickadmin.user.settings.index', null, true, true);
        }, ['active_element'   => 'link']);

        Menu::make('ShopBar', function ($menu) {
            Page::add($menu, 'quickadmin.shop.products', null, true, true);
            Page::add($menu, 'quickadmin.shop.cart', null, true, true);
            Page::add($menu, 'quickadmin.shop.invoice', null, true, true);

        }, ['active_element'   => 'link']);

        Menu::make('QuickBar', function ($menu) {
            Page::add($menu, 'quickadmin.user.rdr', null, true);
            Page::add($menu, 'quickadmin.shop.rdr', null, true);
            Page::add($menu, 'quickadmin.manage.rdr', null, true);
            Page::add($menu, 'quickadmin.admin.rdr', null, true);
            Page::add($menu, 'quickadmin.builder.rdr', null, true);
            Page::add($menu, 'register', null, true);
            Page::add($menu, 'login', null, true);
            Page::add($menu, 'logout', null, true);
        }, ['active_element'   => 'link']);

        Menu::make('Breadcrumbs', function ($menu) {
            //  Page::add($menu, 'welcome');
            $idUser = Page::add($menu, 'quickadmin.user.rdr');
            Page::add($menu, 'quickadmin.user.dashboard.index', $idUser);
            Page::add($menu, 'quickadmin.user.account.index', $idUser);
            Page::add($menu, 'quickadmin.user.profile.index', $idUser);
            Page::add($menu, 'quickadmin.user.settings.index', $idUser);

            $idManage = Page::add($menu, 'quickadmin.manage.rdr');
            Page::add($menu, 'quickadmin.manage.dashboard.index', $idManage);
            Page::add($menu, 'quickadmin.manage.settings.index', $idManage);
            Page::add($menu, 'quickadmin.manage.contacts.index', $idManage);

            $idAdmin = Page::add($menu, 'quickadmin.admin.rdr');
            Page::add($menu, 'quickadmin.admin.settings.index', $idAdmin);
            $idUsers = Page::add($menu, 'quickadmin.admin.users.rdr', $idAdmin);
            Page::add($menu, 'quickadmin.admin.users.list.index', $idUsers);
            Page::add($menu, 'quickadmin.admin.users.roles.index', $idUsers);
            Page::add($menu, 'quickadmin.admin.users.permissions.index', $idUsers);
            Page::add($menu, 'quickadmin.admin.users.impersonate.index', $idUsers);

            $idBuilder = Page::add($menu, 'quickadmin.builder.rdr');
            Page::add($menu, 'quickadmin.builder.settings.index', $idBuilder);
            $idBSet = Page::add($menu, 'quickadmin.builder.settings.rdr', $idBuilder);
            Page::add($menu, 'quickadmin.builder.settings.list.index', $idBSet);
            Page::add($menu, 'quickadmin.builder.settings.type.index', $idBSet);
            Page::add($menu, 'quickadmin.builder.settings.refresh.index', $idBSet);

            $idShop = Page::add($menu, 'quickadmin.shop.rdr');
            Page::add($menu, 'quickadmin.shop.products', $idShop);
            Page::add($menu, 'quickadmin.shop.cart', $idShop);
            Page::add($menu, 'quickadmin.shop.invoice', $idShop);

            $idLogin = Page::add($menu, 'login');
            Page::add($menu, 'password.request', $idLogin);
            Page::add($menu, 'password.reset', $idLogin);
            Page::add($menu, 'register');
            Page::add($menu, 'logout');
        });

        return $next($request);
    }
}
