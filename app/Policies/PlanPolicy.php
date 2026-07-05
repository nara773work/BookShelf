<?php

namespace App\Policies;
use App\Models\ReadingPlan;
use App\Models\User;

class PlanPolicy
{
    /**
     * Create a new policy instance.
     */
    public function view(User $user,Plan $plan): bool
    {
       return $user->id === $plan->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->id === $plan->user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Plan $plan): bool
    {
        return $user->id === $plan->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Plan $plan): bool
    {
        return $user->id === $plan->user_id;
    }
}
