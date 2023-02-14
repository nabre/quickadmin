<?php

namespace Nabre\Quickadmin\Forms\Builder\Settings;

use Illuminate\Support\Facades\Artisan;
use Nabre\Quickadmin\Models\Setting as Model;
use Nabre\Quickadmin\Repositories\Form\Field;
use Nabre\Quickadmin\Repositories\Form\Form;

class ListForm extends Form
{
    protected $model = Model::class;

    function build()
    {
        $this->add('string');
        $this->add(config('setting.database.key'), Field::STATIC);
        $this->add('type');
        $disable = function ($data) {
            return data_get($data, 'user_setting_disable');
        };
        $this->add('user_setting')->disable($disable);
    }

    function query($items)
    {
        return $items->reject(fn ($i) => $i->user)->values();
    }

    function refresh()
    {
        return function () {
            Artisan::call('sync:setting');
        };
    }
}
