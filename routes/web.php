<?php

use Illuminate\Support\Facades\Route;
use Nabre\Quickadmin\Http\Controllers\Auth\NewPasswordController;
use Nabre\Quickadmin\Http\Controllers\Auth\VerifyEmailController;
use Nabre\Quickadmin\Http\Controllers\Auth\RegisteredUserController;
use Nabre\Quickadmin\Http\Controllers\Auth\PasswordResetLinkController;
use Nabre\Quickadmin\Http\Controllers\Auth\ConfirmablePasswordController;
use Nabre\Quickadmin\Http\Controllers\Auth\AuthenticatedSessionController;
use Nabre\Quickadmin\Http\Controllers\Auth\EmailVerificationPromptController;
use Nabre\Quickadmin\Http\Controllers\Auth\EmailVerificationNotificationController;
use Nabre\Quickadmin\Http\Controllers\User\DashboardController as UserDashboardController;
use Nabre\Quickadmin\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use Nabre\Quickadmin\Http\Controllers\Admin\Users\ListController;
use Nabre\Quickadmin\Http\Controllers\Admin\Users\PermissionsController;
use Nabre\Quickadmin\Http\Controllers\Admin\Users\RolesController;
use Nabre\Quickadmin\Http\Controllers\Manage\DashboardController as ManageDashboardController;

Route::name("quickadmin.")->group(function () {
    Route::middleware(['verified','auth'])->group(function () {

        Route::name("user.")->prefix('user')->group(function () {
            Route::get(null, function () {
                return redirect()->route('quickadmin.user.dashboard.index');
            })->name('rdr');
            Route::resource('dashboard', UserDashboardController::class, ['key' => 'data'])->only('index');
        });

        Route::name("admin.")->prefix('admin')->middleware(['role:admin'])->group(function () {
            Route::get(null, function () {
                return redirect()->route('quickadmin.admin.dashboard.index');
            })->name('rdr');
            Route::resource('dashboard', AdminDashboardController::class, ['key' => 'data'])->only('index');

            Route::name("users.")->prefix('users')->group(function () {
                Route::get(null, function () {
                    return redirect()->route('quickadmin.admin.users.list.index');
                })->name('rdr');
                Route::resource('list', ListController::class, ['key' => 'data'])->only('index');

                Route::resource('roles', RolesController::class, ['key' => 'data'])->only('index');
                Route::resource('permissions', PermissionsController::class, ['key' => 'data'])->only('index');
            });
        });

        Route::name("manage.")->prefix('manage')->middleware(['role:manage'])->group(function () {
            Route::get(null, function () {
                return redirect()->route('quickadmin.manage.dashboard.index');
            })->name('rdr');
            Route::resource('dashboard', ManageDashboardController::class, ['key' => 'data'])->only('index');
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
