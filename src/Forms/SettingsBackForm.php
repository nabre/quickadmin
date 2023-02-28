<?php

namespace Nabre\Quickadmin\Forms;

use App\Models\Role;
use Nabre\Quickadmin\Models\Setting as Model;
use Nabre\Quickadmin\Repositories\Form\Field;
use Nabre\Quickadmin\Repositories\Form\Form;
use Nabre\Quickadmin\Repositories\Form\Rule;
use Nabre\Quickadmin\Services\SettingService;

class SettingsBackForm extends Form
{
    protected $model = Model::class;

    function build()
    {
        $this->add('string');
        $output = function ($data) {
            return data_get($data, 'type.key') ?? Field::STATIC;
        };
        $this->add(config('setting.database.value'), $output);
        if ($this->rolePage() == 'builder') {
            $this->add('user_setting', Field::STATIC)->output_edit(Field::BOOLEAN2);
        }
    }

    function rolePage()
    {
        return null;
    }

    function query($items)
    {
        return SettingService::enabledCustomize($items, (array) $this->rolePage(), $this->rolePage());
    }

    function settings()
    {
        return ['view' => 'form-list', 'head' => false];
    }
}
