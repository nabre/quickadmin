<?php

namespace Nabre\Quickadmin\Forms\Builder\Settings;

use Illuminate\Support\Facades\Artisan;
use Nabre\Quickadmin\Models\FormFieldType as Model;
use Nabre\Quickadmin\Repositories\Form\Field;
use Nabre\Quickadmin\Repositories\Form\Form;

class TypeForm extends Form
{
    protected $model = Model::class;

    function build()
    {
        $this->add('key',Field::STATIC);
    }

    function refresh(){
        return function(){
            Artisan::call('sync:field-type');
        };
    }
}
