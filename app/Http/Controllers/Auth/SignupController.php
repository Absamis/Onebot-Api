<?php

namespace App\Http\Controllers\Auth;

use App\Classes\ApiResponse;
use App\Http\Controllers\Controller;
use App\Interface\Auth\ISignupRepository;
use App\Models\Configurations\SigninOption;
use Illuminate\Http\Request;

class SignupController extends Controller
{
    //

    public $signupRepo;
    public function __construct(ISignupRepository $sgn)
    {
        $this->signupRepo = $sgn;
    }

    public function signup(Request $request, SigninOption $option)
    {
        !$request->has("redirect_url") ? abort(400, "Redirect url is required") : null;
        $resp = $this->signupRepo->signup($option, $request->input("redirect_url"));
        return ApiResponse::success("Login url fetched", $resp);
    }
}
