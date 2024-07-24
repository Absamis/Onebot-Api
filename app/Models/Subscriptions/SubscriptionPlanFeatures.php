<?php

namespace App\Models\Subscriptions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Configuration\AppFeature;

class SubscriptionPlanFeatures extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'feature_id',
        'value',
        'description',
        'status',
    ];

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function feature()
    {
        return $this->belongsTo(AppFeature::class, 'feature_id');
    }
}
