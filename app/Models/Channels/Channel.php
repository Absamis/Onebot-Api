<?php

namespace App\Models\Channels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;


    protected $fillable = [];

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
}
