<?php

namespace Nabre\Quickadmin\Forms\Admin\Users;

use App\Models\User as Model;
use Collective\Html\HtmlFacade as Html;
use Nabre\Quickadmin\Repositories\Form\Form;
use Nabre\Quickadmin\Repositories\Form\Field;

class ImpersonateForm extends Form
{
    protected $model = Model::class;

    function build()
    {
        $this->add('email');
        $this->add('name');
        $this->add('verified_email', Field::BOOLEAN);
        $this->add('impersonate')->value(function ($data) {
            if (is_null($id = data_get($data, 'id'))) {
                return;
            }
            $rName = request()->route()->getName();
            $href = route(substr($rName, 0, strripos($rName, '.') + 1) . 'edit', $id);
            $class = "btn btn-dark";
            return (string)Html::a('<i class="fa-solid fa-person-walking-arrow-right" ></i>', compact('href', 'class'));
        });
    }

    function settings()
    {
        return ['crud' => false];
    }

    function query($items)
    {
        return $items->reject(fn ($i) => data_get($i, 'id') == auth()->user()->id)->values();
    }
}
