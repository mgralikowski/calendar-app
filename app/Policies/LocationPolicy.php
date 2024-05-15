<?php

namespace App\Policies;

use App\Models\Location;
use App\Models\User;

class LocationPolicy
{
    /**
     * Determine whether the user can view any location.
     */
    public function viewAny(): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the location.
     */
    public function view(): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create locations.
     */
    public function create(): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the location.
     */
    public function update(User $user, Location $location): bool
    {
        return $user->is($location->manager);
    }

    /**
     * Determine whether the user can delete the location.
     */
    public function delete(User $user, Location $location): bool
    {
        return $this->update($user, $location);
    }

    /**
     * Determine whether the user can restore the location.
     */
    public function restore(User $user, Location $location): bool
    {
        return $this->update($user, $location);
    }

    /**
     * Determine whether the user can permanently delete the location.
     */
    public function forceDelete(User $user, Location $location): bool
    {
        return $this->update($user, $location);
    }
}
