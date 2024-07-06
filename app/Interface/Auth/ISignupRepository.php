<?php

namespace App\Interface\Auth;

use App\Models\Configurations\SigninOption;

interface ISignupRepository
{
    //
    public function signupRequest(SigninOption $option, $redirect_url);
    public function signup(SigninOption $option, $data = []);
}
