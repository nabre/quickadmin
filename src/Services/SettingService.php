<?php
namespace Nabre\Quickadmin\Services;

use Nabre\Quickadmin\Models\Role;

class SettingService
{

    static function enabledCustomize(&$collect, array $roles, ?string $rolePage=null)
    {
        $roles = Role::whereIn('name', $roles)->orderBy('priority')->get();
        $min = auth()->user()->roles->pluck('priority')->min();

        $collect = $collect->filter(function ($i) use ($min, $rolePage,$roles) {
            return  !$i->user && (
                ($i->role >= $min && array_intersect((array)$i->role,$roles->pluck('priority')->toArray()))
                || ((($r = $rolePage) == 'admin' || !$r) ? data_get($i, 'user_setting') : false));
        })->values();
        return $collect;
    }
}
