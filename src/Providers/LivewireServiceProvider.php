<?php

namespace Nabre\Quickadmin\Providers;

//use Livewire\LivewireServiceProvider as ServiceProvider;
use Illuminate\Support\ServiceProvider;
use Livewire;
use Nabre\Quickadmin\Http\Livewire\FormManage;

class LivewireServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Livewire::component('form',FormManage::class);
    }
}
