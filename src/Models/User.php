<?php

namespace Nabre\Quickadmin\Models;

use Illuminate\Support\Facades\Session;
use Illuminate\Notifications\Notifiable;
use Jenssegers\Mongodb\Relations\HasOne;
use Nabre\Quickadmin\Casts\PasswordCast;
use Jenssegers\Mongodb\Relations\HasMany;
use Jenssegers\Mongodb\Auth\User as JUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Nabre\Quickadmin\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Concerns\HasEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Nabre\Quickadmin\Database\Eloquent\RecursiveSaveTrait;
use Nabre\Quickadmin\Database\Eloquent\RelationshipsTrait;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends JUser implements AuthenticatableContract, AuthorizableContract, MustVerifyEmail
{
    use HasRoles;
    use HasFactory, Authorizable, Notifiable;
    use RelationshipsTrait;
    use RecursiveSaveTrait;
    use HasEvents;

    protected $fillable = [
        'name',
        'email',
        'password',
        'disabled',
        'email_verified_at',
        'lang',
        'api_token', //bin2hex(openssl_random_pseudo_bytes(30)) //str_random(60);
    ];

    protected $attributes = [
        'disabled' => false,
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'name' => 'string',
        'disabled' => 'boolean',
        'enabled' => 'boolean',
        'password' => PasswordCast::class,
    ];

    protected $dates = ['email_verified_at'];
    function contact(): HasOne
    {
        return $this->hasOne(Contact::class, 'account_id');
    }

    function settings(): HasMany
    {
        return $this->hasMany(Setting::class);
    }

    function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    function getLvlRoleAttribute()
    {
        return $this->roles()->get()->min("priority") ?? null;
    }

    function getActiveAttribute()
    {
        return !is_null($this->password) && $this->verified_email && $this->enabled;
    }

    function getVerifiedEmailAttribute()
    {
        return !is_null($this->email_verified_at);
    }

    function setEnabledAttribute($value)
    {
        $this->attributes['disabled'] = !$value;
    }

    function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst($value);
    }

    function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    function getEnabledAttribute()
    {
        return !$this->disabled;
    }

    function getAccessibleAttribute()
    {
        return $this->enabled && $this->password && $this->email_verified_at;
    }

    function getLocaleAttribute()
    {
        return data_get($this->settings()->where(config('setting.database.key'), 'app_locale')->first(), config('setting.database.value'));
    }

    #impersonate
    public function setImpersonating($id)
    {
        Session::put('impersonate', $id);
        return $this;
    }

    public function stopImpersonating()
    {
        Session::forget('impersonate');

        return $this;
    }

    public function isImpersonating()
    {
        return Session::has('impersonate');
    }
}
