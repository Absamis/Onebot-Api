<?php

namespace App\Http\Controllers\Channels;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TelegramChannelController extends Controller
{
    //

    public function webhook(Request $request)
    {
        $data = $request->all();
        $hookId = $data["update_id"];
        $hookType = $data[1];
    }
}
