<?php

namespace Nabre\Quickadmin\Http\Controllers\Admin\Users;

use Nabre\Quickadmin\Forms\Admin\Users\ListForm as Form;
use Nabre\Quickadmin\Http\Controllers\Controller;

class ListController extends Controller
{
    function index($id=null)
    {
        $CONTENT = Form::public($id);
        return view('nabre-quickadmin::quick.admin', get_defined_vars());
    }
}
