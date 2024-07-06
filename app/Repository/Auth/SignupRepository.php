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

    public function signup(SigninOption $option, $redirect_url)
    {
        switch ($option->code) {
            case "fb":
                return $this->fbService->getLoginUrl($redirect_url);
                break;
            default:
                abort(400, "Signin option not available");
        }
    }
}
