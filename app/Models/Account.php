<?php

namespace App\Models;

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
}
