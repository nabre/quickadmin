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
        $this->add('email')->email();
      //  $this->addHtml('per modificare la password');
       // $this->add('password')->confirm();
    }

    function settings()
    {
        return ['back' => false,'id'=>auth()->user()];
    }

}
