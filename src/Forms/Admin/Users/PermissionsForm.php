<?php

namespace Nabre\Quickadmin\Forms\Admin\Users;

use App\Models\Permission as Model;
use Nabre\Quickadmin\Repositories\Form\Form;

class PermissionsForm extends Form
{
    protected $model = Model::class;

    function build()
    {
        $this->add('eti')->onlyList();
        $this->add('name');
        $this->add('slug')->onlyForm();
    }
}
