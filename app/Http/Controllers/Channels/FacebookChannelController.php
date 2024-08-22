<?php

namespace App\Http\Controllers\Channels;

use App\Classes\ApiResponse;
use App\DTOs\MessageAttachmentDTO;
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
                $message = $value["message"];
                $id = $message["mid"];
                $text = $message["text"] ?? null;
                $sticker = $message["sticker"] ?? null;
                $timestamp = $value["timestamp"];
                $attachements = $message["attachments"] ?? [];
                $attached = [];
                foreach ($attachements as $key => $attach) {
                    $ath = new MessageAttachmentDTO(
                        name: $attach["name"],
                        type: $attach["type"] ?? null,
                        mime: $attach["payload"]["mime_type"] ?? null,
                        url: $attach["payload"]["url"] ?? null,
                        size: $attach["payload"]["size"]
                    );
                    array_push($attached, $ath);
                }
                $contact = $this->contactService->findContactOrCreate($value["sender"]["id"], ChannelEnums::facebookChannelCode, $value["receiver"]["id"]);
                $rcv = new ReceiveContactMessageDTO(
                    id: $id,
                    date: date("Y-m-d", $timestamp),
                    time: date("H:i:s", $timestamp),
                    conv_type: ChannelConversationEnums::contactConversationType,
                    message: $text,
                    channel: ChannelEnums::facebookChannelCode,
                    status: ChannelConversationEnums::sentStatus,
                    attached: $attached,
                    sticker: $sticker
                );
                $this->convService->receiveMessage($rcv, $contact);
                return ApiResponse::success("Message received successfully");
                break;
            default:
                abort(400, "Unknown hook state");
                break;
        }
    }
}
