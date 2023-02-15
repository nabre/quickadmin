<?php

namespace Nabre\Quickadmin\Http\Controllers\Auth;

use Nabre\Quickadmin\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class RegisteredUserController extends Controller
{
    public function create()
    {
        $CONTENT='registra utente';
        return view('nabre-quickadmin::auth.register', compact('CONTENT'));
    }

    public function store()
    {
        Auth::login($user);
        event(new Registered($user));
        return redirect(RouteServiceProvider::HOME);
    }
}
