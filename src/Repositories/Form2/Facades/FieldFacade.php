<?php

namespace Nabre\Quickadmin\Repositories\Form2\Facades;

use Nabre\Quickadmin\Repositories\Form2\Field;
use Nabre\Quickadmin\Support\Facade;

class FieldFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Field::class;
    }
}
