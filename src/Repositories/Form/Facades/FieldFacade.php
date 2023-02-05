<?php

namespace Nabre\Quickadmin\Repositories\Form\Facades;

use Nabre\Quickadmin\Repositories\Form\Field;
use Nabre\Quickadmin\Support\Facade;

class FieldFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Field::class;
    }
}
