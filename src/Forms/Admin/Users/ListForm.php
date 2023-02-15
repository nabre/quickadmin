<?php

namespace Nabre\Quickadmin\Forms\Admin\Users;

use App\Models\Role;
use Nabre\Quickadmin\Repositories\Form\Form;
use Nabre\Quickadmin\Repositories\Form\Field;
use App\Models\User as Model;

class ListForm extends Form
{
    protected $model = Model::class;

    function build()
    {
        $this->add('email')->required()->unique()->email();
        $this->add('name')->required();
<<<<<<< HEAD
=======
        $this->add('contact')->onlyList()->list('full_name');

>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870

        $rolesDisabled = function ($data) {
            $min = auth()->user()->roles->min('priority');
            $logic = '<';
            if ($data->id == auth()->user()->id) {
                $logic .= '=';
            }
            return Role::where('priority', $logic, $min)->get()->pluck('id')->toArray();
        };
        $rolesQuery = function ($data, $model) {
            return $model->get();
        };
        $this->add('roles')->listDisabled($rolesDisabled)->list('eti', $rolesQuery);

        $enbleDisable = function ($data): bool {
            return auth()->user()->id == $data->id;
        };
<<<<<<< HEAD
=======

        $this->add('permissions')->list('eti');

>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
        $this->add('enabled', Field::BOOLEAN)->onlyForm()->disable($enbleDisable);

        $this->add('Stato')->value(function ($data) {
            return view('nabre-quickadmin::custom.field.user-status', get_defined_vars())->render();
        })->onlyList();
    }
}
