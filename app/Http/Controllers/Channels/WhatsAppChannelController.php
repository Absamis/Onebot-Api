<?php

namespace App\Http\Controllers\Channels;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WhatsAppChannelController extends Controller
{
    public function webhook(Request $request)
    {
        Storage::put("waHook", json_encode($request->all()));
    }
}
