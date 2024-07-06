<?php

namespace App\Repository\Auth;

use App\Interface\Auth\ISignupRepository;
use App\Models\Configurations\SigninOption;
use App\Services\Socials\FacebookApiService;

class SignupRepository implements ISignupRepository
{
    /**
     * Create a new class instance.
     */
    public $fbService;
    public function __construct(FacebookApiService $fbServ)
    {
        //
        $this->fbService = $fbServ;
    }

    public function signupRequest(SigninOption $option, $redirect_url)
    {
        switch ($option->code) {
            case "fb":
                !$redirect_url ? abort(400, "Redirect url is required") : null;
                return $this->fbService->getLoginUrl($redirect_url);
                break;
            default:
                abort(400, "Signin option not available");
        }
    }

    public function signup(SigninOption $option, $data = [])
    {
        switch ($option->code) {
            case "fb":
                $code = $data["code"] ?? abort(400, "Code is required");
                $this->fbService->getFbUserData($code);
                break;
        }
    }
}
