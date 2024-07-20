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
        $url = "https://www.facebook.com/v20.0/dialog/oauth?client_id=" . config("services.instagram.app_id") . "&redirect_uri=$redirect_url&state=$loginState&scope=$scope" . $req;
        return ["url" => $url];
    }

    public function getUserPages($accessToken)
    {
        $response = $this->apiRequest()->getRequest("/me/accounts", [
            "access_token" => $accessToken,
            "fields" => "id,name,access_token,instagram_business_account"
        ]);

        return $response->json() ?? [];
    }

    public function getInstagramBusinessAccount($pageId, $accessToken)
    {
        $response = $this->apiRequest()->getRequest("/$pageId", [
            "fields" => "instagram_business_account",
            "access_token" => $accessToken
        ]);

        return $response->json() ?? [];
    }

    public function getMediaObjects($igUserId, $accessToken)
    {
        $response = $this->apiRequest()->getRequest("/$igUserId/media", [
            "access_token" => $accessToken
        ]);

        return $response->json() ?? [];
    }

    public function getAccessToken($code)
    {
        $response = $this->apiRequest()->postRequest("/oauth/access_token", [
            "client_id" => config("services.instagram.app_id"),
            "client_secret" => config("services.instagram.app_secret"),
            "redirect_uri" => trim(cache(getClientIP() . "-ig_rdr"), "/") . "/",
            "code" => $code,
            "grant_type" => "authorization_code"
        ]);

        return $response->json() ?? [];
    }

    public function getLongLivedAccessToken($shortLivedToken)
    {
        $response = $this->apiRequest()->postRequest("/access_token", [
            "grant_type" => "ig_exchange_token",
            "client_id" => config("services.instagram.app_id"),
            "client_secret" => config("services.instagram.app_secret"),
            "exchange_token" => $shortLivedToken
        ]);

        return $response->json() ?? [];
    }
}
