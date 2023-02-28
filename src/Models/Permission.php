<?php

namespace Nabre\Quickadmin\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasEvents;
use Jenssegers\Mongodb\Relations\BelongsToMany;
use Jenssegers\Mongodb\Relations\HasMany;
use Maklad\Permission\Contracts\PermissionInterface;
use Maklad\Permission\Models\Permission as Original;
use Nabre\Quickadmin\Casts\LocalCast;
use Nabre\Quickadmin\Database\Eloquent\RelationshipsTrait;
use Nabre\Quickadmin\Database\Eloquent\RecursiveSaveTrait;

class Permission extends Original
{
    use RelationshipsTrait;
    use RecursiveSaveTrait;
    use HasEvents;

    protected $fillable = [
        'name',
        'slug',
        'route_used',
        'guard_name',
    ];
    protected $attributes = [
        'guard_name' => 'web',
        'route_used' => false,
    ];

<<<<<<< HEAD
    protected $casts=[
        'slug'=> LocalCast::class,
=======
    protected $casts = [
        'slug' => LocalCast::class,
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
        'route_used' => 'boolean',
    ];


    function contact(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class, null);
    }

    function getEtiAttribute()
    {
        if (empty($this->slug)) {
            return $this->name;
        }
        return $this->slug;
    }

    public static function findByName(string $name, string $guardName = null): PermissionInterface
    {
        $guardName = $guardName ?? (new Guard())->getDefaultName(static::class);

        $permission = static::getPermissions()->filter(function ($permission) use ($name, $guardName) {
            return $permission->name === $name && $permission->guard_name === $guardName;
        })->first();

        if (!$permission) {
            self::firstOrCreate(['name' => $name, 'guard_name' => $guardName]);
            $permission = self::findByName($name, $guardName);
        }

        return $permission;
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
