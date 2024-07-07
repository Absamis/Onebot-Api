<?php

namespace App\Services\Socials;

use App\DTOs\SignupDataDto;
use App\Enums\GoogleScopesEnums;
use App\Services\BaseApiService;
use Illuminate\Support\Facades\Cache;

class GoogleService extends BaseApiService
{

    public function getLoginUrl($redirect_url)
    {

        $loginState = setLoginState("google-login-state");
        $scope = GoogleScopesEnums::loginScope;
        Cache::put(getClientIP() . "-google_rdr", $redirect_url, 300);
        $url = "https://accounts.google.com/o/oauth2/v2/auth?scope=$scope&access_type=offline&response_type=code&state=$loginState&redirect_uri=$redirect_url&client_id=$this->apiKey";
        return ["url" => $url];
    }

    public function getGoogleUserData($code): SignupDataDto
    {
        $resp = $this->getAccessToken($code);
        $this->apiToken = $resp["access_token"];
        $data = $this->getUserInfo($resp["access_token"]);
        $socialData = new SignupDataDto();
        $socialData->name = $data["name"];
        $socialData->email = $data["email"];
        $socialData->app_id = $data["id"];
        $socialData->accessToken = $resp["access_token"];
        $socialData->refreshToken = $resp["refresh_token"] ?? null;
        $socialData->tokenExpiresIn = $resp["expires_in"];
        $socialData->photo = $data["picture"] ?? null;
        return $socialData;
    }

    public function getAccessToken($code)
    {
        $resp = $this->apiRequest()->postRequest("https://oauth2.googleapis.com/token", [
            "code" => $code,
            "client_id" => $this->apiKey,
            "client_secret" => $this->apiSecret,
            "redirect_uri" => cache(getClientIP() . "-google_rdr"),
            "grant_type" => "authorization_code",
        ]);
        $resp = $resp->json() ?? [];
        $tkn = $resp["access_token"] ?? null;
        if (!$tkn)
            abort(400, "Error connection to google service. Try again", ["data" => $resp]);
        return $resp;
    }

    public function getUserInfo($token)
    {
        $resp = $this->apiRequest($token)->getRequest("/userinfo/v2/me");
        $resp = $resp->json() ?? [];
        return $resp;
    }
}
