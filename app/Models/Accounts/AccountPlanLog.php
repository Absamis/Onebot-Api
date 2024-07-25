<?php

namespace App\Models\Accounts;

use App\Models\Account;
use App\Models\Subscriptions\SubscriptionPlan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountPlanLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'plan_id',
        'plan_mode',
        'date_joined',
        'log_type',
        'subscription_mode',
        'duration_in_days',
        'reference',
        'status'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }
}
