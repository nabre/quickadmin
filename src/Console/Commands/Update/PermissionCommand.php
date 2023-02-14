<?php

namespace Nabre\Quickadmin\Console\Commands\Update;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Nabre\Quickadmin\Facades\Routing\RouteHierarchy;

class PermissionCommand extends Command
{
    protected $signature = 'update:permission';
    protected $description = 'Update role & permission from route in database';

    public function handle()
    {
        Artisan::call('optimize');

        Role::where('route_used',true)->update(['route_used'=>false]);
        Permission::where('route_used',true)->update(['route_used'=>false]);

        RouteHierarchy::middlewareList()->each(function ($i) {
            $type = data_get($i, 'type');
            switch ($type) {
                case "role":
                    $model = new Role;
                    break;
                case "permission":
                    $model = new Permission;
                    break;
            }
            $name=data_get($i,'name');

            $result=$model->where('name',$name)->get();
            if($result->count()){
                $result=$result->first();
            }else{
                $result=$model->make();
            }
            $route_used=true;
            $result->recursiveSave(compact('name','route_used'));
        });
    }
}
