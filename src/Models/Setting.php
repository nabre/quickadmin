<?php

namespace Nabre\Quickadmin\Models;

use App\Models\User;
use Jenssegers\Mongodb\Relations\BelongsTo;
use Nabre\Quickadmin\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'user_set',
    ];

    protected $attributes = [
        'value' => null,
        'user_set' => false,
    ];

    protected $casts = [
        'user_set' => 'boolean',
        'user_setting' => 'boolean',
    ];

    function __construct()
    {
        $this->fillable[] = config('setting.database.key');
        $this->fillable[] = config('setting.database.value');
        parent::__construct();
    }

    function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    function getTypeAttribute(){
        return config('setting.type.'.$this->key.'.type');
    }

    function getStringAttribute(): string
    {
        return (string)(__('nabre-quickadmin::setting.' . $this->key) ?? $this->key);
    }

    function getUserSettingAttribute(): bool
    {
        return data_get($this, 'user_set') || data_get($this, 'user_setting_disable');
    }

    function getUserSettingDisableAttribute()
    {
        return is_null($this->role);
    }

    function getRoleAttribute(){
        return  config('setting.type.'.$this->key.'.role');
    }

    function setUserSettingAttribute($value)
    {
        $this->attributes['user_set'] = $value;
    }
}
