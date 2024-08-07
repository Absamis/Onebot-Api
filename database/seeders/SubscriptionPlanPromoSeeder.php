<?php

namespace Database\Seeders;

use App\Models\Subscriptions\SubscriptionPlanPromo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionPlanPromoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        SubscriptionPlanPromo::create([
            'plan_id' => 2,
            'subs_in_days' => 400,
            'discount_type' => "percentage",
            'discount_value' => 10
        ]);
    }
}
