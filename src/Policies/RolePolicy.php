<?php

namespace Nabre\Quickadmin\Policies;

use App\Models\Role as Model;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function before(?User $user, $ability)
    {
        return true;
    }

    function viewAny(?User $user)
    {
        return true;
    }

    public function view(?User $user, Model $model)
    {
        return false;
    }

    public function create(?User $user)
    {
        return true;
    }

    public function refresh(User $user)
    {
        return true;
    }

    public function update(?User $user,  Model $model)
    {
        return true;
    }

    public function delete(?User $user,  Model $model)
    {
        return $model->destroy_enabled;
    }

    public function delete_force(?User $user,  Model $model)
    {
        return true;
    }
}
