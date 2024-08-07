<?php

namespace App\Models\Billings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        "id",
        "account_id",
        "transaction_type",
        "amount",
        "currency",
        "narration",
        "payment_method",
        "payment_reference",
        "payment_channel",
        "transaction_date",
        "status",
    ];
}
