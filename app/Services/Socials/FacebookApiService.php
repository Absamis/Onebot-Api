<?php

namespace App\Services\Socials;

use App\Enums\Enums\FacebookScopesEnums;
use App\Services\BaseApiService;
use Illuminate\Support\Facades\Cache;

class FacebookApiService extends BaseApiService
{

    public function getLoginUrl($redirect_url, $force = false)
    {
        $loginState = uniqid(mt_rand(100, 999));
        Cache::put(getClientIP() . "-fb_login_state", $loginState, 300);
        Cache::put(getClientIP() . "-fb_rdr", $redirect_url, 300);
        $scope = FacebookScopesEnums::loginScope;
        $req = $force ? "&auth_type=rerequest"  : null;
        $url = "https://www.facebook.com/v20.0/dialog/oauth?client_id=$this->apiKey&redirect_uri=$redirect_url&state=$loginState" . $req;
        return ["url" => $url];
    }
}
