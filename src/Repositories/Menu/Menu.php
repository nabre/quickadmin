<?php

namespace Nabre\Quickadmin\Repositories\Menu;

use Lavary\Menu\Menu as LavaryMenu;
use Nabre\Quickadmin\Facades\Repositories\Menu\Page;
use Illuminate\Support\Facades\View;

class Menu extends LavaryMenu
{
    public function make($name, $callback, array $options = [])
    {
        if (!is_callable($callback)) {
            return null;
        }

        if (!array_key_exists($name, $this->menu)) {
            $this->menu[$name] = new Builder($name, array_merge($this->loadConf($name), $options));
        }

        // Registering the items
        call_user_func($callback, $this->menu[$name]);

        // Storing each menu instance in the collection
        $this->collection->put($name, $this->menu[$name]);

        // Make the instance available in all views
        View::share($name, $this->menu[$name]);

        return $this->menu[$name];
    }

    function get($key)
    {
        return optional(optional(parent::{__FUNCTION__}($key))->filter(function ($item) {
<<<<<<< HEAD
            $route=data_get($item->link->path,'route');
=======
            $route = data_get($item->link->path, 'route');
            if (is_array($route)) {
                $route = data_get($route, 0);
            }
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
            return is_null($route) || Page::route($route);
        }));
    }
}
