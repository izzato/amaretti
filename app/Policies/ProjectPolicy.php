<?php

namespace App\Policies;

use App\User;
use App\Project;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can list all projects.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->hasPermissionTo('list projects');
    }

    /**
     * Determine whether the user can create projects.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return ($user->hasPermissionTo('create projects'));
    }

    /**
     * Determine whether the user can update the project.
     *
     * @param  \App\User  $user
     * @param  \App\Project  $project
     * @return mixed
     */
    public function update(User $user, Project $project)
    {
        return ($user->id === $project->user_id && $user->hasPermissionTo('edit projects')) || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can trash the project.
     *
     * @param  \App\User  $user
     * @param  \App\Project  $project
     * @return mixed
     */
    public function trash(User $user, Project $project)
    {
        return ($user->id === $project->user_id && $user->hasPermissionTo('trash projects')) || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can trash the project.
     *
     * @param  \App\User  $user
     * @param  \App\Project  $project
     * @return mixed
     */
    public function restore(User $user, Project $project)
    {
        return ($user->id === $project->user_id && $user->hasPermissionTo('trash projects')) || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the project.
     *
     * @param  \App\User  $user
     * @param  \App\Project  $project
     * @return mixed
     */
    public function delete(User $user, Project $project)
    {
        return ($user->id === $project->user_id && $user->hasPermissionTo('delete projects')) || $user->hasRole('admin');
    }
}
