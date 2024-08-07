<?php

namespace App\Repository\Auth;

use App\DTOs\SignupDataDto;
use App\Enums\AppEnums;
use App\Enums\AccountEnums;
use App\Enums\ActivityLogEnums;
use App\Enums\ChannelEnums;
use App\Events\UserAccountVerified;
use App\Interfaces\Auth\ISocialsAuthRepository;
use App\Models\Configurations\SigninOption;
use App\Models\User;
use App\Models\UserSigninOption;
use App\Services\Socials\FacebookApiService;
use App\Services\Socials\GoogleService;
use App\Services\Socials\InstagramApiService;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

class SocialsAuthRepository implements ISocialsAuthRepository
{
    /**
     * Create a new class instance.
     */
    public $fbService;
    public $googleService;
    public $igService;
    public function __construct(FacebookApiService $fbServ, GoogleService $gleService, InstagramApiService $igServ)
    {
        $this->fbService = $fbServ;
        $this->googleService = $gleService;
        $this->igService = $igServ;
    }

    public function authRequest(SigninOption $option, $redirect_url)
    {
        !$redirect_url ? abort(400, "Redirect url is required") : null;
        switch ($option->code) {
            case ChannelEnums::facebookChannelCode:
                return $this->fbService->getLoginUrl($redirect_url, true);
            case ChannelEnums::googleChannelCode:
                return $this->googleService->getLoginUrl($redirect_url);
            case "ig":
                return $this->igService->getLoginUrl($redirect_url, true);
            default:
                abort(400, "Signin option not available");
        }
    }

    private function authorize($code, $data)
    {
        switch ($code) {
            case ChannelEnums::facebookChannelCode:
                verifyLoginState($data["state"], "fb-login-state");
                $signupData = $this->fbService->getFbUserData($data["code"]);
                break;
            case ChannelEnums::googleChannelCode:
                verifyLoginState($data["state"], "google-login-state");
                $signupData = $this->googleService->getGoogleUserData($data["code"]);
                break;
            case "instagram":
                verifyLoginState($data["state"], "ig-login-state");
                $signupData = $this->igService->getIgUserData($data["code"]);
                break;
            default:
                abort(400, "Signin option is not available");
        }
        return $signupData;
    }

    public function signup(SigninOption $option, $data = [])
    {
        $signupData = $this->authorize($option->code, $data);
        $checkUser = User::where("email", $signupData->email)->first();
        $checkUser ? abort(400, "Account with this email already exists") : null;
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

    public function signin(SigninOption $option, $data = [])
    {
        $signupData = $this->authorize($option->code, $data);
        $check = UserSigninOption::where(["type" => $option->code, "signin_app_id" => $signupData->app_id])->first();
        if (!$check) {
            return abort(400, "Signin option is not registered yet. Kindly create an account");
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
        Auth::login($user);
        UserService::logActivity(ActivityLogEnums::userSignin, [
            "location" => getClientIP(),
            "time" => now()->toDateTime()
        ]);
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
