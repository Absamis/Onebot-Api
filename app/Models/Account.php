<?php

namespace App\Models;

use App\Models\Accounts\AccountPlanLog;
use App\Models\Billings\Transaction;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        "userid",
        "name",
        "category",
        "company_url",
        "description",
        "type",
        "plan_id",
        "plan_mode",
        "plan_date_joined",
        "plan_duration_in_days",
        "plan_expiring_date",
        "trial_status",
        "status"
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "userid");
    }

    public function members()
    {
        return $this->hasMany(UserAccount::class, "account_id");
    }

    public function owner(): Attribute
    {
        return Attribute::get(function () {
            return $this->userid == (auth()->user()->id ?? null);
        });
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, "account_id");
    }

    public function plan_logs()
    {
        return $this->hasMany(AccountPlanLog::class, "account_id");
    }
}
