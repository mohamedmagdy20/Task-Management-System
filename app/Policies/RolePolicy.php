<?php

namespace App\Policies;

use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RolePolicy
{
    public function viewAny(User $user)
    {
        //
        return $user->hasPermissionTo('view-any Role');

    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Role $role)
    {
        //
        return $user->hasPermissionTo('view Role');

    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        //
        return $user->hasPermissionTo('create Role');

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Role $role)
    {
        //
        return $user->hasPermissionTo('update Role');

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Role $role)
    {
        //
        return $user->hasPermissionTo('delete Role');

    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Role $role)
    {
        //
        return $user->hasPermissionTo('restore Role');

    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Role $role)
    {
        //
        return $user->hasPermissionTo('force-delete Role');

    }
}
