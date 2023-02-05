<?php

namespace Nabre\Quickadmin\Http\Controllers\Admin\Users;

use Nabre\Quickadmin\Http\Controllers\Controller;
use Nabre\Quickadmin\Forms\Admin\Users\ImpersonateForm as Form;

class ImpersonateController extends Controller
{
    function index()
    {
        $CONTENT = Form::public();
        return view('nabre-quickadmin::quick.admin', get_defined_vars());
    }

    function edit($data)
    {
        auth()->user()->setImpersonating($data);
        return redirect()->to('/');
    }

    function create()
    {
        auth()->user()->stopImpersonating();
        return redirect()->route('quickadmin.admin.users.impersonate.index');
    }
}
