<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponse;
use App\Interfaces\ISubscriptionPlanRepository;
use Illuminate\Http\Request;
use App\Http\Resources\SubscriptionPlanResource;
use App\Models\Account;
use App\Models\Subscriptions\SubscriptionPlan;

class SubscriptionPlanController extends Controller
{
    protected $subscriptionPlanRepo;

    public function __construct(ISubscriptionPlanRepository $subscriptionPlanRepo)
    {
        $this->subscriptionPlanRepo = $subscriptionPlanRepo;
    }

    public function upgrade(Request $request, Account $account)
    {
        $data = $request->validate([
            'plan_id' => ['required', 'exists:subscription_plans,id'],
        ]);
        $this->subscriptionPlanRepo->upgradePlan($account, $data['plan_id']);
        return ApiResponse::success('Plan upgraded successfully.', new SubscriptionPlanResource($account));
    }

    public function downgrade(Request $request, Account $account)
    {
        $this->subscriptionPlanRepo->downgradePlan($account);
        return ApiResponse::success('Plan downgraded successfully.', new SubscriptionPlanResource($account));
    }

    public function trial(Request $request, Account $account)
    {
        $data = $request->validate([
            'plan_id' => ['required', 'exists:subscription_plans,id'],
        ]);
        $this->subscriptionPlanRepo->startTrial($account, $data['plan_id']);
        return ApiResponse::success('Trial started successfully.', new SubscriptionPlanResource($account));
    }

    public function getPlans()
    {
        $plans = SubscriptionPlan::all();
        return ApiResponse::success('Subscription plans retrieved successfully.', $plans);
    }

    public function getPlanFeatures($planId)
    {
        $plan = SubscriptionPlan::findOrFail($planId);
        return ApiResponse::success('Subscription plan features retrieved successfully.', $plan->features);
    }
}
