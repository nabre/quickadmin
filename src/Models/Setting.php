<?php

namespace Nabre\Quickadmin\Models;

use App\Models\User;
use Jenssegers\Mongodb\Relations\BelongsTo;
use Jenssegers\Mongodb\Relations\BelongsToMany;
use Nabre\Quickadmin\Casts\LocalCast;
use Nabre\Quickadmin\Casts\SettingTypeCast;
use Nabre\Quickadmin\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'name',
        'user_set',
    ];

    protected $attributes = [
        'value' => null,
        'user_set' => false,
    ];

    protected $casts = [
        'type' => SettingTypeCast::class,
        'name' => LocalCast::class,
        'user_set' => 'boolean',
        'user_setting' => 'boolean',
    ];

    function __construct()
    {
        $this->fillable[] = config('setting.database.key');
        $this->fillable[] = config('setting.database.value');
        parent::__construct();
    }

    function type(): BelongsTo
    {
        return $this->belongsTo(FormFieldType::class);
    }

    function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    function settingGroup(): BelongsTo
    {
        return $this->belongsTo(SettingGroup::class);
    }

    function getStringAttribute(): string
    {
        return (string)($this->name ?? $this->key);
    }

    function getUserSettingAttribute(): bool
    {
        return data_get($this, 'user_set') || data_get($this, 'user_setting_disable');
    }

    function getUserSettingDisableAttribute()
    {
        return !$this->roles->count();
    }

    function setUserSettingAttribute($value)
    {
        $this->attributes['user_set'] = $value;
    }

    function getShowStringAttribute()
    {
        $key = config('setting.database.key');
        return $this->$key;
    }
}
