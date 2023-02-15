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
<<<<<<< HEAD
=======
        $this->add('route_used')->onlyList();
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
        $this->add('name');
        $this->add('slug')->onlyForm();
    }
}
