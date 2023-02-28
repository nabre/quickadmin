<?php
namespace Nabre\Quickadmin\Http\Controllers\User;

use Nabre\Quickadmin\Http\Controllers\Controller;
use Nabre\Quickadmin\Forms\User\ProfileForm as Form;
class ProfileController extends Controller{
    function index(){
        $CONTENT = Form::public();
        return view('nabre-quickadmin::quick.user', get_defined_vars());
    }
}
