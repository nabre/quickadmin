<?php

namespace Nabre\Quickadmin\Http\Controllers\Admin\Users;

use Nabre\Quickadmin\Forms\Admin\Users\PermissionsForm as Form;
use Nabre\Quickadmin\Http\Controllers\Controller;

class PermissionsController extends Controller
{
    function index($id=null)
    {
        $CONTENT = Form::public($id);
        return view('nabre-quickadmin::quick.admin', get_defined_vars());
    }
}
