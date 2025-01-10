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
            url: null,
            data: [
                "display" => "page",
                "extra" => "{'setup':{'channel':'IG_API_ONBOARDING'}}"
            ]
        );
    }

    public function getLoginUrl($redirect_url, $scopes = null, $force = false)
    {
        $redirect_url = trim($redirect_url, "/") . "/";
        $loginState = setLoginState("ig-login-state");
        Cache::put(getClientIP() . "-ig_rdr", $redirect_url, 300);
        $scope = $scopes ?? InstagramScopesEnums::loginScope;
        $req = $force ? "&force_authentication=1" : null;
        $url = "https://www.instagram.com/oauth/authorize?enable_fb_login=0&client_id=" . config("services.instagram.app_id") . "&response_type=code&redirect_uri=$redirect_url&state=$loginState&scope=$scope" . $req;
        return ["url" => $url, "app" => "instagram"];
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
        $response = $this->apiRequest()->asForm()->postRequest("https://api.instagram.com/oauth/access_token", [
            "client_secret" => config("services.instagram.app_secret"),
            "client_id" => config("services.instagram.app_id"),
            "redirect_uri" => cache(getClientIP() . "-ig_rdr"),
            "code" => $code,
            "grant_type" => "authorization_code"
        ]);
        $response = $response->json() ?? [];
        if (isset($response["access_token"])) {
            return $this->getLongLivedAccessToken($response["access_token"]);
        }
        abort(400, "Error connecting to Instagram. Try again", ["data" => $response]);
    }

    public function getLongLivedAccessToken($shortLivedToken)
    {
        $response = $this->apiRequest()->getRequest("/access_token", [
            "grant_type" => "ig_exchange_token",
            "client_secret" => config("services.instagram.app_secret"),
            "access_token" => $shortLivedToken
        ]);

        return $response->json() ?? [];
    }

    public function getUserData($accessToken)
    {
        $response = $this->apiRequest()->getRequest("/me", [
            "access_token" => $accessToken,
            "fields" => "id,username,profile_picture_url"
        ]);
        $resp = $response->json() ?? [];
        return $resp;
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
}
