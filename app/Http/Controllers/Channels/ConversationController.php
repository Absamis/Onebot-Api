<?php

namespace App\Http\Controllers\Channels;

use App\Classes\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Channels\SendChatMessageRequest;
use App\Http\Resources\Channels\ContactResource;
use App\Interfaces\Channels\IConversationsRepository;
use App\Models\Account;
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

    public function getContact(Account $account, Contact $contact)
    {
        return ApiResponse::success("Contact sent", new ContactResource($contact));
    }
    public function assignUser(Request $request, Account $account, Contact $contact)
    {
        $data = $request->validate([
            "userid" => ["nullable", "exists:users,id"]
        ]);
        $user = isset($data["userid"]) ? User::findOrFail($data["userid"]) : null;
        $response = $this->conversationRepo->assignUser($contact, $user);
        return ApiResponse::success("Conversation assigned", new ContactResource($response));
    }

    public function changeStatus(Request $request, Account $account, Contact $contact)
    {
        $data = $request->validate(["status" => ["required"]]);
        $response = $this->conversationRepo->changeStatus($contact, $data["status"]);
        return ApiResponse::success("Conversation status changed", new ContactResource($response));
    }

    public function sendChatMessage(SendChatMessageRequest $request, Account $account, Contact $contact)
    {
        $response = $this->conversationRepo->sendMessage($request, $contact);
        return ApiResponse::success("Message sent", $response);
    }
}
