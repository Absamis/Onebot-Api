<?php

namespace App\Services;

use App\Enums\ActivityLogEnums;
use App\Models\ActivityLog;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

class UserService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function logActivity($type, $data = null)
    {
        try {
            ActivityLog::create([
                "userid" => auth()->user()->id,
                "type" => $type,
                "account_id" => Auth::account()->id ?? null,
                "narration" => ActivityLogEnums::logMessages[$type],
                "data" => $data,
            ]);
        } catch (Exception $ex) {
        }
    }

    public static function getUser($uid)
    {
        return User::where("email", $uid)->orWhere("id", $uid)->first();
    }
}
