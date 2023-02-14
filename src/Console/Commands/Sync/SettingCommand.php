<?php

namespace Nabre\Quickadmin\Console\Commands\Sync;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Nabre\Quickadmin\Models\Setting as Model;

class SettingCommand extends Command
{
    protected $signature = 'sync:setting';
    protected $description = 'Update editable setting';

    public function handle()
    {
        Artisan::call('optimize');

        $configKey = config('setting.override');
        collect($configKey)->each(function ($key) {
            $data = [config('setting.database.key') => $key];
            $set = Model::where(config('setting.database.key'), $key)->whereDoesntHave('user')->firstOrCreate();
            $set->recursiveSave($data);
        });
        Model::whereNotIn(config('setting.database.key'), $configKey)->delete();
    }
}
