<?php

<<<<<<< HEAD
use Nabre\Quickadmin\Models\Setting;
=======
use Nabre\Quickadmin\Models\Role;
use Nabre\Quickadmin\Models\Setting;
use Nabre\Quickadmin\Services\SettingService;
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870

function userAccountEnabled()
{
    return !userProfileEnabled();
}

<<<<<<< HEAD

=======
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
function userProfileEnabled()
{
    return !is_null(optional(auth()->user())->contact);
}

<<<<<<< HEAD
function userSettingsEnabled(){
    $settings=Setting::doesnthave('user')->get()->filter(fn($i)=>data_get($i,'user_setting'));
    return $settings->count();
=======
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
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
}
