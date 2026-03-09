<?php

namespace App\Policies;

use App\Enums\Roles;
use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    use HandlesAuthorization;
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
      return $user->role === Roles::MANAGER;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
      return $user->role === Roles::MANAGER;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
      return $user->role === Roles::MANAGER;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
      return $user->role === Roles::MANAGER;
    }

    /**
     * Determine whether the user can activate the model.
     */
    public function activate(User $user): bool
    {
      return $user->role === Roles::MANAGER;
    }

    /**
     * Determine whether the user can activate the model.
     */
    public function archive(User $user): bool
    {
     return $user->role === Roles::MANAGER;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user): bool
    {
      return $user->role === Roles::MANAGER;
    }
}
