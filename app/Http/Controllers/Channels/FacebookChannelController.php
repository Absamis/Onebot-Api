<?php

namespace App\Http\Controllers\Channels;

use App\Classes\ApiResponse;
use App\DTOs\ReceiveContactMessageDTO;
use App\Enums\ChannelConversationEnums;
use App\Enums\ChannelEnums;
use App\Enums\FacebookScopesEnums;
use App\Http\Controllers\Controller;
use App\Interfaces\Channels\IContactRepository;
use App\Interfaces\Channels\IConversationsRepository;
use App\Services\ContactService;
use App\Services\Socials\FacebookApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FacebookChannelController extends Controller
{
    //
    public $fbService;
    public $convService;
    public $contactService;
    public function __construct(FacebookApiService $fbS, IConversationsRepository $conv, IContactRepository $contSv)
    {
        $this->contactService = $contSv;
        $this->fbService = $fbS;
        $this->convService = $conv;
    }

    public function webhook(Request $request)
    {
        $data = $request->all();
        $field = $data["field"];
        $value = $data["value"];
        switch ($field) {
            case FacebookScopesEnums::fbMessageWebhook:

                $contact = $this->contactService->findContactOrCreate($value["sender"]["id"], ChannelEnums::facebookChannelCode, $value["receiver"]["id"]);
                $rcv = new ReceiveContactMessageDTO(
                    id: $value["message"]["mid"],
                    date: date("Y-m-d", $value["timestamp"]),
                    time: date("H:i:s", $value["timestamp"]),
                    conv_type: ChannelConversationEnums::contactConversationType,
                    message: $value["message"]["text"],
                    channel: ChannelEnums::facebookChannelCode,
                    status: ChannelConversationEnums::sentStatus,
                    sender: [
                        "id" => $contact->id,
                        "name" => $contact->name,
                        "type" => ChannelConversationEnums::contactConversationType,
                    ],
                    receiver: null,
                );
                $this->convService->receiveMessage($rcv, $contact);
                return ApiResponse::success("Message received uccessfully");
                break;
            default:
                abort(400, "Unknown hook state");
                break;
        }
    }
}
