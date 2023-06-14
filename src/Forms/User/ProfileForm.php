<?php

namespace Nabre\Quickadmin\Forms\User;

use App\Models\Contact as Model;
use Nabre\Quickadmin\Repositories\Form\Form;

class ProfileForm extends Form
{
    protected $model = Model::class;

    function build()
    {
        $this->add('lastname')->required();
        $this->add('firstname')->required();
        $this->add('email')->email()->required();
    }

    function settings()
    {
        return ['back' => false, 'id' => auth()->user()->contact];
    }
}
