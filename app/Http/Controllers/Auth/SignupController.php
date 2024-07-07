<?php

namespace App\Http\Controllers\Auth;

use App\Classes\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Interface\Auth\ISignupRepository;
use App\Models\Configurations\SigninOption;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class SignupController extends Controller
{
    //

    public $signupRepo;
    public function __construct(ISignupRepository $sgn)
    {
        $this->signupRepo = $sgn;
    }

    public function signupRequest(Request $request, SigninOption $option)
    {
        $resp = $this->signupRepo->signupRequest($option, $request->input("redirect_url"));
        return ApiResponse::success("Login url fetched", $resp);
    }

    public function signup(Request $request, SigninOption $option)
    {
        $data = $request->validate([
            "code" => ["required"],
            "state" => ["required"]
        ]);
        $resp = $this->signupRepo->signup($option, $data);
        return ApiResponse::success("Signup successful", new UserResource($resp));
    }
}
