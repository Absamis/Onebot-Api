<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponse;
use App\Http\Controllers\Controller;
use App\Interfaces\ISubscriptionPlanRepository;
use Illuminate\Http\Request;
use App\Http\Resources\SubscriptionPlanResource;
use App\Models\Account;
use App\Models\Subscriptions\SubscriptionPlan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SubscriptionPlanController extends Controller
{
    protected $subscriptionPlanRepo;

    public function __construct(ISubscriptionPlanRepository $subscriptionPlanRepo)
    {
        $this->subscriptionPlanRepo = $subscriptionPlanRepo;
    }


    public function purchasePlan(Account $account, Request $request)
    {
        $data = $request->validate([
            'plan_id' => ['required', 'exists:subscription_plans,id'],
            "plan_duration" => ["nullable", "numeric"],
            'billing_cycle_id' => ["nullable", "exists:subscription_plan_promos,id"],
            "payment_method" => ["required"]
        ]);
        $response = $this->subscriptionPlanRepo->purchasePlan($data);
        return ApiResponse::success("Plan purchase initiated", $response);
    }

    public function getPlans()
    {
        $data = SubscriptionPlan::with(["features" => function ($query) {
            $query->with("feature");
        }, "promos"])->get();
        return ApiResponse::success("Plan fetched", $data);
    }
}
