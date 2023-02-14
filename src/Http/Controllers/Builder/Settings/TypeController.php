<?php
namespace Nabre\Quickadmin\Http\Controllers\Builder\Settings;

use Nabre\Quickadmin\Http\Controllers\Controller;
use Nabre\Quickadmin\Forms\Builder\Settings\TypeForm as Form;
class TypeController extends Controller{
    function index($id=null){
        $CONTENT = Form::public($id);
        return view('nabre-quickadmin::quick.builder', get_defined_vars());    }
}
