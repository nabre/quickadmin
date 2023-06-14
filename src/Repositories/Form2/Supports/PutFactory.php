<?php

namespace Nabre\Quickadmin\Repositories\Form2\Supports;

class PutFactory
{
    protected $builder;

    function __construct($builder, $method, $args)
    {
        if (!method_exists($this, $method)) {
            return;
        }
        $this->builder = $builder;
        return $this->$method(...$args);
    }
}
