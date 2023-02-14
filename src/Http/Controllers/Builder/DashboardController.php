<?php

namespace Nabre\Quickadmin\Http\Controllers\Builder;

use Nabre\Quickadmin\Http\Controllers\Controller;

class DashboardController extends Controller
{
    function index()
    {
        return view('nabre-quickadmin::quick.builder', ['CONTENT' => get_class($this)]);
    }
}
