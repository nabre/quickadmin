<?php

namespace Nabre\Quickadmin\Http\Controllers\Builder;

use Nabre\Quickadmin\Http\Controllers\Controller;
use Nabre\Quickadmin\Forms\Builder\SettingsForm as Form;

class SettingsController extends Controller
{
    function index()
    {
        $CONTENT = Form::public();
        return view('nabre-quickadmin::quick.builder' , compact('CONTENT'));
    }
}
