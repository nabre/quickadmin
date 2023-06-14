<?php

namespace Nabre\Quickadmin\Providers;

//use Livewire\LivewireServiceProvider as ServiceProvider;
use Livewire\Livewire;
use Illuminate\Support\ServiceProvider;
use Nabre\Quickadmin\Http\Livewire\ManageData;
use Nabre\Quickadmin\Http\Livewire\ManageDatabaseCRUD;
use Nabre\Quickadmin\Http\Livewire\Payment;
use Nabre\Quickadmin\Http\Livewire\RefreshPanel;

class LivewireServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Livewire::component('manageData',ManageData::class);
        Livewire::component('manageDatabadeCRUD',ManageDatabaseCRUD::class);
        Livewire::component('refreshPanel',RefreshPanel::class);
        Livewire::component('payment',Payment::class);
    }
}
