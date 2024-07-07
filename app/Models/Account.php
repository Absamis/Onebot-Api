<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        "userid",
        "name",
        "type",
        "photo",
        "description",
        "account_app_id",
        "token",
        "refresh_token",
        "token_expires_in",
        "status",
    ];

    protected $hidden = [
        "token",
        "refresh_token",
        "token_expires_in"
    ];

    protected function casts()
    {
        return [
            "token" => "encrypted:object",
            "refresh_token" => "encrypted:object",
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, "userid");
    }

}
