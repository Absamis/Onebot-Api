<?php

namespace App\Repository;

use App\Models\Subscriptions\SubscriptionPlan;
use App\Models\Account;
use App\Models\Subscriptions\SubscriptionPlanPromo;
use App\Interfaces\ISubscriptionPlanRepository;
use App\Enums\AppEnums;


class SubscriptionPlanRepository implements ISubscriptionPlanRepository
{
    public function upgradePlan(Account $account, int $planId): ?SubscriptionPlan
    {
        $plan = SubscriptionPlan::findOrFail($planId);
        $promo = $this->getActivePromo($planId);

        if ($promo) {
            $account->plan_duration_in_days = $promo->subs_in_days;
            $account->plan_expiring_date = now()->addDays($promo->subs_in_days);
        } else {
            $duration = $this->getPlanDurationInDays($plan);
            $account->plan_duration_in_days = $duration;
            $account->plan_expiring_date = now()->addDays($duration);
        }
        $account->plan_id = $plan->id;
        $account->plan_mode = 'live';
        $account->plan_date_joined = now();
        $account->save();
        return $plan;
    }

    public function downgradePlan(Account $account): ?SubscriptionPlan
    {
        $plan = SubscriptionPlan::where('is_default', true)->firstOrFail();
        $account->plan_id = $plan->id;
        $account->plan_mode = 'live';
        $account->plan_date_joined = now();
        $duration = $this->getPlanDurationInDays($plan);
        $account->plan_duration_in_days = $duration;
        $account->plan_expiring_date = now()->addDays($duration);
        $account->trial_status = false;
        $account->save();
        return $plan;
    }

    public function startTrial(Account $account, int $planId): ?SubscriptionPlan
    {
        if ($account->trial_status) {
            abort(403, 'User has already used a trial.');
        }

        $plan = SubscriptionPlan::findOrFail($planId);
        if (!$plan->allow_trial) {
            abort(403, 'This plan does not allow a trial.');
        }
        $account->plan_id = $plan->id;
        $account->plan_mode = 'trial';
        $account->plan_date_joined = now();
        $account->plan_duration_in_days = $plan->trial_validity;
        $account->plan_expiring_date = now()->addDays($plan->trial_validity);
        $account->trial_status = true;
        $account->save();
        return $plan;
    }

    protected function getActivePromo(int $planId): ?SubscriptionPlanPromo
    {
        return SubscriptionPlanPromo::where('plan_id', $planId)
            ->where('status', AppEnums::active)
            ->first();
    }

    protected function getPlanDurationInDays(SubscriptionPlan $plan): int
    {
        switch ($plan->subscription_mode) {
            case 'monthly':
                return 30;
            case 'yearly':
                return 365;
            default:
                throw new \InvalidArgumentException("Unknown plan mode: {$plan->mode}");
        }
    }
}
