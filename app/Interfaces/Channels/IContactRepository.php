<?php

namespace App\Interfaces\Channels;

interface IContactRepository
{
    //
    public function findContactOrCreate($contact_app_id, $contact_app_type, $channel_app_id = null);
}
