<?php

namespace Nabre\Quickadmin\Providers;

use Lavary\Menu\Menu as LavaryMenu;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Nabre\Quickadmin\Setting\Manager;
use Illuminate\Support\ServiceProvider;
use Nabre\Quickadmin\Repositories\Menu;
use Nabre\Quickadmin\View\Components\AppLayout;
use Nabre\Quickadmin\View\Components\GuestLayout;
use Nabre\Quickadmin\Http\Middleware\GenerateMenu;
use Nabre\Quickadmin\Setting\Facade as SettingFacade;
use App\Http\Middleware\GenerateMenu as AppGenerateMenu;
use Nabre\Quickadmin\Repositories\LocalizationRepositorie;
use Nabre\Quickadmin\Http\Middleware\SettingAutoSaveMiddleware;

class AppServiceProvider extends ServiceProvider
{
    public $bindings = [
        \Illuminate\Routing\ResourceRegistrar::class => \Nabre\Quickadmin\Routing\ResourceRegistrar::class,
        LavaryMenu::class => Menu::class,
    ];

    function register()
    {
        /**
         * Providers
         */
        $this->app->register(GlobalFunctionsServiceProvider::class);
        $this->app->register(AuthServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(MacroServiceProvider::class);
        $this->app->register(ViewsServiceProvider::class);
        $this->app->register(LivewireServiceProvider::class);
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

        /**
         * Connfig
         */
        $this->mergeConfigFrom(__DIR__ . '/../../config/setting.php', 'setting');
    }

    function boot(\Illuminate\Routing\Router $router, \Illuminate\Contracts\Http\Kernel $kernel)
    {
        $router->pushMiddlewareToGroup('web', GenerateMenu::class);
        $router->aliasMiddleware('role', \Maklad\Permission\Middlewares\RoleMiddleware::class);
        $router->aliasMiddleware('permission', \Maklad\Permission\Middlewares\PermissionMiddleware::class);
        /**
         *  Config
         */
        $this->publishes([
            __DIR__ . '/../../config/routeicons.php' => config_path('routeicons.php'),
        ], 'nabre-quickadmin');
        $this->mergeConfigFrom(__DIR__ . '/../../config/routeicons.php', 'routeicons');

        $this->loadViewComponentsAs('',[
            AppLayout::class,
            GuestLayout::class,
        ]);
        /**
         * Bindings
         */
        $this->app->singleton(GenerateMenu::class, function ($app) {
            return class_exists(AppGenerateMenu::class) ? new AppGenerateMenu : new GenerateMenu;
        });



        /**
         * Translation
         */
        $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'nabre-quickadmin');
        $this->publishes([
            __DIR__ . '/../../lang' => $this->app->langPath('vendor/nabre-quickadmin'),
        ], 'nabre-quickadmin');
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
}
