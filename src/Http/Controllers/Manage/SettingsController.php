<?php

namespace Nabre\Quickadmin\Http\Controllers\Manage;

use Nabre\Quickadmin\Http\Controllers\Controller;
use Nabre\Quickadmin\Forms\Manage\SettingsForm as Form;

class SettingsController extends Controller
{
    function index()
    {
        $CONTENT = Form::public();
        return view('nabre-quickadmin::quick.manage' , compact('CONTENT'));
    }
}
