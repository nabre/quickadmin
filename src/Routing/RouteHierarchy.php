<?php

namespace Nabre\Quickadmin\Routing;

//use Nabre\Repositories\Menu\Generate;
use Illuminate\Support\Facades\Route;

class RouteHierarchy
{

    function routeGetList()
    {
        return Route::getRoutesList()->filter(function ($r) {
            $varOptional = count(explode("{", $r->uri)) == count(explode("{?", $r->uri)) || count(explode("{", $r->uri)) == 0;
            return in_array('GET', (array)$r->method) && $varOptional;
        })->values();
    }

    function ruoteUri($middleware = false)
    {
        return $this->routeGetList()->pluck('uri')->sortBy('uri')->values();
    }

    function middlewareList()
    {
        $middlewareList = collect([]);
        Route::getRoutesList()->pluck('middleware')->each(function ($i) use (&$middlewareList) {
            $middlewareList = $middlewareList->merge($i);
        });
        $middlewareList=$middlewareList->unique()->values();

        $middleware=$middlewareList->like(null,'role:%')->merge($middlewareList->like(null,'permission:%'))
        ->values()->map(function($i){
            list($type,$name)=explode(':',$i);
            unset($i);
            return get_defined_vars();
        });
        return $middleware;
    }
}
