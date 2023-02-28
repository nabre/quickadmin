<?php
<<<<<<< HEAD
=======

>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
namespace Nabre\Quickadmin\Http\Controllers\Admin;

use Nabre\Quickadmin\Http\Controllers\Controller;
use Nabre\Quickadmin\Forms\Admin\SettingsForm as Form;

<<<<<<< HEAD
class SettingsController extends Controller{
    function index(){
        $CONTENT = Form::public();
        return view('nabre-quickadmin::quick.admin', get_defined_vars());    }
=======
class SettingsController extends Controller
{
    function index()
    {
        $CONTENT = Form::public();
        return view('nabre-quickadmin::quick.admin' , compact('CONTENT'));
    }
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
}
