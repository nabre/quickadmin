<?php

namespace Nabre\Quickadmin\Forms\Manage;

use App\Models\Role;
use Nabre\Quickadmin\Repositories\Form\Form;
use Nabre\Quickadmin\Repositories\Form\Field;
use App\Models\Contact as Model;

class ContactForm extends Form
{
    protected $model = Model::class;

    function build()
    {
        $this->add('lastname')->required();
        $this->add('firstname')->required();
     //   $this->add('email')->required()->unique()->email();
     //   $this->add('permission')->required()->list('eti');
     //   $this->add('account_bool',Field::BOOLEAN)->onlyList();

      /*  $this->add('name')->required();

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
        $this->add('enabled', Field::BOOLEAN)->onlyForm()->disable($enbleDisable);

        $this->add('Stato')->value(function ($data) {
            return view('nabre-quickadmin::custom.field.user-status', get_defined_vars())->render();
        })->onlyList();*/
    }
}
