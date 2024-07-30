<?php

namespace App\Interfaces;

use App\Models\Account;

interface ISubscriptionPlanRepository
{
    public function downgradePlan(Account $account);
    public function purchasePlan($data);
    public function startTrial(Account $account, int $planId);
    public function processPlanPurchase(Account $account, $transRef);
}
