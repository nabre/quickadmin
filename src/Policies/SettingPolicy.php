<?php

namespace Nabre\Quickadmin\Policies;

use Nabre\Quickadmin\Models\Setting as Model;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SettingPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if(0!=$user->roles->pluck('priority')->min()){
            return false;
        }
    }

<<<<<<< HEAD
=======

>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
    function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Model $model)
    {
        return false;
    }

    public function create(User $user)
    {
        return false;
    }

    public function refresh(User $user)
    {
        return true;
    }

    public function update(User $user,  Model $model)
    {
        return true;
    }

    public function delete(User $user,  Model $model)
    {
        return false;
    }

    public function delete_force(User $user,  Model $model)
    {
        return true;
    }
}
