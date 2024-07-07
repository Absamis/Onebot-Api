<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "name",
        "email",
        "password",
        "phone",
        "photo",
        "status",
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function photo(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (preg_match("/^(https:\/\/|http:\/\/)/", $value))
                    return $value;
                return Storage::disk("upl")->url($value);
            }
        );
    }

    public function accounts()
    {
        return $this->hasMany(UserAccount::class, "userid");
    }

    public function signin_options()
    {
        return $this->hasMany(UserSigninOption::class, "userid");
    }
}
