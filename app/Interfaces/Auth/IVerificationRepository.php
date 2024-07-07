<?php

namespace App\Interfaces\Auth;

use App\Models\User;

interface IVerificationRepository
{
    //
    public function getVerificationCode(User $user, $type = null);
    public function verifyToken($token, $invalidate = false);
    public function verifyCode($code, $token);
    public function resendVerificationCode($token);
    public function verifyResendToken($token);
    public function setVerificationData(User $user, $type,  $code = null, $data = null);
}
