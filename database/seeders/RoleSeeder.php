<?php

namespace Nabre\Quickadmin\Database\Seeders;

use Illuminate\Database\Seeder;
use Nabre\Quickadmin\Models\Role as Model;

class RoleSeeder extends Seeder
{
    function run()
    {
        collect([
            ['guard_name'=>'web',"name"=>"builder","priority"=>0,"slug"=>['it'=>"Costruttore"]],
            ['guard_name'=>'web',"name"=>"admin","priority"=>1,"slug"=>['it'=>"Amministratore"]],
            ['guard_name'=>'web',"name"=>"manage","priority"=>2,"slug"=>['it'=>"Gestione"]],
        ])->each(function($data){
            $name=data_get($data,'name');
            Model::firstOrCreate(compact('name'),$data);
        });
    }
}
