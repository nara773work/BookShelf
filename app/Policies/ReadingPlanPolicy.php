<?php

namespace App\Policies;

use App\Models\ReadingPlan;
use App\Models\User;

class ReadingPlanPolicy
{
    /**
     * Create a new policy instance.
     */
    public function view(User $user, ReadingPlan $readingPlans): bool
    {
        return $user->id === $readingPlans->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ReadingPlan $readingPlans): bool
    {
        return $user->id === $readingPlans->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ReadingPlan $readingPlan): bool
    {
        return $user->id === $readingPlan->user_id;
    }
}
