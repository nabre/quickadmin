<?php

namespace Nabre\Quickadmin\Console\Commands\Update;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class UserCommand extends Command
{
    protected $signature = 'update:user';
    protected $description = 'Update user fields';

    public function handle()
    {
        User::doesnthave('contact')->get()->each(function($item){
            $item->recursiveSave([]);
        });
    }
}
