<?php

namespace App\Interface\Auth;

use App\Models\Configurations\SigninOption;

interface ISignupRepository
{
    //
    public function signup(SigninOption $option, $redirect_url);
}
