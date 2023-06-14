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
        $this->add('contact')->onlyList()->list('fullname');
      /* $this->add('Con')->value(function ($data) {
            $contact = $data->contact;

            if (config('setting.define.autousergenerate') && is_null($contact)) {
                return 'Aggiungi contatto';
            } elseif(!is_null($contact)) {
                return data_get($data->contact, 'fullname') . '<br>Rimuovi contatto';
            }
        })->onlyList();*/

        $rolesDisabled = function ($data) {
            $min = auth()->user()->roles->min('priority');
            $logic = '<';
            if ($data->id == auth()->user()->id) {
                $logic .= '=';
            }
            return Role::where('priority', $logic, $min)->get()->pluck('id')->toArray();
        };
        $rolesQuery = function ($data, $model) {
            //$min = auth()->user()->roles->min('priority');
            return $model->get(); //->where('priority','>=', $min)->get();
        };
        $this->add('roles')->listDisabled($rolesDisabled)->list('eti', $rolesQuery);

        $enbleDisable = function ($data): bool {
            return auth()->user()->id == $data->id;
        };

        $this->add('permissions')->list('eti');

        $this->add('enabled', Field::BOOLEAN)->onlyForm()->disable($enbleDisable);

        $this->add('Stato')->value(function ($data) {
            return view('nabre-quickadmin::custom.field.user-status', get_defined_vars())->render();
        })->onlyList();
    }
}
