<?php

namespace Nabre\Quickadmin\Facades\Repositories;

use Nabre\Quickadmin\Repositories\Page as RepositoriesPage;
use Nabre\Quickadmin\Support\Facade;

class Page extends Facade{

    protected static function getFacadeAccessor()
    {
        return RepositoriesPage::class;
    }
}
