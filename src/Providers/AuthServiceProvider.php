<?php

namespace Nabre\Quickadmin\Providers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\UserContact;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Nabre\Quickadmin\Policies\PermissionPolicy;
use Nabre\Quickadmin\Policies\RolePolicy;
use Nabre\Quickadmin\Policies\UserPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Permission::class => PermissionPolicy::class,
        Role::class => RolePolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
