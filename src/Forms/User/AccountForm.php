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
        $this->add('email')->email()->required();
    }

    function settings()
    {
        return ['back' => false,'id'=>auth()->user()];
    }

}
