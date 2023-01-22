<?php

namespace Nabre\Quickadmin\Support;

trait Facade
{
    protected static function resolveFacade($name)
    {
        return is_null($name) ? new self : data_get(app(), $name) ?? new self;
    }

    protected static function getFacadeAccessor()
    {
        return null;
    }

    public static function __callStatic($method, $args)
    {
        return self::resolveFacade(self::getFacadeAccessor())->$method(...$args);
    }
}
