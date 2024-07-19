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
        Storage::put("fbhook", json_encode($request->all()));
    }
}
