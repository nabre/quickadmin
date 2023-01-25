<?php

namespace Nabre\Quickadmin\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Nabre\Quickadmin\Models\Role;
use Nabre\Quickadmin\Models\User;

class UserSeeder extends Seeder
{
    function run()
    {

        $minPri = Role::whereNotNull("priority")->get()->min("priority");

        $exists = (bool)User::whereHas('roles', function ($q) use ($minPri) {
            $q->where("priority", $minPri);
        })->get()->count();

        if (!$exists) {

            $data=[
                'name'=>'Account admin',
                'email'=>'admin@account.test',
                'password'=>'password',
                "email_verified_at"=> Carbon::now(),
                'roles'=> [
                    data_get(Role::where('priority', $minPri)->first(), 'id'),
                ]
            ];

            if (!is_null(data_get($data, 'email')) && !is_null(data_get($data, 'password'))) {
                $node = User::create()->recursiveSave($data);
            }
        }
    }
}
