<?php
<<<<<<< HEAD

namespace Nabre\Quickadmin\Forms\Admin;

use Nabre\Quickadmin\Models\Setting as Model;
use Nabre\Quickadmin\Repositories\Form\Field;
use Nabre\Quickadmin\Repositories\Form\Form;

class SettingsForm extends Form
{
    protected $model = Model::class;

    function build()
    {
        $this->add('string');
        $output = function ($data) {
            return data_get($data->type, 'key') ?? Field::STATIC;
        };
        $this->add(config('setting.database.value'), $output);
        $this->add('user_setting', Field::STATIC)->output_edit(Field::BOOLEAN2);
    }

    function query($items)
    {
        list($min, $roles) = $this->paramFn();
        return $items->filter(function ($i) use ($min, $roles) {
            return !$i->user && (data_get($i, 'user_setting')
                || $i->roles->pluck('priority')->max() >= $min
                && count(array_intersect($i->roles->pluck('name')->toArray(), $roles)));
        })->values();
    }

    function paramFn()
    {
        $min = auth()->user()->roles->pluck('priority')->min();
        $roles = collect(request()->route()->middleware())->reject(function ($m) {
            return strpos($m, 'role:') === false;
        })->map(function ($m) {
            list(, $name) = explode(":", $m);
            return $name;
        })->values()->toArray();
        return [$min, $roles];
    }

    function settings()
    {
        return ['view' => 'form-list'];
    }
}
=======
namespace Nabre\Quickadmin\Forms\Admin;

use Nabre\Quickadmin\Forms\SettingsBackForm;

class SettingsForm extends SettingsBackForm{

    function rolePage()
    {
        return 'admin';
    }
}

>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
