<?php
<<<<<<< HEAD

namespace Nabre\Quickadmin\Forms\Manage;

use Nabre\Quickadmin\Forms\Admin\SettingsForm as AdminSettingsForm;

class SettingsForm extends AdminSettingsForm
{
    function query($items)
    {
        list($min, $roles) = $this->paramFn();
        return $items->filter(function ($i) use ($min, $roles) {
            return !$i->user
                && $i->roles->pluck('priority')->max() >= $min
                && count(array_intersect($i->roles->pluck('name')->toArray(), $roles));
        })->values();
    }
}
=======
namespace Nabre\Quickadmin\Forms\Manage;

use Nabre\Quickadmin\Forms\SettingsBackForm;

class SettingsForm extends SettingsBackForm{

    function rolePage()
    {
        return 'manage';
    }
}

>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
