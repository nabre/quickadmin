<?php
namespace Nabre\Quickadmin\Http\Controllers\Admin;

use Nabre\Quickadmin\Http\Controllers\Controller;
use Nabre\Quickadmin\Forms\Admin\SettingsForm as Form;

class SettingsController extends Controller{
    function index(){
        $CONTENT = Form::public();
        return view('nabre-quickadmin::quick.admin', get_defined_vars());    }
}
