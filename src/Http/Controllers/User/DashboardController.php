<?php

namespace Nabre\Quickadmin\Http\Controllers\User;

use Nabre\Quickadmin\Http\Controllers\Controller;

class DashboardController extends Controller
{
    function index()
    {
        return view('nabre-quickadmin::quick.user', ['CONTENT' => get_class($this)]);
    }
}
