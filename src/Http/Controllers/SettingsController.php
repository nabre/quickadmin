<?php

namespace Nabre\Quickadmin\Http\Controllers;

use Nabre\Quickadmin\Http\Controllers\Controller;
use Nabre\Quickadmin\Forms\SettingsBackForm as Form;
use App\Models\Role;

class SettingsController extends Controller
{
    function index()
    {
        $roles = collect(request()->route()->middleware())->reject(function ($m) {
            return strpos($m, 'role:') === false;
        })->map(function ($m) {
            list(, $name) = explode(":", $m);
            return $name;
        })->values()->toArray();
        $role = optional(Role::whereIn('name', $roles)->orderByDesc('priority')->get()->first())->name;


        $CONTENT = Form::public();
        return view('nabre-quickadmin::quick.' . $role, compact('CONTENT'));
    }
}
