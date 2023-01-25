<?php

namespace Nabre\Quickadmin\Http\Controllers\Admin\Users;

use App\Models\Role as Model;
use Nabre\Quickadmin\Forms\Admin\Users\RolesForm as Form;
use Nabre\Quickadmin\Http\Controllers\Controller;

class RolesController extends Controller
{
    function index()
    {
        $CONTENT = Form::public(Model::make());
        return view('nabre-quickadmin::quick.admin', get_defined_vars());
    }

    function edit(Model $data)
    {
        $CONTENT = Form::public($data);
        return view('nabre-quickadmin::quick.admin', get_defined_vars());
    }
}
