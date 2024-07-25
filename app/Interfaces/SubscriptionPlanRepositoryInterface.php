<?php

namespace App\Interfaces;

use App\Models\User;

interface SubscriptionPlanRepositoryInterface
{
    public function upgradePlan(User $user, int $planId);
    public function downgradePlan(User $user, int $planId);
}
