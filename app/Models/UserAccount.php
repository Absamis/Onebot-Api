<?php

namespace App\Models;

use App\Models\Configurations\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAccount extends Model
{
    use HasFactory;


    public function account()
    {
        return $this->belongsTo(Account::class, "account_id");
    }

    public function roles()
    {
        return $this->belongsTo(Role::class, "role_id");
    }
}
