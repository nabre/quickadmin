<?php
namespace Nabre\Quickadmin\Http\Controllers\User;

use Nabre\Quickadmin\Http\Controllers\Controller;
use Nabre\Quickadmin\Forms\User\AccountForm as Form;
class AccountController extends Controller{
    function index(){
        $CONTENT = Form::public();
        return view('nabre-quickadmin::quick.user', get_defined_vars());
    }
}
