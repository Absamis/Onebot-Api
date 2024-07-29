<?php

use App\Enums\AppEnums;
use App\Enums\SubscriptionEnums;
use App\Models\Configurations\AppSetting;
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

if (!function_exists("generateLoginState")) {
    function generateLoginState()
    {
        $loginState = uniqid(mt_rand(100, 999));
        return $loginState;
    }
}

if (!function_exists("setLoginState")) {
    function setLoginState($suffix)
    {
        $loginState = generateLoginState();
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

if (!function_exists('convertModeToDays')) {
    function convertModeToDays($mode, $value)
    {
        switch ($mode) {
            case SubscriptionEnums::dailyMode:
                return floor($value);
            case SubscriptionEnums::weeklyMode:
                return floor($value * 7);
            case SubscriptionEnums::monthlyMode:
                return floor($value * 30);
            case SubscriptionEnums::yearlyMode:
                return floor($value * 365);
            default:
                return 1;
        }
    }
}

if (!function_exists('convertDaysToMode')) {
    function convertDaysToMode($mode, $value)
    {
        switch ($mode) {
            case SubscriptionEnums::dailyMode:
                return $value;
            case SubscriptionEnums::weeklyMode:
                return ($value / 7);
            case SubscriptionEnums::monthlyMode:
                return ($value / 30);
            case SubscriptionEnums::yearlyMode:
                return ($value / 365);
            default:
                return 1;
        }
    }
}


if (!function_exists("appSettings")) {
    function appSettings()
    {
        $data = Cache::remember("app-settings", 3600, function () {
            return AppSetting::query()->first() ?? new AppSetting();
        });
        return $data;
    }
}
