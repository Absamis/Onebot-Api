<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    protected $fillable = [
        'userid', 'verification_type', 'token', 'refresh_token', 'code', 'data'
    ];
}
