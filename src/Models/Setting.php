<?php

namespace Nabre\Quickadmin\Models;

use App\Models\User;
use Jenssegers\Mongodb\Relations\BelongsTo;
<<<<<<< HEAD
use Jenssegers\Mongodb\Relations\BelongsToMany;
use Nabre\Quickadmin\Casts\LocalCast;
use Nabre\Quickadmin\Casts\SettingTypeCast;
=======
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
use Nabre\Quickadmin\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
<<<<<<< HEAD
        'name',
=======
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
        'user_set',
    ];

    protected $attributes = [
        'value' => null,
        'user_set' => false,
    ];

    protected $casts = [
<<<<<<< HEAD
        'type' => SettingTypeCast::class,
        'name' => LocalCast::class,
=======
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
        'user_set' => 'boolean',
        'user_setting' => 'boolean',
    ];

    function __construct()
    {
        $this->fillable[] = config('setting.database.key');
        $this->fillable[] = config('setting.database.value');
        parent::__construct();
    }

<<<<<<< HEAD
    function type(): BelongsTo
    {
        return $this->belongsTo(FormFieldType::class);
    }

=======
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
    function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

<<<<<<< HEAD
    function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    function settingGroup(): BelongsTo
    {
        return $this->belongsTo(SettingGroup::class);
=======
    function getTypeAttribute(){
        return config('setting.type.'.$this->key.'.type');
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
    }

    function getStringAttribute(): string
    {
<<<<<<< HEAD
        return (string)($this->name ?? $this->key);
=======
        return (string)(__('nabre-quickadmin::setting.' . $this->key) ?? $this->key);
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
    }

    function getUserSettingAttribute(): bool
    {
        return data_get($this, 'user_set') || data_get($this, 'user_setting_disable');
    }

    function getUserSettingDisableAttribute()
    {
<<<<<<< HEAD
        return !$this->roles->count();
=======
        return is_null($this->role);
    }

    function getRoleAttribute(){
        return  config('setting.type.'.$this->key.'.role');
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
    }

    function setUserSettingAttribute($value)
    {
        $this->attributes['user_set'] = $value;
    }
<<<<<<< HEAD

    function getShowStringAttribute()
    {
        $key = config('setting.database.key');
        return $this->$key;
    }
=======
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
}
