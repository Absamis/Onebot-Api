<?php

namespace App\Http\Controllers\Channels;

use App\Classes\ApiResponse;
use App\Http\Controllers\Controller;
use App\Interfaces\Channels\IConversationsRepository;
use App\Models\Channels\Contact;
use App\Models\User;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    //
    public $conversationRepo;
    public function __construct(IConversationsRepository $convRepo)
    {
        $this->conversationRepo = $convRepo;
    }
    public function assignUser(Request $request, Contact $contact)
    {
        $data = $request->validate([
            "userid" => ["nullable", "exists:users,id"]
        ]);
        $user = isset($data["userid"]) ? User::findOrFail($data["userid"]) : null;
        $response = $this->conversationRepo->assignUser($contact, $user);
        return ApiResponse::success("Conversation assigned", $response);
    }
}
