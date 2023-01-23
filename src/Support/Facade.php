<?php

namespace Nabre\Quickadmin\Support;

use RuntimeException;

abstract class Facade
{
    protected static function resolveFacade($name)
    {
        if (!class_exists($name) && is_null($instance = data_get(app(), $name))) {
            throw ('Error');
        }
        return $instance ?? new $name;
    }

    protected static function getFacadeAccessor()
    {
        throw new RuntimeException('Facade does not implement getFacadeAccessor method.');
    }


    public static function __callStatic($method, $args)
    {
        return static::resolveFacade(static::getFacadeAccessor())->$method(...$args);
    }
}
