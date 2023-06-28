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
        $this->add('name')->rules(Rule::required(),Rule::min(3))->unique();
        $this->add('email')->rules(Rule::required(),Rule::email(),Rule::min(5))->unique();
        $this->add('role')->list('eti');
    }
}
