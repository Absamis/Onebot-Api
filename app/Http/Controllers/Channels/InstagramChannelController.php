<?php

namespace App\Http\Controllers\Channels;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstagramChannelController extends Controller
{
    public function webhook(Request $request)
    {
        Storage::put("igHook", json_encode($request->all()));
    }
}
