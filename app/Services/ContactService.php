<?php

namespace App\Services;

use App\Models\Channels\Channel;
use App\Models\Channels\Contact;

class ContactService
{
    /**
     * Create a new class instance.
     */

    public function __construct()
    {
        //
    }

    public static function isContactExists($c_app_id, $channel)
    {
        $conv = Contact::where(["contact_app_id" => $c_app_id, "contact_app_type" => $channel])->first();
        return $conv;
    }
}
