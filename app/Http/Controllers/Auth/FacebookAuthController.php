<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Socials\FacebookApiService;
use Illuminate\Http\Request;

class FacebookAuthController extends Controller
{
    //
    public $fbService;
    public function __construct(FacebookApiService $fbs)
    {
        $this->fbService = $fbs;
    }
    public function validateLogin(Request $request)
    {
        $resp = $this->fbService->getAccessToken($request->input("code"));
        return $resp;
    }
}
