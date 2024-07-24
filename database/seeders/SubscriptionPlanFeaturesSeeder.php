<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subscriptions\SubscriptionPlanFeatures;


class SubscriptionPlanFeaturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SubscriptionPlanFeatures::create([
            'plan_id' => 1,
            'feature_id' => 1,
            'value' => '10 GB',
            'description' => 'Storage limit',
            'status' => 1,
        ]);

        SubscriptionPlanFeatures::create([
            'plan_id' => 2,
            'feature_id' => 1,
            'value' => '100 GB',
            'description' => 'Storage limit',
            'status' => 1,
        ]);
    }
}
