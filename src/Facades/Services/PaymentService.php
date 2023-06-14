<?php

namespace Nabre\Quickadmin\Facades\Services;

use Nabre\Quickadmin\Services\PaymentService as ClassFacade;
use Nabre\Quickadmin\Support\Facade;

class PaymentService extends Facade{

    protected static function getFacadeAccessor()
    {
        return ClassFacade::class;
    }
}


