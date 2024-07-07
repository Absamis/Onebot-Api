<?php

namespace App\Interfaces\Auth;

use App\Models\Configurations\SigninOption;

interface ISocialsAuthRepository
{
    //
    public function authRequest(SigninOption $option, $redirect_url);
    public function signup(SigninOption $option, $data = []);
    public function signin(SigninOption $option, $data = []);
}
