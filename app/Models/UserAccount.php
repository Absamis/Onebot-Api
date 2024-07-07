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
        return $this->hasOne(Account::class, "account_id");
    }

    public function role()
    {
        return $this->hasOne(Role::class, "role_id");
    }
}
