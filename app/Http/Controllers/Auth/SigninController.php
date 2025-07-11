<?php

namespace App\Http\Controllers\Auth;

use App\Classes\ApiResponse;
use App\Enums\ChannelEnums;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Interfaces\Auth\ISocialsAuthRepository;
use App\Models\Configurations\SigninOption;
use Illuminate\Http\Request;

class SigninController extends Controller
{
    //
    public $loginRepo;
    public function __construct(ISocialsAuthRepository $sgn)
    {
        $this->loginRepo = $sgn;
    }

    public function signin(Request $request, SigninOption $option)
    {
        if ($option->code == ChannelEnums::emailChannelCode) {
            $req = app('App\Http\Requests\Auth\LoginRequest');
            $data = $req->validated();
        } else {
            $data = $request->validate([
                "code" => ["required"],
                "state" => ["required"]
            ]);
        }
        $resp = $this->loginRepo->signin($option, $data);
        return ApiResponse::success("Signin successful", new UserResource($resp));
    }
}
