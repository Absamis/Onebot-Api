<?php

namespace App\Repository\Auth;

use App\DTOs\SignupDataDto;
use App\Enums\AppEnums;
use App\Enums\AccountEnums;
use App\Events\UserAccountVerified;
use App\Interfaces\Auth\ISocialsAuthRepository;
use App\Models\Configurations\SigninOption;
use App\Models\User;
use App\Models\UserSigninOption;
use App\Services\Socials\FacebookApiService;
use App\Services\Socials\GoogleService;

class SocialsAuthRepository implements ISocialsAuthRepository
{
    /**
     * Create a new class instance.
     */
    public $fbService;
    public $googleService;
    public function __construct(FacebookApiService $fbServ, GoogleService $gleService)
    {
        //
        $this->fbService = $fbServ;
        $this->googleService = $gleService;
    }

    public function authRequest(SigninOption $option, $redirect_url)
    {
        switch ($option->code) {
            case "fb":
                !$redirect_url ? abort(400, "Redirect url is required") : null;
                return $this->fbService->getLoginUrl($redirect_url, true);
                break;
            case "google":
                !$redirect_url ? abort(400, "Redirect url is required") : null;
                return $this->googleService->getLoginUrl($redirect_url);
                break;
            default:
                abort(400, "Signin option not available");
        }
    }

    public function signup(SigninOption $option, $data = [])
    {
        switch ($option->code) {
            case "fb":
                verifyLoginState($data["state"], "fb-login-state");
                $this->fbService->getFbUserData($data["code"]);
                break;
            case "google":
                verifyLoginState($data["state"], "google-login-state");
                $signupData = $this->googleService->getGoogleUserData($data["code"]);
                break;
            default:
                abort(400, "Sigin option is not available");
        }
        $check = UserSigninOption::where(["type" => $option->code, "signin_app_id" => $signupData->app_id])->first();
        if (!$check) {
            $user = $this->createNewAccount($option->code, $signupData);
            UserAccountVerified::dispatch($user);
            return $this->loginUser($user);
        } else {
            $user = $this->updateSigninDetails($check, $signupData);
            return $this->loginUser($user);
        }
    }

    private function updateSigninDetails($signinOpt, $signupData)
    {
        $signinOpt->token = $signupData->accessToken;
        $signinOpt->refresh_token = $signupData->refreshToken;
        $signinOpt->photo = $signupData->photo;
        $signinOpt->token_expires_in = $signupData->tokenExpiresIn;
        $signinOpt->save();
        return $signinOpt->user;
    }

    private function loginUser(User $user)
    {
        $token = $user->createToken(getClientIP() . $user->id . "token")->plainTextToken;
        $user->access_token = $token;
        return $user;
    }

    private function createNewAccount($type, SignupDataDto $signupData)
    {
        $user = User::create([
            "name" => $signupData->name,
            "email" => $signupData->email,
            "photo" => $signupData->photo,
            "status" => AccountEnums::verifiedAccount,
        ]);
        UserSigninOption::create([
            "userid" => $user->id,
            "type" => $type,
            "signin_app_id" => $signupData->app_id,
            "name" => $signupData->name,
            "email" => $signupData->email,
            "token" => $signupData->accessToken,
            "refresh_token" => $signupData->refreshToken,
            "photo" => $signupData->photo,
            "token_expires_in" => $signupData->tokenExpiresIn,
            "status" => AppEnums::active,
        ]);
        return $user;
    }
}
