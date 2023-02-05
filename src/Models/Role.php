<?php

namespace Nabre\Quickadmin\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasEvents;
use Jenssegers\Mongodb\Relations\BelongsToMany;
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

    function user():BelongsToMany{
        return $this->belongsToMany(User::class,null);
    }

    function getEtiAttribute()
    {
        $ret = $this->priority;

        if (!is_null($ret)) {
            $ret .= '] ';
        }
        $ret .= empty($this->slug)?$this->name:$this->slug;

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
