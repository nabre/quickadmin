<?php

use Illuminate\Support\Facades\Route;
use Nabre\Quickadmin\Http\Controllers\WelcomeController;
use Nabre\Quickadmin\Http\Controllers\Shop\PaymentController;
use Nabre\Quickadmin\Http\Controllers\User\AccountController;
use Nabre\Quickadmin\Http\Controllers\User\ProfileController;
use Nabre\Quickadmin\Http\Controllers\Manage\ContactsController;
use Nabre\Quickadmin\Http\Controllers\Admin\Users\ListController;
use Nabre\Quickadmin\Http\Controllers\Auth\NewPasswordController;
use Nabre\Quickadmin\Http\Controllers\Auth\VerifyEmailController;
use Nabre\Quickadmin\Http\Controllers\Admin\Users\RolesController;
use Nabre\Quickadmin\Http\Controllers\Auth\RegisteredUserController;
use Nabre\Quickadmin\Http\Controllers\Builder\Settings\TypeController;
use Nabre\Quickadmin\Http\Controllers\Auth\PasswordResetLinkController;
use Nabre\Quickadmin\Http\Controllers\Admin\Users\ImpersonateController;
use Nabre\Quickadmin\Http\Controllers\Admin\Users\PermissionsController;
use Nabre\Quickadmin\Http\Controllers\Auth\ConfirmablePasswordController;
use Nabre\Quickadmin\Http\Controllers\Auth\AuthenticatedSessionController;
use Nabre\Quickadmin\Http\Controllers\Auth\EmailVerificationPromptController;
use Nabre\Quickadmin\Http\Controllers\Builder\Settings\RefreshPanelController;
use Nabre\Quickadmin\Http\Controllers\Auth\EmailVerificationNotificationController;
use Nabre\Quickadmin\Http\Controllers\User\SettingsController as UserSettingsController;
use Nabre\Quickadmin\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use Nabre\Quickadmin\Http\Controllers\User\DashboardController as UserDashboardController;
use Nabre\Quickadmin\Http\Controllers\Manage\SettingsController as ManageSettingsController;
use Nabre\Quickadmin\Http\Controllers\Builder\SettingsController as BuilderSettingsController;
use Nabre\Quickadmin\Http\Controllers\Manage\DashboardController as ManageDashboardController;
use Nabre\Quickadmin\Http\Controllers\Builder\Settings\ListController as BuilderSetListController;
use Nabre\Quickadmin\Http\Controllers\Shop\ShopController;

Route::macro('livewire', function ($route, $method) {
    $string = collect(explode("/", $route))->push('{mode?}', '{id?}')->implode("/");
    return Route::get($string, $method);
});

Route::livewire('/', [WelcomeController::class, 'index'])->name('welcome');

Route::name("quickadmin.")->group(function () {
    Route::middleware(['shop'])->group(function () {
        Route::name("shop.")->prefix('shop')->group(function () {
            Route::get(null, function () {
                return redirect()->route('quickadmin.shop.products');
            })->name('rdr');

            Route::get('products', [ShopController::class, 'products'])->name('products');
            Route::get('cart', [ShopController::class, 'cart'])->name('cart');
            Route::get('invoice', [ShopController::class, 'invoice'])->name('invoice');
        });

        Route::name("pay.")->prefix('pay')->group(function () {
            Route::get(null, function () {
                return redirect()->route('quickadmin.pay.status', ['status' => 'checkout']);
            })->name('rdr');
            Route::get('{status}/{mode?}', [PaymentController::class, 'status'])->name('status');
            Route::name("response.")->prefix('response')->group(function () {
                Route::get('success', [PaymentController::class, 'success'])->name('success');
                Route::get('cancel', [PaymentController::class, 'cancel'])->name('cancel');
            });
        });
    });

    Route::middleware(['verified', 'auth'])->group(function () {
        Route::resource('admin/user/impersonate', ImpersonateController::class, ['key' => 'data'])->only(['create']);

        Route::name("user.")->prefix('user')->group(function () {
            Route::get(null, function () {
                return redirect()->route('quickadmin.user.dashboard.index');
            })->name('rdr');
            Route::resource('dashboard', UserDashboardController::class, ['key' => 'data'])->only('livewire');
            Route::middleware(['user-account'])->resource('account', AccountController::class, ['key' => 'data'])->only('livewire');
            Route::middleware(['user-profile'])->resource('profile', ProfileController::class, ['key' => 'data'])->only('livewire');
            Route::middleware(['settings-define'])->resource('settings', UserSettingsController::class, ['key' => 'data'])->only('livewire');
        });

        Route::name("admin.")->prefix('admin')->middleware(['role:admin'])->group(function () {
            Route::get(null, function () {
                return redirect()->route('quickadmin.admin.settings.index');
            })->name('rdr');
            Route::middleware(['settings-define'])->resource('settings', AdminSettingsController::class, ['key' => 'data'])->only('livewire');

            Route::name("users.")->prefix('users')->group(function () {
                Route::get(null, function () {
                    return redirect()->route('quickadmin.admin.users.list.index');
                })->name('rdr');
                Route::resource('list', ListController::class, ['key' => 'data'])->only('livewire');
                Route::resource('roles', RolesController::class, ['key' => 'data'])->only('livewire');
                Route::resource('permissions', PermissionsController::class, ['key' => 'data'])->only('livewire');
                Route::middleware(['role:builder'])->resource('impersonate', ImpersonateController::class, ['key' => 'data'])->only(['index', 'edit'])->except('update');
            });
        });

        Route::name("builder.")->prefix('builder')->middleware(['role:builder'])->group(function () {
            Route::get(null, function () {
                return redirect()->route('quickadmin.builder.settings.index');
            })->name('rdr');
            Route::middleware(['settings-define'])->resource('settings', BuilderSettingsController::class, ['key' => 'data'])->only('livewire');
            Route::name("settings.")->prefix('settings-define')->group(function () {
                Route::get(null, function () {
                    return redirect()->route('quickadmin.builder.settings.list.index');
                })->name('rdr');
                Route::resource('list', BuilderSetListController::class, ['key' => 'data'])->only('livewire');
                Route::resource('type', TypeController::class, ['key' => 'data'])->only('livewire');
                Route::resource('refresh', RefreshPanelController::class, ['key' => 'data'])->only('livewire');
            });
        });

        Route::name("manage.")->prefix('manage')->middleware(['role:manage'])->group(function () {
            Route::middleware(['settings-define'])->resource('settings', ManageSettingsController::class, ['key' => 'data'])->only('livewire');
            Route::get(null, function () {
                return redirect()->route('quickadmin.manage.dashboard.index');
            })->name('rdr');
            Route::resource('dashboard', ManageDashboardController::class, ['key' => 'data'])->only('livewire');
            Route::middleware(['user-contact-model'])->resource('contacts', ContactsController::class, ['key' => 'data'])->only('livewire');
        });
    });
});

Route::get('/register', [RegisteredUserController::class, 'create'])
    ->middleware(['guest', 'registration'])
    ->name('register');

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest', 'registration');

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.update');

Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
    ->middleware('auth')
    ->name('verification.notice');

Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
    ->middleware('auth')
    ->name('password.confirm');

Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
    ->middleware('auth');

Route::get('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
