<?php
namespace Nabre\Quickadmin\Http\Controllers\Builder\Settings;

use Nabre\Quickadmin\Http\Controllers\Controller;
use Nabre\Quickadmin\Forms\Builder\Settings\TypeForm as Form;
class RefreshPanelController extends Controller{
    function index(){
        $CONTENT = livewire('refreshPanel');
        return view('nabre-quickadmin::quick.builder', get_defined_vars());    }
}
