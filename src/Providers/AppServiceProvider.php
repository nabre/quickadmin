<?php

namespace Nabre\Quickadmin\Providers;

use Lavary\Menu\Item as LavaryItem;
use Lavary\Menu\Menu as LavaryMenu;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Nabre\Quickadmin\Setting\Manager;
use Illuminate\Support\ServiceProvider;
use Lavary\Menu\Builder as LavaryBuilder;
use Nabre\Quickadmin\Repositories\Menu\Menu;
use Illuminate\Session\Middleware\StartSession;
use Nabre\Quickadmin\View\Components\AppLayout;
use Nabre\Quickadmin\View\Components\GuestLayout;
use Nabre\Quickadmin\Http\Middleware\GenerateMenu;
use Nabre\Quickadmin\Setting\Facade as SettingFacade;
use App\Http\Middleware\GenerateMenu as AppGenerateMenu;
use Nabre\Quickadmin\Console\Commands\OptimizeCommand;
use Nabre\Quickadmin\Repositories\Menu\Item as MenuItem;
use Nabre\Quickadmin\Console\Commands\Sync\SettingCommand;
use Nabre\Quickadmin\Repositories\LocalizationRepositorie;
use Nabre\Quickadmin\Http\Middleware\EnsureEmailIsVerified;
use Nabre\Quickadmin\Http\Middleware\ImpersonateMiddleware;
use Nabre\Quickadmin\Repositories\Menu\Builder as MenuBuilder;
use Nabre\Quickadmin\Console\Commands\Update\PermissionCommand;
use Nabre\Quickadmin\Http\Middleware\SettingAutoSaveMiddleware;
use Nabre\Quickadmin\Http\Middleware\SettingOverrideMiddleware;
use Nabre\Quickadmin\Console\Commands\Sync\FormFieldTypeCommand;
use Nabre\Quickadmin\Console\Commands\Update\UserCommand;
use Nabre\Quickadmin\Http\Middleware\HttpRedirectSecure;
use Nabre\Quickadmin\Http\Middleware\PagesEnable\AccountEnableMiddleware;
use Nabre\Quickadmin\Http\Middleware\PagesEnable\ContactMiddleware;
use Nabre\Quickadmin\Http\Middleware\PagesEnable\ProfileEnableMiddleware;
use Nabre\Quickadmin\Http\Middleware\PagesEnable\SettingEnableMiddleware;
use Nabre\Quickadmin\Http\Middleware\RegisterPageMiddleware;
use Nabre\Quickadmin\Http\Middleware\ShopPageMiddleware;

class AppServiceProvider extends ServiceProvider
{
    public $bindings = [
        \Illuminate\Routing\ResourceRegistrar::class => \Nabre\Quickadmin\Routing\ResourceRegistrar::class,
        LavaryMenu::class => Menu::class,
        LavaryBuilder::class=>MenuBuilder::class,
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
        $this->app->register(ObserverServiceProvider::class);
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
         * Connfig
         */
        $this->mergeConfigFrom(__DIR__ . '/../../config/setting.php', 'setting');
    }

    function boot(\Illuminate\Routing\Router $router, \Illuminate\Contracts\Http\Kernel $kernel)
    {
        $kernel->pushMiddleware(StartSession::class);
        $kernel->pushMiddleware(ImpersonateMiddleware::class);
        $kernel->pushMiddleware(SettingOverrideMiddleware::class);
        $router->pushMiddlewareToGroup('web', GenerateMenu::class);
        $router->pushMiddlewareToGroup('web', HttpRedirectSecure::class);
        $router->aliasMiddleware('role', \Maklad\Permission\Middlewares\RoleMiddleware::class);
        $router->aliasMiddleware('permission', \Maklad\Permission\Middlewares\PermissionMiddleware::class);
        $router->aliasMiddleware('user-account', AccountEnableMiddleware::class);
        $router->aliasMiddleware('user-profile', ProfileEnableMiddleware::class);
        $router->aliasMiddleware('user-settings', UserSettingEnableMiddleware::class);
        $router->aliasMiddleware('settings-define', SettingEnableMiddleware::class);
        $router->aliasMiddleware('registration', RegisterPageMiddleware::class);
        $router->aliasMiddleware('user-contact-model', ContactMiddleware::class);
        $router->aliasMiddleware('shop', ShopPageMiddleware::class);

        /**
         *  Config
         */
        $this->publishes([
            __DIR__ . '/../../config/routeicons.php' => config_path('routeicons.php'),
        ], 'nabre-quickadmin');
        $this->mergeConfigFrom(__DIR__ . '/../../config/routeicons.php', 'routeicons');

        $this->loadViewComponentsAs('', [
            AppLayout::class,
            GuestLayout::class,
        ]);

        /**
         * Bindings
         */
        $this->app->singleton(GenerateMenu::class, function ($app) {
            return class_exists(AppGenerateMenu::class) ? new AppGenerateMenu : new GenerateMenu;
        });

        $this->app->singleton(\Illuminate\Auth\Middleware\EnsureEmailIsVerified::class, function ($app) {
            return new EnsureEmailIsVerified($app);
        });

        $this->app->singleton(LocalizationRepositorie::class, function ($app) {
            return new LocalizationRepositorie;
        });

        /**
         * Commands
         */
        $this->commands([
            PermissionCommand::class,
            SettingCommand::class,
            FormFieldTypeCommand::class,
            OptimizeCommand::class,
            UserCommand::class,
        ]);

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
