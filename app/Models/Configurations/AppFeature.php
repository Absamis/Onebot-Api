<?php

namespace App\Models\Configurations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppFeature extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'status'];
}
