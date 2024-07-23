<?php

namespace App\Http\Controllers\Channels;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FacebookChannelController extends Controller
{
    //
    public function webhook(Request $request)
    {
        $mode = $request->input("hub_mode");
        $verifyToken = $request->input("hub_verify_token");
        $challenge = $request->input("hub_challenge");

        if ($mode != "subscribe")
        abort(400, "Invalid mode");
        if ($verifyToken != config("services.facebook.webhook_verify_token"))
        abort(400, "Invalid verify token");
        return $challenge;
    }
}
