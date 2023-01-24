<?php

namespace Nabre\Quickadmin\Repositories;

use Lavary\Menu\Menu as LavaryMenu;
use Nabre\Quickadmin\Facades\Repositories\Page;


class Menu extends LavaryMenu
{
    function get($key)
    {
        return optional(optional(parent::{__FUNCTION__}($key))->filter(function ($item) {
            $route=data_get($item->link->path,'route');
            return is_null($route) || Page::route($route);
        }));
    }
}
