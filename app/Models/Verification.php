<?php

namespace App\Models;

use App\Traits\Scopes\StatusScope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Verification extends Model
{
    use StatusScope;
    protected $fillable = [
        "userid",
        "verification_type",
        "data",
        "token",
        "refresh_token",
        "status",
        "code"
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "userid");
    }

    public function plainCode(): Attribute
    {
        return Attribute::make(
            get: function () {
                return Crypt::decrypt($this->code);
            }
        );
    }
}
