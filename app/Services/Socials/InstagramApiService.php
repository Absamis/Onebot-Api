<?php

namespace App\Services\Socials;

use App\DTOs\SignupDataDto;
use App\DTOs\SocialAppCredentials;
use App\Enums\InstagramScopesEnums;
use App\Services\BaseApiService;
use Illuminate\Support\Facades\Cache;

class InstagramApiService extends BaseApiService
{
    public function getCredentials($scopes = null)
    {
        return new SocialAppCredentials(
            app_id: config("services.instagram.app_id"),
            app_key: null,
            app_secret: null,
            token: null,
            scopes: $scopes,
            state: generateLoginState(),
            url: null
        );
    }

    public function getLoginUrl($redirect_url, $force = false)
    {
        $loginState = setLoginState("ig-login-state");
        Cache::put(getClientIP() . "-ig_rdr", $redirect_url, 300);
        $scope = InstagramScopesEnums::loginScope;
        $req = $force ? "&auth_type=rerequest" : null;
        $confId = config("services.instagram.config_id");
        $url = config("services.instagram.api_url") . "/oauth/authorize?client_id=" . config("services.instagram.app_id") . "&redirect_uri=$redirect_url&state=$loginState&scope=$scope" . $req;
        return ["url" => $url];
    }


    public function getIgUserData($code): SignupDataDto
    {
        $accResp = $this->getAccessToken($code);
        $data = $this->getUserData($accResp["access_token"]);
        $socialData = new SignupDataDto();
        $socialData->name = $data["username"];
        $socialData->email = $data["email"] ?? null;
        $socialData->app_id = $data["id"];
        $socialData->accessToken = $accResp["access_token"];
        $socialData->refreshToken = $accResp["refresh_token"] ?? null;
        $socialData->tokenExpiresIn = $accResp["expires_in"];
        $socialData->photo = $data["profile_picture_url"] ?? null;
        return $socialData;
    }

    public function getLongLivedAccessToken($token)
    {
        $resp = $this->apiRequest()->postRequest("/access_token", [
            "grant_type" => "ig_exchange_token",
            "client_id" => config("services.instagram.app_id"),
            "client_secret" => config("services.instagram.app_secret"),
            "exchange_token" => $token
        ]);
        $resp = $resp->json() ?? [];
        $tkn = $resp["access_token"] ?? null;
        if (!$tkn)
            abort(400, "Error connecting to Instagram. Try again", ["data" => $resp]);
        return $resp;
    }

    public function getAccessToken($code)
    {
        $resp = $this->apiRequest()->postRequest("/access_token", [
            "client_id" => config("services.instagram.app_id"),
            "client_secret" => config("services.instagram.app_secret"),
            "redirect_uri" => trim(cache(getClientIP() . "-ig_rdr"), "/") . "/",
            "code" => $code,
            "grant_type" => "authorization_code"
        ]);
        $resp = $resp->json() ?? [];
        $tkn = $resp["access_token"] ?? null;
        if (!$tkn)
            abort(400, "Error connecting to Instagram. Try again", ["data" => $resp]);
        return $this->getLongLivedAccessToken($resp["access_token"]);
    }

    public function getUserData($accessToken)
    {
        $resp = $this->apiRequest()->getRequest("/me", [
            "access_token" => $accessToken,
            "fields" => "id,username,email,profile_picture_url"
        ]);
        $resp = $resp->json() ?? [];
        $uid = $resp["id"] ?? null;
        if (!$uid)
            abort(400, "Error getting Instagram data. Try again", ["data" => $resp]);
        return $resp;
    }

    public function getPages($accessToken)
    {
        $resp = $this->apiRequest()->getRequest("/me/accounts", [
            "access_token" => $accessToken
        ]);
        return $resp->json() ?? [];
    }

    public function getInstagramBusinessAccount($pageId, $accessToken)
    {
        $resp = $this->apiRequest()->getRequest("/$pageId", [
            "fields" => "instagram_business_account",
            "access_token" => $accessToken
        ]);
        return $resp->json() ?? [];
    }

    public function getMediaObjects($igUserId, $accessToken)
    {
        $resp = $this->apiRequest()->getRequest("/$igUserId/media", [
            "access_token" => $accessToken
        ]);
        return $resp->json() ?? [];
    }
}
