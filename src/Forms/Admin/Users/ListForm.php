<?php
namespace Nabre\Quickadmin\Forms\Admin\Users;

use Nabre\Quickadmin\Repositories\Form\Form;

class ListForm extends Form{
    function build(){
        $this->add('name');
        $this->add('email');
        $this->add('roles')->listLabel('name');
    }
}
