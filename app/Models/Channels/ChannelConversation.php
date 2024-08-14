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
        "message",
        "attachments",
        "sticker",
        "reaction",
        "status",
    ];

    protected function casts()
    {
        return [
            "messages" => "encrypted:array"
        ];
    }

    public function scopeActive(Builder $builder)
    {
        return $builder->where("saturation_status", false);
    }
}
