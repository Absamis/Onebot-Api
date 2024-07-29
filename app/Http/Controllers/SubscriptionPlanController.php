<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponse;
use App\Http\Controllers\Controller;
use App\Interfaces\ISubscriptionPlanRepository;
use Illuminate\Http\Request;
use App\Http\Resources\SubscriptionPlanResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SubscriptionPlanController extends Controller
{
    protected $subscriptionPlanRepo;

    public function __construct(ISubscriptionPlanRepository $subscriptionPlanRepo)
    {
        $this->subscriptionPlanRepo = $subscriptionPlanRepo;
    }

    public function upgrade(Request $request)
    {
        $data = $request->validate([
            'plan_id' => ['required', 'exists:subscription_plans,id'],
        ]);
        $user = Auth::user();
        $this->subscriptionPlanRepo->upgradePlan($user, $data['plan_id']);
        return ApiResponse::success('Plan upgraded successfully.', new SubscriptionPlanResource($user));
    }

    public function downgrade(Request $request)
    {
        $data = $request->validate([
            'plan_id' => ['required', 'exists:subscription_plans,id'],
        ]);
        $user = Auth::user();
        $this->subscriptionPlanRepo->downgradePlan($user, $data['plan_id']);
        return ApiResponse::success('Plan downgraded successfully.', new SubscriptionPlanResource($user));
    }

    public function purchasePlan(Request $request)
    {
        $data = $request->validate([
            'plan_id' => ['required', 'exists:subscription_plans,id'],
            'billing_cycle_id' => ["nullable", "exists:subscription_plan_promos,id"]
        ]);
    }
}
