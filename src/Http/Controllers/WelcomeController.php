<?php

namespace Nabre\Quickadmin\Http\Controllers;

use Nabre\Quickadmin\Forms\NewForm;

class WelcomeController extends Controller
{
    function index($mode=null,$idData=null)
    {
        $CONTENT = NewForm::public($mode,$idData);
        return view('welcome', get_defined_vars());
    }
}
