<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionPlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'plan_id' => $this->plan_id,
            'plan_mode' => $this->plan_mode,
            'plan_date_joined' => $this->plan_date_joined,
            'plan_duration_in_days' => $this->plan_duration_in_days,
            'plan_expiring_date' => $this->plan_expiring_date,
        ];
    }
}
