<?php

namespace Nabre\Quickadmin\Forms;

use App\Models\Demo as Model;
use Nabre\Quickadmin\Repositories\Form2\Form;
use Nabre\Quickadmin\Repositories\Form2\Rule;

class NewForm extends Form
{
    protected $model = Model::class;

    function build()
    {
        $this->add('name')->rules(Rule::required(),Rule::min(5));
        $this->add('email')->rules(Rule::email());
        $this->add('role')->list('eti');
    }
}
