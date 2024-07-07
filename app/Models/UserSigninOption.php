<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSigninOption extends Model
{
    use HasFactory;

    protected $fillable = [
        "userid",
        "type",
        "signin_app_id",
        "name",
        "email",
        "token",
        "refresh_token",
        "photo",
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
