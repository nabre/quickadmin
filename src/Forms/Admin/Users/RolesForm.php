<?php

namespace Nabre\Quickadmin\Forms\Admin\Users;

use App\Models\Role as Model;
use Illuminate\Support\Facades\Artisan;
use Nabre\Quickadmin\Repositories\Form\Field;
use Nabre\Quickadmin\Repositories\Form\Form;

class RolesForm extends Form
{
    protected $model = Model::class;

    function build()
    {
        $this->add('eti')->onlyList();
<<<<<<< HEAD
=======
        $this->add('route_used')->onlyList();
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
        $this->add('name')->disable(function ($data) {
            return data_get($data, 'route_used');
        });
        $this->add('slug')->onlyForm();
    }

    function query($items)
    {
        $min=auth()->user()->roles->pluck('priority')->min();
        return $items->reject(fn($i)=>data_get($i,'priority')<$min)->values();
    }

    function refresh()
    {
        return function () {
            Artisan::call('update:permission');
        };
    }
}
