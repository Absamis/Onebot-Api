<?php

namespace App\Repository\Channels;

use App\DTOs\CreateContactDTO;
use App\DTOs\ReceiveContactMessageDTO;
use App\Enums\ActivityLogEnums;
use App\Enums\ChannelConversationEnums;
use App\Interfaces\Channels\IConversationsRepository;
use App\Models\Channels\Channel;
use App\Models\Channels\ChannelConversation;
use App\Models\Channels\Contact;
use App\Models\User;
use App\Repository\BaseRepository;
use App\Services\Socials\FacebookApiService;
use App\Services\UserService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ConversationsRepository extends BaseRepository implements IConversationsRepository
{
    /**
     * Create a new class instance.
     */
    public $chatStorage;
    public $fbService;
    public function __construct(FacebookApiService $fbS)
    {
        //
        $this->fbService = $fbS;
        $this->chatStorage = Storage::disk("chat_files");
    }

    public function assignUser(Contact $contact, User $user = null)
    {
        $uid = $user ? $user->id : null;
        if ($uid == $contact->conversation_assigned_to && $contact->conversation_status == ChannelConversationEnums::assigned)
            return $contact;
        $contact->update([
            "conversation_assigned_to" => $uid,
            "conversation_status" => ChannelConversationEnums::assigned
        ]);
        UserService::logActivity(ActivityLogEnums::assignedConversation, [
            "userid" => $uid,
            "contact id" => $contact->id
        ]);
        return $contact;
    }

    public function changeStatus(Contact $contact, $status)
    {
        if ($contact->conversation_status == $status)
            return $contact;
        $contact->conversation_status = $status;
        $contact->save();
        UserService::logActivity(ActivityLogEnums::assignedConversation, [
            "contact_id" => $contact->id,
            "status" => $status
        ]);
        return $contact;
    }

    public function receiveMessage(ReceiveContactMessageDTO $rcv, Contact $contact)
    {
        //
        return $this->saveConversation($contact, $rcv);
    }

    private function saveConversation(Contact $contact, ReceiveContactMessageDTO $rcv)
    {
        $conv = $contact->conversations()->active()->first();
        if (!$conv) {
            $conv = new ChannelConversation();
            $conv->contact_id = $contact->id;
        }
        $convMessage = $conv->messages ?? [];
        $newConv = [
            "id" => $rcv->id,
            "app_type" => $rcv->channel,
            "date" => $rcv->date,
            "time" => $rcv->time,
            "conv_type" => $rcv->conv_type,
            "sender" => $rcv->sender,
            "attached" => $rcv->attached,
            "message" => $data["message"] ?? null,
            "status" => ChannelConversationEnums::sendingStatus
        ];
        array_push($convMessage, $newConv);
        if (count($convMessage) >= config("services.utils.max_chat_message_count"))
        $conv->saturation_status = true;
        $conv->messages = $convMessage;
        $conv->save();
        return $newConv;
    }

    public function sendMessage($request, Contact $contact)
    {
        if ($contact->conversation_status != ChannelConversationEnums::assigned) {
            abort(200, "This conversation is either closed or not yet assigned to a user");
        }
        if (auth()->user()->id != $contact->conversation_assigned_to) {
            abort(403, "You can't send message to a conversation you are not assigned to you");
        }
        $data = $request->validated();
        $this->validateNotAllArrayFieldEmpty($data);
        $files = $request->file("files") ?? [];
        $attached = [];
        foreach ($files as $key => $file) {
            $fData = [
                "name" => Storage::name($file),
                "type" => Storage::mimeType($file),
                "size" => Storage::size($file),
                "path" => $this->chatStorage->put("files", $file),
                "description" =>  $data["descriptions"][$key] ?? null,
                "caption" => $data["captions"][$key] ?? null
            ];
            array_push($attached, $fData);
        }

        $rcv = new ReceiveContactMessageDTO(
            id: Str::uuid(),
            date: date("Y-m-d"),
            time: date("H:i:s"),
            conv_type: ChannelConversationEnums::adminConversationType,
            sender: [
                "id" => auth()->user()->id,
                "name" => auth()->user()->name,
                "type" => ChannelConversationEnums::adminConversationType,
            ],
            attached: $attached,
            channel: $contact->contact_app_type,
            status: ChannelConversationEnums::sendingStatus,
            message: $data["message"] ?? null
        );

        return $this->saveConversation($contact, $rcv);
    }
}
