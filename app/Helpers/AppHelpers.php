<?php

use App\Enums\AppEnums;
use App\Models\AppSetting;
use App\Models\Configurations\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

if (!function_exists('getClientIP')) {
    function getClientIP()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } else if (isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = 'UNKNOWN';
        }

        return $ipaddress;
    }
}

if (!function_exists("getClientUserAgent")) {
    function getClientUserAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }
}

if (!function_exists("setLoginState")) {
    function setLoginState($suffix)
    {
        $loginState = uniqid(mt_rand(100, 999));
        Cache::put(getClientIP() . $suffix, $loginState, 300);
        return $loginState;
    }
}

if (!function_exists("verifyLoginState")) {
    function verifyLoginState($state, $suffix)
    {
        $loginState = cache(getClientIP() . $suffix);
        $loginState != $state ? abort(400, "Invalid request sent") : /*Cache::forget(getClientIP() . $suffix)*/ null;
        return true;
    }
}

if (!function_exists("pageCount")) {
    function pageCount()
    {
        return config("services.utils.pagination_count");
    }
}

if (!function_exists("getRole")) {
    function getRole($code)
    {
        return Role::where(["code" => $code])->first();
    }
}
