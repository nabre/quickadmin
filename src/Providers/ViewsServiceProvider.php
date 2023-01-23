<?php

namespace Nabre\Quickadmin\Providers;

use Collective\Html\HtmlFacade as Html;
use Collective\Html\FormFacade as Form;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ViewsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        /**
         * Quickadmin views
         */
        $dir_views_package = __DIR__ . '/../../resources/views';
        $namespace='nabre-quickadmin';
        $dir_views_resources =resource_path('views/vendor/'.$namespace);

      //  $this->loadViewsFrom($dir_views_resources,$namespace );
        $this->loadViewsFrom($dir_views_package,$namespace );
        $this->publishes([
            $dir_views_package => $dir_views_resources,
        ]);

    }
}
