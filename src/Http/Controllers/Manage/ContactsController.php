<?php
namespace Nabre\Quickadmin\Http\Controllers\Manage;

use Nabre\Quickadmin\Http\Controllers\Controller;
use Nabre\Quickadmin\Forms\Manage\ContactForm as Form;

class ContactsController extends Controller{
    function index($id=null)
    {
        $CONTENT = Form::public($id);
        return view('nabre-quickadmin::quick.manage', get_defined_vars());
    }
}
