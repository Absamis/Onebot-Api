<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subscriptions\SubscriptionPlan;


class SubscriptionPlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SubscriptionPlan::create([
            'name' => 'Basic Plan',
            'description' => 'A basic subscription plan',
            'slug' => 'basic-plan',
            'subscription_mode' => 'monthly',
            'price' => 9.99,
            'discount_price' => 8.99,
            'allow_trial' => true,
            'trial_validity' => 30,
            'grace_duration_in_days' => 7,
            'is_default' => true,
            'status' => 1,
        ]);

        SubscriptionPlan::create([
            'name' => 'Premium Plan',
            'description' => 'A premium subscription plan',
            'slug' => 'premium-plan',
            'subscription_mode' => 'yearly',
            'price' => 99.99,
            'discount_price' => 89.99,
            'allow_trial' => true,
            'trial_validity' => 30,
            'grace_duration_in_days' => 7,
            'is_default' => false,
            'status' => 1,
        ]);
    }
}
