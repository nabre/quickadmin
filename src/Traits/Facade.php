<?php

namespace Nabre\Quickadmin\Traits;

trait Facade
{
    static $facadeName = null;

    protected static function resolveFacade($name = null)
    {
        if (!is_null($name)) {
            return app()->make($name);
        }
        return (new self);
    }

    public static function __callStatic($method, $arguments)
    {
        return self::resolveFacade(self::$facadeName)->$method(...$arguments);
    }
}
