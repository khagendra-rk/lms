<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeacherPolicy
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
        return $user->role->hasPermission('view-teachers');
        // return $this->getPermission($user, 'view-teachers');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Teacher $teacher)
    {
        return $user->role->hasPermission('view-teachers');
        // return $this->getPermission($user, 'view-teachers');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->role->hasPermission('create-teachers');
        // return $this->getPermission($user, 'create-teachers');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Teacher $teacher)
    {
        return $user->role->hasPermission('update-teachers');
        // return $this->getPermission($user, 'update-teachers');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Teacher $teacher)
    {
        return $user->role->hasPermission('delete-teachers');
        // return $this->getPermission($user, 'delete-teachers');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Teacher $teacher)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Teacher $teacher)
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
