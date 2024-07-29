<?php

namespace App\Models\Subscriptions;

use App\Enums\TransactionEnums;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    public function subsInMode(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => convertDaysToMode($this->plan->subscription_mode, $this->subs_in_days)
        );
    }

    public function discountAmount(): Attribute
    {
        return Attribute::make(
            get: function () {
                $nAmt = $this->discount_value;
                if ($this->discount_type == TransactionEnums::percentageDiscountType) {
                    $amt = $this->plan->price * $this->subsInMode;
                    $nAmt = ($this->discount_value / 100) * $amt;
                    $nAmt = $amt - $nAmt;
                }
                return $nAmt;
            }
        );
    }
}
