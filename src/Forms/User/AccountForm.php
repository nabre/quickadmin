<?php

namespace Nabre\Quickadmin\Forms\User;

use App\Models\User as Model;
use Nabre\Quickadmin\Repositories\Form\Form;

class AccountForm extends Form
{
    protected $model = Model::class;

    function build()
    {
        $this->add('name')->required();
<<<<<<< HEAD
        $this->add('email')->email();
      //  $this->addHtml('per modificare la password');
       // $this->add('password')->confirm();
=======
        $this->add('email')->email()->required();
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
    }

    function settings()
    {
        return ['back' => false,'id'=>auth()->user()];
    }

}
