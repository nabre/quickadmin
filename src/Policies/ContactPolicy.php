<?php

namespace Nabre\Quickadmin\Policies;

use Nabre\Quickadmin\Models\Contact as Model;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
    }

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
        return true;
    }

    public function update(User $user,  Model $model)
    {
        return true;
    }

    public function delete(User $user,  Model $model)
    {
        return true;
    }

    public function delete_force(User $user,  Model $model)
    {
        return true;
    }

    public function restore(User $user,  Model $model)
    {
        return true;
    }
}
