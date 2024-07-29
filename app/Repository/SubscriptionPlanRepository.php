<?php

namespace App\Repository;

use App\DTOs\PayGatewayData;
use App\Models\Subscriptions\SubscriptionPlan;
use App\Models\Account;
use App\Models\Subscriptions\SubscriptionPlanPromo;
use App\Interfaces\ISubscriptionPlanRepository;
use App\Enums\AppEnums;
use App\Enums\SubscriptionEnums;
use App\Enums\TransactionEnums;
use App\Services\PayGateways\StripePaymentService;
use App\Services\TransactionService;
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
        if ($subsPlan->plan_id == Auth::account()->plan_id)
            $purType = SubscriptionEnums::planRenewalCode;
        $narr = SubscriptionEnums::narrations[$purType];
        $init = $this->initiatePaymentCheckout($amount, $payMethod, $purType, $narr);

        Auth::account()->plan_logs()->create([
            "plan_id" => $subsPlan->id,
            "plan_mode" => SubscriptionEnums::liveMode,
            "date_joined" => now()->toDate(),
            "log_type" => $purType,
            "subscription_mode" => $subsPlan->subscription_mode,
            "duration_in_days" => $duration,
            "reference" => $init["data"]["ref"],
            "status" => AppEnums::active
        ]);
        return $init;
    }

    private function initiatePaymentCheckout($amount, $payMethod, $transType, $narration = null)
    {
        $transRef = TransactionService::generateTransactionId();
        $payUrl = $payRef = null;
        switch ($payMethod) {
            case "stripe":
                $rdr = $_GET["redirect_url"] ?? null;
                if (!$rdr)
                    abort(400, "Redirect url is required");
                $payData = [
                    "name" => $transType,
                    "amount" => $amount
                ];
                $stripeService = new StripePaymentService();
                $resp = $stripeService->InitCheckOut($transRef, $payData);
                $payUrl = $resp["url"];
                break;
            default:
                abort(400, "Payment method is not available");
        }

        Auth::account()->transactions()->create([
            "id" => $transRef,
            "transaction_type" => $transType,
            "amount" => $amount,
            "currency" => appSettings()->currency_code,
            "narration" => $narration,
            "payment_method" => $payMethod,
            "payment_reference" => $payRef,
            "transaction_date" => now()->toDateTime(),
            "status" => TransactionEnums::pendingStatus
        ]);
        return new PayGatewayData(
            url: $payUrl,
            reference: $transRef
        );
    }

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
