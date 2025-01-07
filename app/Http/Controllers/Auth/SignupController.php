<?php

namespace App\Http\Controllers\Auth;

use App\Classes\ApiResponse;
use App\Enums\ChannelEnums;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Interfaces\Auth\ISocialsAuthRepository;
use App\Models\Configurations\SigninOption;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class SignupController extends Controller
{
    //

    public $signupRepo;
    public function __construct(ISocialsAuthRepository $sgn)
    {
        $this->signupRepo = $sgn;
    }

    public function signupRequest(Request $request, SigninOption $option)
    {
        $resp = $this->signupRepo->authRequest($option, $request->input("redirect_url"));
        return ApiResponse::success("Login url fetched", $resp);
    }

    public function signup(Request $request, SigninOption $option)
    {
        if ($option->code == ChannelEnums::emailChannelCode) {
            $req = app('App\Http\Requests\Auth\SignupWithEmailRequest');
            $data = $req->validated();
        } else {
            $data = $request->validate([
                "code" => ["required"],
                "state" => ["required"]
            ]);
        }
        $resp = $this->signupRepo->signup($option, $data);
        return ApiResponse::success("Signup successful", new UserResource($resp));
    }
}
