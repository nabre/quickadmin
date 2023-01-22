<?php

namespace Nabre\Quickadmin\Database\Eloquent;

use Illuminate\Database\Eloquent\Concerns\HasEvents;
use Jenssegers\Mongodb\Eloquent\Model as JModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
//use Jenssegers\Mongodb\Eloquent\HybridRelations; #Per relazioni ibride tra SQL & MONGO
use Nabre\Quickadmin\Database\Eloquent\RelationshipsTrait;


class Model extends JModel
{
    use HasFactory;
    use RelationshipsTrait;
    use RecursiveSaveTrait;
    use HasEvents;
    use AuthorizesRequests;

    protected $guard_name = 'web';
    protected $dates = ['created_at', 'updated_at'];
    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s', 'updated_at' => 'datetime:Y-m-d H:i:s'];
    protected $guarded = ['_id'];

    function delete()
    {
        $this->authorize('delete', $this);
        return parent::delete();
    }
}
