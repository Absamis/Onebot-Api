<?php

namespace App\Classes;

class ApiResponse
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function success($message = "", $data = [], $errors = [], $httpCode = 200)
    {
        return response()->json([
            "status" => "00",
            "message" => $message,
            "data" => $data,
            "errors" => $errors
        ], $httpCode);
    }

    public static function failed($message = "", $data = [], $errors = [], $httpCode = 200)
    {
        return response()->json([
            "status" => "99",
            "message" => $message,
            "data" => $data,
            "errors" => $errors
        ], $httpCode);
    }
}
