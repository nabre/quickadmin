<?php

namespace Nabre\Quickadmin\Http\Controllers;

class WelcomeController extends Controller
{
    function index()
    {
        $CONTENT = get_class($this);
        return view('welcome', get_defined_vars());
    }
}
