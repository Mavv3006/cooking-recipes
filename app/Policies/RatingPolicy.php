<?php

namespace App\Policies;

use App\Models\Rating;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RatingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Rating $rating
     * @return bool
     */
    public function view(User $user, Rating $rating): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Rating $rating
     * @return bool
     */
    public function update(User $user, Rating $rating): bool
    {
        return $rating->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Rating $rating
     * @return bool
     */
    public function delete(User $user, Rating $rating): bool
    {
        return $rating->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Rating $rating
     * @return bool
     */
    public function restore(User $user, Rating $rating): bool
    {
        return $rating->user_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Rating $rating
     * @return bool
     */
    public function forceDelete(User $user, Rating $rating): bool
    {
        return $rating->user_id === $user->id;
    }
}
