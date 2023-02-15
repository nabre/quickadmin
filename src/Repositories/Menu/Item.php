<?php

namespace Nabre\Quickadmin\Repositories\Menu;

use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Request;
use Lavary\Menu\Item as MenuItem;
use Illuminate\Support\Facades\Route;

class Item extends MenuItem
{
<<<<<<< HEAD
=======

>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
    public function checkActivationStatus()
    {
        if (true === $this->disableActivationByURL) {
            return;
        }

        parent::{__FUNCTION__}();

        if (!$this->isActive) {

            $params = request()->route()->originalParameters();
            $id = substr(($rpath = Request::path()), strrpos($rpath, "/") + 1);

            if (in_array($id, $params)) {

                $p = substr($reFUrl = Request::fullUrl(), (($strPos = strrpos($reFUrl, '?')) === false) ? strlen($reFUrl) : $strPos);
                $requestUrl = substr(($reUrl = Request::url()), 0, strrpos($reUrl, "/"));
                $requestFullUrl = $requestUrl . $p;

                if ($this->url() == $requestUrl || $this->url() == $requestFullUrl) {
                    $this->activate();
                }
            }
        }
    }
}
