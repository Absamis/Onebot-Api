<?php

namespace App\Repository\Channels;

use App\Enums\ActivityLogEnums;
use App\Enums\ChannelConversationEnums;
use App\Interfaces\Channels\IConversationsRepository;
use App\Models\Channels\Contact;
use App\Models\User;
use App\Services\UserService;

class ConversationsRepository implements IConversationsRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function assignUser(Contact $contact, User $user = null)
    {
        $uid = $user ? $user->id : null;
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
        $contact->conversation_status = $status;
        $contact->save();
        return $contact;
    }
}
