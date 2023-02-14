<?php

namespace Nabre\Quickadmin\Facades\Repositories;

use Nabre\Quickadmin\Repositories\AutoLabel as RepositoriesAutoLabel;
use Nabre\Quickadmin\Support\Facade;

class AutoLabel extends Facade{

    protected static function getFacadeAccessor()
    {
        return RepositoriesAutoLabel::class;
    }
}
