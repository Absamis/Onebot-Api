<?php

namespace App\Models\Configurations;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SigninOption extends Model
{
    use HasFactory;

    protected $table = 'signin_options';

    protected $fillable = [];
}
