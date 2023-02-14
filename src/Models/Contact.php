<?php

namespace Nabre\Quickadmin\Models;

use App\Models\User;
use Jenssegers\Mongodb\Relations\BelongsTo;
use Nabre\Quickadmin\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'email',
        'firstname',
        'lastname',
    ];
    /*
    protected $attributes = [
        'type' => Field::TEXT,
    ];

    protected $casts=[];
    */
    function getFullNameAttribute()
    {
        return data_get($this, 'lastname') . ' ' . data_get($this, 'firstname');
    }

    function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }

    function account(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    function getAccountBoolAttribute(){
        return (bool) $this->account;
    }
}
