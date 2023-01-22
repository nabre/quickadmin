<?php

namespace Nabre\Quickadmin\Repositories\Menu;

use Lavary\Menu\Menu as LavaryMenu;

class Menu extends LavaryMenu
{
    function get($key)
    {
      ///  dd(get_class($this),class_exists(get_class($this)),PageCheck::class,class_exists(PageCheck::class));

      dd(new PageCheck);

        return parent::{__FUNCTION__}($key)->filter(function ($item) {

            return true;
        });
    }
}
