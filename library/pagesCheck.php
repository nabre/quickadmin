<?php

use Nabre\Quickadmin\Models\Setting;

function userAccountEnabled()
{
    return !userProfileEnabled();
}


function userProfileEnabled()
{
    return !is_null(optional(auth()->user())->contact);
}

function userSettingsEnabled(){
    $settings=Setting::doesnthave('user')->get()->filter(fn($i)=>data_get($i,'user_setting'));
    return $settings->count();
}
