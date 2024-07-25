<?php

namespace App\Repository;

use App\Models\Subscriptions\SubscriptionPlan;
use App\Models\User;
use App\Interfaces\ISubscriptionPlanRepository;

class SubscriptionPlanRepository implements ISubscriptionPlanRepository
{
    public function upgradePlan(User $user, int $planId): ?SubscriptionPlan
    {
        $plan = SubscriptionPlan::findOrFail($planId);
        $user->plan_id = $plan->id;
        $user->plan_mode = 'live';
        $user->plan_date_joined = now();
        $user->plan_duration_in_days = $plan->trial_validity;
        $user->plan_expiring_date = now()->addDays($plan->trial_validity);
        $user->save();
        return $plan;
    }

    public function downgradePlan(User $user, int $planId): ?SubscriptionPlan
    {
        $plan = SubscriptionPlan::findOrFail($planId);
        $user->plan_id = $plan->id;
        $user->plan_mode = 'live';
        $user->plan_date_joined = now();
        $user->plan_duration_in_days = $plan->trial_validity;
        $user->plan_expiring_date = now()->addDays($plan->trial_validity);
        $user->save();
        return $plan;
    }
}
