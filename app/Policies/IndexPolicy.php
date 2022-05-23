<?php

namespace App\Policies;

use App\Models\Index;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IndexPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
    }
    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->role->hasPermission('view-indices');
        // $this->getPermission($user, 'view-indices');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Index  $index
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Index $index)
    {
        return $user->role->hasPermission('view-indices');
        // $this->getPermission($user, 'view-indices');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->role->hasPermission('create-indices');
        // $this->getPermission($user, 'create-indices');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Index  $index
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Index $index)
    {
        return $user->role->hasPermission('update-indices');
        // $this->getPermission($user, 'update-indices');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Index  $index
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Index $index)
    {
        return $user->role->hasPermission('delete-indices');
        // $this->getPermission($user, 'delete-indices');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Index  $index
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Index $index)
    {
        return $user->role->hasPermission('restore-indices');
        // $this->getPermission($user, 'restore-indices');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Index  $index
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Index $index)
    {
        return $user->role->hasPermission('force-delete-indices');
        // $this->getPermission($user, 'force-delete-indices');
    }

    // protected function getPermission($user, $pName)
    // {
    //     foreach ($user->roles as $role) {
    //         foreach ($role->permissions as $permission) {
    //             if ($permission->slug == $pName) {
    //                 return true;
    //             }
    //         }
    //     }
    // }
}
