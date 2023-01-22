<?php

namespace Nabre\Quickadmin\Casts;

use Nabre\Quickadmin\Repositories\LocalizationRepositorie;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class LocalCast implements CastsAttributes {

    public function get($model, $key, $value, $attributes)
    {
        $locale=new LocalizationRepositorie;
        return $locale->string($value);
    }

    public function set($model, $key, $value, $attributes)
    {
        $value=array_filter((array)$value, 'strlen');

        if(!count($value)){
            $value=null;
        }

        return [$key => $value];
    }
}
