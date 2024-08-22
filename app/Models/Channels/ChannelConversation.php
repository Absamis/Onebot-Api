<?php

namespace App\Models\Channels;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChannelConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        "contact_id",
        "admin_id",
        "reply_to_id",
        "message",
        "attachments",
        "date",
        "time",
        "sticker",
        "reaction",
        "status",
    ];

    protected function casts()
    {
        return [
            // "messages" => "encrypted:array"
            "attachments" => "array"
        ];
    }

    public function scopeActive(Builder $builder) {
    }
}
