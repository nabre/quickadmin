<?php

namespace Nabre\Quickadmin\Providers;

use Illuminate\Support\ServiceProvider;
use Nabre\Quickadmin\Repositories\LocalizationRepositorie;

class AppServiceProvider extends ServiceProvider{

    function boot(){

    }

    function register(){

        $this->app->singleton(LocalizationRepositorie::class, function ($app) {
            return new LocalizationRepositorie;
        });

    }
}
