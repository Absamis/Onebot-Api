<?php

namespace App\Repository;

use App\DTOs\PayGatewayData;
use App\Enums\ActivityLogEnums;
use App\Models\Subscriptions\SubscriptionPlan;
use App\Models\Account;
use App\Models\Subscriptions\SubscriptionPlanPromo;
use App\Interfaces\ISubscriptionPlanRepository;
use App\Enums\AppEnums;
use App\Enums\SubscriptionEnums;
use App\Enums\TransactionEnums;
use App\Services\PayGateways\StripePaymentService;
use App\Services\TransactionService;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

class SubscriptionPlanRepository implements ISubscriptionPlanRepository
{

    public function purchasePlan($data)
    {
        $plan_id = $data["plan_id"];
        $payMethod = $data["payment_method"];
        $plan_dur = $data["plan_duration"] ?? 1;
        $cycle_id = $data["billing_cycle_id"] ?? null;
        $subsPlan = SubscriptionPlan::findOrFail($plan_id);
        if ($cycle_id) {
            $cycle = $subsPlan->promos()->findOrFail($cycle_id);
            $amount = $cycle->discountAmount;
            $duration = $cycle->subs_in_days;
        } else {
            $amount = $subsPlan->price;
            $duration = convertModeToDays($subsPlan->subscription_mode, $plan_dur);
        }
        $payUrl = null;

        $purType = SubscriptionEnums::planPurchaseCode;
        if ($subsPlan->plan_id == Auth::account()->plan_id && Auth::account()->plan_mode == SubscriptionEnums::liveMode)
            $purType = SubscriptionEnums::planRenewalCode;
        $narr = SubscriptionEnums::narrations[$purType];
        $init = TransactionService::initiateTransaction($amount, $payMethod, $purType, $narr);
        $pl = Auth::account()->plan_logs()->create([
            "plan_id" => $subsPlan->id,
            "plan_mode" => SubscriptionEnums::liveMode,
            "date_joined" => now()->toDate(),
            "log_type" => $purType,
            "subscription_mode" => $subsPlan->subscription_mode,
            "duration_in_days" => $duration,
            "reference" => $init->reference,
            "status" => AppEnums::inactive
        ]);
        UserService::logActivity(ActivityLogEnums::subscribedPlan, [
            "plan_log_id" => $pl->id,
            "transaction_id" => $init->reference
        ]);
        return $init;
    }

    public function processPlanPurchase(Account $account, $transRef)
    {
        $planDet = $account->plan_logs()->where(["reference" => $transRef, "status" => AppEnums::inactive])->first();
        if (!$planDet)
            abort(200, "No active plan purchase initiated");
        $plLog = $this->updateAccountPlan($account, $planDet);
        //Send purchase Notification
        return $plLog;
    }

    private function updateAccountPlan($account, $planLog)
    {
        $daysLeft = 0;
        $prevDueDate = strtotime($account->plan_expiring_date);
        if ($prevDueDate > time()) {
            $daysLeft = round(($prevDueDate - time()) / 60 / 60 / 24);
        }
        $plDur = $planLog->duration_in_days;
        $plDurMode = convertDaysToMode($planLog->plan->subscription_mode, $planLog->duration_in_days);
        if ($planLog->log_type == SubscriptionEnums::planRenewalCode) {
            $dtJoined = date("Y-m-d", strtotime($$account->plan_expiring_date . " +1 day"));
            $newDur = $plDur + $daysLeft;
            $account->plan_date_joined = $dtJoined;
            $account->plan_duration_in_days = $newDur;
            $account->plan_expiring_date = date("Y-m-d", strtotime($dtJoined . " +$newDur days"));
            $account->save();
        } else {
            $dtJoined = date("Y-m-d");
            $account->plan_date_joined = $dtJoined;
            $account->plan_mode = $planLog->plan_mode;
            $account->plan_id = $planLog->plan_id;
            $account->plan_duration_in_days = $plDur;
            $account->plan_expiring_date = $plDur ? date("Y-m-d", strtotime("+$planLog->duration_in_days days")) : null;
            $account->save();
        }
        $planLog->status = AppEnums::active;
        $planLog->save();
        return $planLog;
    }

    public function downgradePlan(Account $account): ?SubscriptionPlan
    {
        $subsPlan = SubscriptionPlan::where('is_default', true)->firstOrFail();
        $pl = Auth::account()->plan_logs()->create([
            "plan_id" => $subsPlan->id,
            "plan_mode" => SubscriptionEnums::liveMode,
            "date_joined" => now()->toDate(),
            "log_type" => SubscriptionEnums::planDowngradeCode,
            "subscription_mode" => $subsPlan->subscription_mode,
            "duration_in_days" => null,
            "status" => AppEnums::active
        ]);

        $plg = $this->updateAccountPlan($account, $pl);
        return $plg;
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
}
