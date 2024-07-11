<?php

namespace App\Repository\Auth;

use App\Enums\AccountEnums;
use App\Enums\AppEnums;
use App\Interfaces\Auth\IVerificationRepository;
use App\Models\Verification;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


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

        Log::info('Generated unencrypted verification code', [
            'user_id' => $user->id,
            'verification_type' => $type,
            'unencrypted_code' => $code,
        ]);

        $encCode = Crypt::encrypt($code);
        return $this->setVerificationData($user, $type, $encCode, $data);
    }

    public function setVerificationData(User $user, $type, $code = null, $data = null)
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
            "data" => $data,
            "status" => AppEnums::active
        ]);
        return $vrfData;
    }



    public function verifyToken($token, $type, $invalidate = false)
    {
        // Ensure we are only fetching active tokens
        $vrf = Verification::where([
            'token' => $token,
            'verification_type' => $type,
            'status' => AppEnums::active
        ])->first();

        if ($invalidate && $vrf) {
            $vrf = $this->invalidateToken($vrf);
        }
        return $vrf;
    }

    public function verifyResendToken($token)
    {
        $vrf = Verification::where([
            'refresh_token' => $token,
            'status' => AppEnums::active
        ])->first();
        return $vrf;
    }

    public function verifyCode($code, $token)
    {
        $vrf = $this->verifyToken($token, AccountEnums::emailChangeVerificationType);

        if (!$vrf) {
            abort(400, "Invalid token");
        }

        if ($code != $vrf->plainCode) {
            abort(400, "Incorrect code");
        }
        $vrf = $this->invalidateToken($vrf);
        return $vrf;
    }

    public function verifyEmailChangeCode($code)
    {
        $vrf = Verification::active()->where(["userid" => auth()->user()->id, "verification_type" => AccountEnums::emailChangeVerificationType])->first();
        if (!$vrf)
            abort(400, "Invalid request");
        if ($code != $vrf->plainCode)
            abort(400, "Incorrect code");
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
        if (!$vrf) {
            abort(400, "Invalid request");
        }
        $newVrf = $this->getVerificationCode($vrf->user, $vrf->verification_type, $vrf->data);
        return $newVrf;
    }
}
