<?php

namespace App\Policies;

use App\User;
use App\Proposal;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProposalPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can list all proposals.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->hasPermissionTo('list proposals');
    }

    /**
     * Determine whether the user can create proposals.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return ($user->hasPermissionTo('create proposals'));
    }

    /**
     * Determine whether the user can update the proposal.
     *
     * @param  \App\User  $user
     * @param  \App\Proposal  $proposal
     * @return mixed
     */
    public function update(User $user, Proposal $proposal)
    {
        return ($user->id === $proposal->user_id && $user->hasPermissionTo('edit proposals')) || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can trash the proposal.
     *
     * @param  \App\User  $user
     * @param  \App\Proposal  $proposal
     * @return mixed
     */
    public function trash(User $user, Proposal $proposal)
    {
        return true;
        return ($user->id === $proposal->user_id && $user->hasPermissionTo('trash proposals')) || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can trash the proposal.
     *
     * @param  \App\User  $user
     * @param  \App\Proposal  $proposal
     * @return mixed
     */
    public function restore(User $user, Proposal $proposal)
    {
        return ($user->id === $proposal->user_id && $user->hasPermissionTo('trash proposals')) || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the proposal.
     *
     * @param  \App\User  $user
     * @param  \App\Proposal  $proposal
     * @return mixed
     */
    public function delete(User $user, Proposal $proposal)
    {
        return ($user->id === $proposal->user_id && $user->hasPermissionTo('delete proposals')) || $user->hasRole('admin');
    }
}
