<?php

namespace App\Services\Socials;

use App\DTOs\SignupDataDto;
use App\DTOs\SocialAppCredentials;
use App\Enums\FacebookScopesEnums;
use App\Services\BaseApiService;
use Illuminate\Support\Facades\Cache;

class FacebookApiService extends BaseApiService
{

    public function getCredentials($scopes = null)
    {
        return new SocialAppCredentials(
            app_id: config("services.facebook.app_id"),
            app_key: null,
            app_secret: null,
            token: null,
            scopes: $scopes,
            state: generateLoginState(),
            url: null
        );
    }

    public function getLoginUrl($redirect_url, $scopes = null, $force = false)
    {
        $redirect_url = trim($redirect_url, "/") . "/";
        $loginState = setLoginState("fb-login-state");
        Cache::put(getClientIP() . "-fb_rdr", $redirect_url, 300);
        $scope = $scope ?? FacebookScopesEnums::loginScope;
        $req = $force ? "&auth_type=rerequest"  : null;
        $confId = config("services.facebook.config_id");
        $url = "https://www.facebook.com/v20.0/dialog/oauth?client_id=$this->apiKey&redirect_uri=$redirect_url&state=$loginState&config_id=$confId&scope=$scope" . $req;
        return ["url" => $url, "app" => "facebook"];
    }

    public function getFbUserData($code): SignupDataDto
    {
        $accResp = $this->getAccessToken($code);
        $data = $this->getUserData($accResp["access_token"]);
        $socialData = new SignupDataDto();
        $socialData->name = $data["name"];
        $socialData->email = $data["email"] ?? null;
        $socialData->app_id = $data["id"];
        $socialData->accessToken = $accResp["access_token"];
        $socialData->refreshToken = $accResp["refresh_token"] ?? null;
        $socialData->tokenExpiresIn = $accResp["expires_in"];
        $socialData->photo = $data["photo"] ?? null;
        return $socialData;
        // return $uData;
    }


    public function getFbPages($code)
    {
        $accResp = $this->getAccessToken($code);
        $data = $this->getPagesData($accResp["access_token"]);
        return $data;
    }

    public function getLongLivedAccessToken($token)
    {
        $resp = $this->apiRequest()->getRequest("/oauth/access_token", [
            "grant_type" => "fb_exchange_token",
            "client_id" => $this->apiKey,
            "client_secret" => $this->apiSecret,
            "fb_exchange_token" => $token
        ]);
        $resp = $resp->json() ?? [];
        $tkn = $resp["access_token"] ?? null;
        if (!$tkn)
            abort(400, "Error connecting to facebook. Try again", ["data" => $resp]);
        return $resp;
    }

    public function getAccessToken($code)
    {
        $resp = $this->apiRequest()->getRequest("/oauth/access_token", [
            "client_id" => $this->apiKey,
            "client_secret" => $this->apiSecret,
            "redirect_uri" => cache(getClientIP() . "-fb_rdr"),
            "code" => $code
        ]);
        $resp = $resp->json() ?? [];
        $tkn = $resp["access_token"] ?? null;
        if (!$tkn)
            abort(400, "Error connecting to facebook. Try again", ["data" => $resp]);
        return $this->getLongLivedAccessToken($resp["access_token"]);
    }

    public function getUserData($accessToken)
    {
        $resp = $this->apiRequest()->getRequest("/me", [
            "access_token" => $accessToken,
            "fields" => "id,name,email"
        ]);
        $resp = $resp->json() ?? [];
        $uid = $resp["id"] ?? null;
        if (!$uid)
            abort(400, "Error getting facebook data. Try again", ["data" => $resp]);
        return $resp;
    }

    public function getPagesData($accessToken)
    {
        $uData = $this->getUserData($accessToken);
        $uid = $uData["id"];
        $resp = $this->apiRequest()->getRequest("/$uid/accounts", [
            "access_token" => $accessToken,
            "fields" => "id,access_token,category,name,picture"
        ]);
        $resp = $resp->json() ?? [];
        if (!$resp)
            abort(400, "Error getting facebook data. Try again", ["data" => $resp]);
        return $resp;
    }
}
