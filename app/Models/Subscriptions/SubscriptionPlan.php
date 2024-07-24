<?php

namespace App\Models\Subscriptions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'subscription_mode',
        'price',
        'discount_price',
        'allow_trial',
        'trial_validity',
        'grace_duration_in_days',
        'is_default',
        'status',
    ];

    public function features()
    {
        return $this->hasMany(SubscriptionPlanFeatures::class, 'plan_id');
    }
}
