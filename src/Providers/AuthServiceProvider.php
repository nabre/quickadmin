<?php

namespace Nabre\Quickadmin\Providers;

use App\Models\Contact;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\UserContact;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Nabre\Quickadmin\Models\FormFieldType;
use Nabre\Quickadmin\Models\Setting;
use Nabre\Quickadmin\Policies\ContactPolicy;
use Nabre\Quickadmin\Policies\FormFieldTypePolicy;
use Nabre\Quickadmin\Policies\PermissionPolicy;
use Nabre\Quickadmin\Policies\RolePolicy;
use Nabre\Quickadmin\Policies\SettingPolicy;
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
        Setting::class=>SettingPolicy::class,
        FormFieldType::class=>FormFieldTypePolicy::class,
        Contact::class=>ContactPolicy::class,
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
