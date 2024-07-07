<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        "userid",
        "type",
        "account_id",
        "narration",
        "data",
        "status"
    ];

    protected function casts()
    {
        return [
            "data" => "array"
        ];
    }
}
