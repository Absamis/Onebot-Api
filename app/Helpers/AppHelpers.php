<?php

use App\Enums\AppEnums;
use App\Models\AppSetting;
use Illuminate\Support\Facades\Cache;

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

function getClientUserAgent()
{
    return $_SERVER['HTTP_USER_AGENT'];
}

// function appSettings()
// {
//     $settings = Cache::remember("app-settings", 3600, function () {
//         return AppSetting::where("status", AppEnums::active)->first();
//     });
//     return $settings;
// }

function pageCount()
{
    return config("services.utils.pagination_count");
}
