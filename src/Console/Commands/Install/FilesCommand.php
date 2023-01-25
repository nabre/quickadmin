<?php

namespace Nabre\Quickadmin\Console\Commands\Install;

use Illuminate\Console\Command;

class FilesCommand extends Command
{
    protected $signature = 'install:files';
    protected $description = 'Install files in "app" forder';

    public function handle()
    {
       /* $middlewares=collect([]);
        (new RouteHierarchy)->routeGetList()->pluck('name')->each(function($name)use(&$middlewares){
            $middlewares=$middlewares->merge(\Route::getRoutes()->getByName($name)->middleware())->unique()->sort()->values();
        });
        $roles=$middlewares->like(null,'role:%')->values();
        $permissions=$middlewares->like(null,'permission:%')->values();
        collect(compact('roles','permissions'))->each(function($i,$type){

        });*/
    }
}
