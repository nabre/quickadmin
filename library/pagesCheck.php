<?php

use Nabre\Quickadmin\Models\Role;
use Nabre\Quickadmin\Models\Setting;
use Nabre\Quickadmin\Services\SettingService;

function userAccountEnabled()
{
    return !userProfileEnabled();
}

function userProfileEnabled()
{
    return !is_null(optional(auth()->user())->contact);
}

function settingsPageEnabled()
{
    $roles = collect(request()->route()->middleware())->reject(function ($m) {
        return strpos($m, 'role:') === false;
    })->map(function ($m) {
        list(, $name) = explode(":", $m);
        return $name;
    })->values()->toArray();

    $rolePage = data_get(Role::whereIn('name', $roles)->first(), 'name');

    $collect = Setting::doesnthave('user')->get();
    SettingService::enabledCustomize($collect, $roles, $rolePage);

    return (bool) $collect->count();
}

function registerPageEnabled()
{
    return config('setting.define.register-form');
}
