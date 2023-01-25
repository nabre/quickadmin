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
        'route_used',
        'guard_name',
        'priority',
    ];
    protected $attributes = [
        'guard_name' => 'web',
        'route_used' => false,
    ];

    protected $casts = [
        'slug' => LocalCast::class,
        'route_used' => 'boolean',
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

    function getDestroyEnabledAttribute()
    {
        $count = 0;
        $this->definedRelations()->pluck('name')->each(function ($name) use (&$count) {
            $count += $this->$name()->get()->count();
        });
        return !$this->route_used && !$count;
    }
}
