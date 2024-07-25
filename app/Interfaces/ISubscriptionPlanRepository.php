<?php

namespace App\Interfaces;

use App\Models\User;

interface ISubscriptionPlanRepository
{
    public function upgradePlan(User $user, int $planId);
    public function downgradePlan(User $user, int $planId);
}
