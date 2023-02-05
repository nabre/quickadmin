<?php

namespace Nabre\Quickadmin\Models;

use Nabre\Quickadmin\Casts\LocalCast;
use Nabre\Quickadmin\Database\Eloquent\Model;

class FormFieldType extends Model
{
    protected $fillable = [
        'name',
        'key'
    ];
/*
    protected $attributes = [
        'type' => Field::TEXT,
    ];*/

    protected $casts = [
        'name' => LocalCast::class,
    ];

    function getStringAttribute()
    {
        return ucfirst($this->name ?? $this->key);
    }

    function getShowStringAttribute()
    {
        return $this->key;
    }
}
