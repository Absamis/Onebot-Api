<?php

namespace App\DTOs;

class SocialsDataDto
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
    public function __construct()
    {
        //
    }
}
