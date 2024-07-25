<?php

namespace App\Models\Subscriptions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlanPromo extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'subs_in_days',
        'discount_type',
        'discount_value',
        'status',
    ];

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }
}
