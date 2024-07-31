<?php

namespace App\Services\Socials;

use App\DTOs\SignupDataDto;
use App\DTOs\SocialAppCredentials;
use App\Services\BaseApiService;
use Illuminate\Support\Facades\Cache;

class WhatsAppApiService extends BaseApiService
{
    public function getCredentials($scopes = null)
    {
        return new SocialAppCredentials(
            app_id: config("services.whatsapp.app_id"),
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
        $redirect_url = trim($redirect_url, "/") . "/";
        $loginState = setLoginState("wa-login-state");
        Cache::put(getClientIP() . "-wa_rdr", $redirect_url, 300);
        $req = $force ? "&auth_type=rerequest" : null;
        $confId = config("services.whatsapp.config_id");
        $url = "https://api.whatsapp.com/oauth/authorize?client_id=$this->apiKey&redirect_uri=$redirect_url&state=$loginState&config_id=$confId" . $req;
        return ["url" => $url, "app" => "whatsapp"];
    }

    public function getWaUserData($code): SignupDataDto
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
    }

    private function getAccessToken($code)
    {
        $response = $this->apiRequest()->getRequest("/oauth/access_token", [
            "client_id" => $this->apiKey,
            "client_secret" => $this->apiSecret,
            "redirect_uri" => cache(getClientIP() . "-wa_rdr"),
            "code" => $code
        ]);
        $response = $response->json() ?? [];
        if (isset($response["access_token"])) {
            return $this->getLongLivedAccessToken($response["access_token"]);
        }
        abort(400, "Error connecting to WhatsApp. Try again", ["data" => $response]);
    }

    private function getLongLivedAccessToken($shortLivedToken)
    {
        $response = $this->apiRequest()->getRequest("/access_token", [
            "grant_type" => "wa_exchange_token",
            "client_secret" => $this->apiSecret,
            "access_token" => $shortLivedToken
        ]);

        return $response->json() ?? [];
    }

    private function getUserData($accessToken)
    {
        $response = $this->apiRequest()->getRequest("/me", [
            "access_token" => $accessToken,
            "fields" => "id,name,email"
        ]);
        $resp = $response->json() ?? [];
        if (!$resp["id"]) {
            abort(400, "Error getting WhatsApp data. Try again", ["data" => $resp]);
        }
        return $resp;
    }
}
