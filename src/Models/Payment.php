<?php

namespace Nabre\Quickadmin\Models;

use App\Models\User;
use Jenssegers\Mongodb\Relations\BelongsTo;
use Nabre\Quickadmin\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'payment_id',
        'payer_id',
        'payer_email',
        'amount',
        'currency',
        'payment_status',
    ];
    /*
    protected $attributes = [
        'type' => Field::TEXT,
    ];*/

    protected $casts=['amount'=>'decimal:2'];


    function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
