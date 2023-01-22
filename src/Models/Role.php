<?php

namespace Nabre\Quickadmin\Models;

use Illuminate\Database\Eloquent\Concerns\HasEvents;
use Maklad\Permission\Models\Role as Original;
use Nabre\Quickadmin\Casts\LocalCast;
use Nabre\Quickadmin\Database\Eloquent\RelationshipsTrait;
use Nabre\Quickadmin\Database\Eloquent\RecursiveSaveTrait;

class Role extends Original
{
    use RelationshipsTrait;
    use RecursiveSaveTrait;
    use HasEvents;

    protected $fillable = [
        'name',
        'slug',
        'guard_name',
    ];
    protected $attributes = [
        'guard_name' => 'web',
    ];

    protected $casts = [
        'slug' => LocalCast::class,
    ];

    function getEtiAttribute()
    {
        $ret = $this->priority;

        if (!is_null($ret)) {
            $ret .= '] ';
        }

        if (empty($this->slug)) {
            $ret .= $this->name;
        } else {
            $ret .= $this->slug;
        }

        return $ret;
    }

    function getShowStringAttribute()
    {
        return $this->name;
    }
}
