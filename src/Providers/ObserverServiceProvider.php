<?php

namespace Nabre\Quickadmin\Providers;

use Illuminate\Support\ServiceProvider;
use Nabre\Quickadmin\Models\Contact;
use App\Models\User;
use Nabre\Quickadmin\Observers\ContactObserver;
use Nabre\Quickadmin\Observers\UserObserver;

class ObserverServiceProvider extends ServiceProvider
{
    public function boot()
    {
        User::observe(UserObserver::class);
        Contact::observe(ContactObserver::class);
    }
}
