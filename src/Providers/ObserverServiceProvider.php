<?php

namespace Nabre\Quickadmin\Providers;

use App\Models\User;
use App\Models\Contact;
use Illuminate\Support\ServiceProvider;
use Nabre\Quickadmin\Observers\UserObserver;
use Nabre\Quickadmin\Observers\ContactObserver;

class ObserverServiceProvider extends ServiceProvider
{
    public function boot()
    {
        User::observe(UserObserver::class);
        Contact::observe(ContactObserver::class);
    }
}
