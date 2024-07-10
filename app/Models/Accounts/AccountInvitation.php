<?php

namespace App\Models\Accounts;

use App\Models\Account;
use App\Models\Configurations\Role;
use App\Models\User;
use App\Traits\Scopes\StatusScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountInvitation extends Model
{
    use HasFactory, StatusScope;

    protected $fillable = [
        "userid",
        "account_id",
        "email",
        "name",
        "role_id",
        "token",
        "status",
    ];

    protected $hidden = [
        "token"
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "userid");
    }

    public function account()
    {
        return $this->belongsTo(Account::class, "account_id");
    }

    public function role()
    {
        return $this->belongsTo(Role::class, "role_id");
    }
}
