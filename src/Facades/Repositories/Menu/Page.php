<?php

namespace Nabre\Quickadmin\Facades\Repositories\Menu;

use Nabre\Quickadmin\Repositories\Menu\Page as RepositoriesPage;
use Nabre\Quickadmin\Support\Facade;

class Page extends Facade{

    protected static function getFacadeAccessor()
    {
        return RepositoriesPage::class;
    }
}
