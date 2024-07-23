<?php

namespace App\Models\Channels;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        "account_id",
        "channel_id",
        "name",
        "contact_app_id",
        "token",
        "email",
        "phone",
        "photo",
        "gender",
        "locale",
        "contact_app_type",
        "conversation_assigned_to",
        "conversation_status",
        "status",
    ];

    protected $hidden = [
        "token"
    ];

    public function conversations()
    {
        return $this->hasMany(ChannelConversation::class, "contact_id");
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class, "channel_id");
    }

    public function conversation_user()
    {
        return $this->belongsTo(User::class, "conversation_assigned_to");
    }

    public function account()
    {
        return $this->belongsTo(Account::class, "account_id");
    }
}
