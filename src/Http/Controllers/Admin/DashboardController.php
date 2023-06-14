<?php

namespace Nabre\Quickadmin\Http\Controllers\Admin;

use Nabre\Quickadmin\Database\Eloquent\Model;
use Nabre\Quickadmin\Http\Controllers\Controller;

class DashboardController extends Controller
{
    function index()
    {

        dd( getExtendedClasses(Model::class));
        return view('nabre-quickadmin::quick.admin', ['CONTENT' => get_class($this)]);
    }
}
