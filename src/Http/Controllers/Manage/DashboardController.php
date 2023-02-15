<?php

namespace Nabre\Quickadmin\Http\Controllers\Manage;

use Nabre\Quickadmin\Http\Controllers\Controller;

class DashboardController extends Controller
{
    function index()
    {
        return view('nabre-quickadmin::quick.manage', ['CONTENT' => get_class($this)]);
    }
}
