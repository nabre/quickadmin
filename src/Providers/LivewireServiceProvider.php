<?php

namespace Nabre\Quickadmin\Providers;

//use Livewire\LivewireServiceProvider as ServiceProvider;
use Livewire\Livewire;
use Illuminate\Support\ServiceProvider;
use Nabre\Quickadmin\Http\Livewire\FormManage;
use Nabre\Quickadmin\Http\Livewire\ManageDatabaseCRUD;

class LivewireServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Livewire::component('manageDatabadeCRUD',ManageDatabaseCRUD::class);
    }
}
