<?php

namespace App\Interfaces\Channels;

use App\Models\Channels\Contact;
use App\Models\User;

interface IConversationsRepository
{
    //
    public function assignUser(Contact $contact, User $user = null);
    public function changeStatus(Contact $contact, $status);
    public function sendMessage($request, Contact $contact);
}
