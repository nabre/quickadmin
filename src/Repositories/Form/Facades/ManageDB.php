<?php

namespace Nabre\Quickadmin\Repositories\Form\Facades;

use Nabre\Quickadmin\Repositories\Form\ManageDB as FormTwoManageDB;
use Nabre\Quickadmin\Support\Facade;

class ManageDB extends Facade{

    protected static function getFacadeAccessor()
    {
        return FormTwoManageDB::class;
    }
}
