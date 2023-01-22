<?php

namespace Nabre\Quickadmin\Providers;

use Illuminate\Support\ServiceProvider;
use Nabre\Quickadmin\Http\Middleware\GenerateMenu;
use Nabre\Quickadmin\Http\Middleware\SettingAutoSaveMiddleware;
use Nabre\Quickadmin\Repositories\LocalizationRepositorie;
use Nabre\Quickadmin\Setting\Facade as SettingFacade;
use Nabre\Quickadmin\Setting\Manager;
use Blade;
use Lavary\Menu\Menu as LavaryMenu;
use Nabre\Quickadmin\Repositories\Menu\Menu;

class AppServiceProvider extends ServiceProvider
{
    public $bindings = [
        \Illuminate\Routing\ResourceRegistrar::class => \Nabre\Quickadmin\Routing\ResourceRegistrar::class,
    ];

    function boot(\Illuminate\Routing\Router $router, \Illuminate\Contracts\Http\Kernel $kernel)
    {
        $router->pushMiddlewareToGroup('web', GenerateMenu::class);

        /**
         * Setting
         */
        $this->app->singleton('Setting', function () {
            return new SettingFacade();
        });

        $this->publishes([
            __DIR__ . '/../../config/setting.php' => config_path('setting.php'),
        ], 'setting');

        if (config('setting.auto_save')) {
            $kernel->pushMiddleware(SettingAutoSaveMiddleware::class);
        }

        Blade::directive('setting', function ($expression) {
            return "<?php echo setting($expression); ?>";
        });
    }

    function register()
    {
        /**
         * Providers
         */
        $this->app->register(MacroServiceProvider::class);
        $this->app->register(\Collective\Html\HtmlServiceProvider::class);

        /**
         * Setting
         */
        $this->app->singleton('setting.manager', function ($app) {
            return new Manager($app);
        });

        $this->app->singleton('setting', function ($app) {
            return $app['setting.manager']->driver();
        });

        /**
         * Bindings
         */
        $this->app->singleton(LocalizationRepositorie::class, function ($app) {
            return new LocalizationRepositorie;
        });

        $this->app->singleton(LavaryMenu::class, function ($app) {
            return new Menu;
        });

        /**
         * Connfig
         */
        $this->mergeConfigFrom(__DIR__ . '/../../config/setting.php', 'setting');
    }
}
