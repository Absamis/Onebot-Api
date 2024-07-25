<?php

namespace App\Interfaces;

use App\Models\Account;

interface ISubscriptionPlanRepository
{
    public function upgradePlan(Account $account, int $planId);
    public function downgradePlan(Account $account);
    public function startTrial(Account $account, int $planId);
}
