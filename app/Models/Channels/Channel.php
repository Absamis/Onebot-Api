<?php

namespace App\Models\Channels;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;


    protected $fillable = [
        "account_id",
        "name",
        "type",
        "photo",
        "description",
        "channel_app_id",
        "token",
        "refresh_token",
        "token_expires_in",
        "permissions",
        "status",
    ];

    protected $hidden = ["token", "refresh_token", "permissions"];

    protected function casts()
    {
        return [
            "token" => "encrypted:object",
            "refresh_token" => "encrypted:object",
        ];
    }

    public function account()
    {
        return $this->belongsTo(Account::class, "account_id");
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class, "channel_id");
    }
}
