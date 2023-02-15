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

<<<<<<< HEAD
    function user():BelongsToMany{
        return $this->belongsToMany(User::class,null);
=======
    function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class, null);
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
    }

    function getEtiAttribute()
    {
        $ret = $this->priority;

        if (!is_null($ret)) {
            $ret .= '] ';
        }
<<<<<<< HEAD
        $ret .= empty($this->slug)?$this->name:$this->slug;
=======
        $ret .= empty($this->slug) ? $this->name : $this->slug;
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870

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
