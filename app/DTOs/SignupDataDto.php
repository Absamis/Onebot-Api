<?php

namespace App\DTOs;

use App\Enums\AccountEnums;

class SignupDataDto
{
    /**
     * Create a new class instance.
     */
    public $name;
    public $email;
    public $app_id;
    public $accessToken;
    public $refreshToken;
    public $tokenExpiresIn;
    public $type;
    public $photo;
    public $status = AccountEnums::verifiedAccount;
    public function __construct()
    {
        //
    }
}
