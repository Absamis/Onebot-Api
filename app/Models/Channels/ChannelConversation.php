<?php

namespace App\Models\Channels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChannelConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        "account_id",
        "channel_id",
        "assigned_to",
        "messages",
        "sub_conversation",
        "assigned_status",
        "status"
    ];
}
