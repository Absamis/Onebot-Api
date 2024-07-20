<?php

namespace App\Models\Channels;

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
}
