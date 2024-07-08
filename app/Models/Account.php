<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        "userid",
        "name",
        "category",
        "company_url",
        "description",
        "type",
        "status"
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "userid");
    }
}
