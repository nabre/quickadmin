<?php
namespace Nabre\Quickadmin\Forms\Admin\Users;

use Nabre\Quickadmin\Repositories\Form\Form;

class RolesForm extends Form{

    function build(){
        $this->add('name');
        $this->add('slug');
    }

}
