<?php

namespace Nabre\Quickadmin\Facades\Routing;

use Nabre\Quickadmin\Routing\RouteHierarchy as RoutingRouteHierarchy;
use Nabre\Quickadmin\Support\Facade;

class RouteHierarchy extends Facade{

    protected static function getFacadeAccessor()
    {
        return RoutingRouteHierarchy::class;
    }
}
