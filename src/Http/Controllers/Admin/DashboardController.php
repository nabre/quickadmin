<?php

namespace Nabre\Quickadmin\Http\Controllers\Admin;

use Nabre\Quickadmin\Http\Controllers\Controller;

class DashboardController extends Controller
{
    function index()
    {
        return view('nabre-quickadmin::quick.admin', ['CONTENT' => get_class($this)]);
    }
}
