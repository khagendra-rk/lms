<?php

namespace App\Policies;

use App\Models\Faculty;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FacultyPolicy
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
        return $user->role->hasPermission('view-faculties');

        // return $this->getPermission($user, 'view-faculties');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Faculty  $faculty
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Faculty $faculty)
    {
        return $user->role->hasPermission('view-faculties');
        // return $this->getPermission($user, 'view-faculties');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->role->hasPermission('create-faculties');
        // return $this->getPermission($user, 'create-faculties');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Faculty  $faculty
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Faculty $faculty)
    {
        return $user->role->hasPermission('update-faculties');
        // return $this->getPermission($user, 'update-faculties');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Faculty  $faculty
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Faculty $faculty)
    {
        return $user->role->hasPermission('delete-faculties');
        // return $this->getPermission($user, 'delete-faculties');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Faculty  $faculty
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Faculty $faculty)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Faculty  $faculty
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Faculty $faculty)
    {
        return false;
    }

    // protected function getPermission($user, $pName)
    // {

    //     foreach ($user->role->permissions as $permission) {
    //         if ($permission->slug == $pName)
    //             return true;
    //     }
    //     return false;
    // }
}
