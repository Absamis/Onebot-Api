<?php

namespace App\Repository\Auth;

use App\Enums\AccountEnums;
use App\Enums\AppEnums;
use App\Interfaces\Auth\IVerificationRepository;
use App\Models\Verification;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class VerificationRepository implements IVerificationRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getVerificationCode(User $user, $type = null, $data = null)
    {
        $code = mt_rand(1000, 9999);
        $encCode = Crypt::encrypt($code);
        return $this->setVerificationData($user, $type, $encCode, $data);
    }

    public function setVerificationData(User $user, $type,  $code = null, $data = null)
    {
        $token = md5(Str::uuid());
        $refToken = md5(uniqid());
        $vrfData = Verification::updateOrCreate([
            "userid" => $user->id,
            "verification_type" => $type
        ], [
            "token" => $token,
            "refresh_token" => $refToken,
            "code" => $code,
            "data" => $data
        ]);
        return $vrfData;
    }

    public function verifyToken($token, $invalidate = false)
    {
        $vrf = Verification::active()->where(["token" => $token])->first();
        if ($invalidate && $vrf)
            $vrf = $this->invalidateToken($vrf);
        return $vrf;
    }

    public function verifyResendToken($token)
    {
        $vrf = Verification::active()->where(["refresh_token" => $token])->first();
        return $vrf;
    }

    public function verifyCode($code, $token)
    {
        $vrf = $this->verifyToken($token, true);
        if (!$vrf)
            abort(400, "Invalid request");
        if ($code != $vrf->plainCode)
            abort(400, "Incorrect code");
        //$vrf = $this->invalidateToken($vrf);
        return $vrf;
    }

    public function verifyEmailChangeCode($code)
    {
        $vrf = Verification::active()->where(["userid" => auth()->user()->id, "verification_type" => AccountEnums::emailChangeVerificationType])->first();
        if (!$vrf)
            abort(400, "Invalid request");
        if ($code != $vrf->plainCode)
            abort(400, "Incorrect code");
        //$vrf = $this->invalidateToken($vrf);
        return $vrf;
    }

    private function invalidateToken($vrf)
    {
        $vrf->status = AppEnums::inactive;
        $vrf->token = null;
        $vrf->refresh_token = null;
        $vrf->save();
        return $vrf;
    }

    public function resendVerificationCode($token)
    {
        $vrf = $this->verifyResendToken($token);
        if (!$vrf)
            abort(400, "Invalid request");
        $dt = $this->getVerificationCode($vrf->user, $vrf->verification_type);
        return $dt;
    }
}
