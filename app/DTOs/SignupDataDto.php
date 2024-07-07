<?php

namespace App\DTOs;

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
    public function __construct()
    {
        //
    }
}
