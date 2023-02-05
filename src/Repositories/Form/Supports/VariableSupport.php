<?php

namespace Nabre\Quickadmin\Repositories\Form\Supports;

trait VariableSupport
{
    private static $create = 'post';
    private static $update = 'put';

    function array()
    {
        return get_object_vars($this);
    }

    function set($key, $value, $overwrite = true)
    {
        $value = $this->callFn($value);

        $key = is_array($key) ? $key : explode('.', $key);
        $var = collect($key)->reverse()->take(1)->implode('.');
        $find = collect($key)->reverse()->skip(1)->reverse()->implode('.');
        if (empty($find)) {
            $find = $key;
            $set = $value;
        } else {
            $set = (array)data_get($this, $find, []);
            data_set($set, $var, $value, $overwrite);
            $overwrite = true;
        }

        if (method_exists($this, 'removeRequiredProp')) {
            $this->removeRequiredProp($key);
        }

        data_set($this, $find, $set, $overwrite);
        return $set;
    }

    function get($key, $default = null)
    {
        return data_get($this, $key, $default);
    }

    private function callFn(&$fn,...$args)
    {
        if (isFunction($fn)) {
            $fn = $fn($this->getData(),...$args);
            return $fn;
        }
        return $fn;
    }
}
