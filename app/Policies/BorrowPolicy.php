<?php

namespace App\Policies;

use App\Models\Borrow;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BorrowPolicy
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
        return $user->role->hasPermission('view-borrows');

        // return $this->getPermission($user, 'view-borrows');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Borrow  $borrow
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Borrow $borrow)
    {
        return $user->role->hasPermission('view-borrows');

        // return $this->getPermission($user, 'view-borrows');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->role->hasPermission('create-borrows');

        // return $this->getPermission($user, 'create-borrows');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Borrow  $borrow
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Borrow $borrow)
    {
        return $user->role->hasPermission('update-borrows');

        // return $this->getPermission($user, 'update-borrows');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Borrow  $borrow
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Borrow $borrow)
    {
        return $user->role->hasPermission('delete-borrows');

        // return $this->getPermission($user, 'delete-borrows');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Borrow  $borrow
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Borrow $borrow)
    {
        return $user->role->hasPermission('restore-borrows');

        // return $this->getPermission($user, 'restore-borrows');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Borrow  $borrow
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Borrow $borrow)
    {
        return $user->role->hasPermission('force-delete-borrows');
        // return $this->getPermission($user, 'force-delete-borrows');
    }

    public function return(User $user, Borrow $borrow)
    {
        return $user->role->hasPermission('return-borrows');
        // return $this->getPermission($user, 'return-borrows');
    }

    public function checkBorrow(User $user, Borrow $borrow)
    {
        return $user->role->hasPermission('check-borrows');
        // return $this->getPermission($user, 'check-borrows');
    }

    // protected function getPermission($user, $pName)
    // {
    //     foreach ($user->role->permissions as $permission) {
    //         if ($permission->slug == $pName) {
    //             return true;
    //         }
    //     }
    // }
}
