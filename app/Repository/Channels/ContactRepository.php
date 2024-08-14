<?php

namespace App\Repository\Channels;

use App\DTOs\CreateContactDTO;
use App\Enums\ChannelEnums;
use App\Interfaces\Channels\IContactRepository;
use App\Models\Channels\Channel;
use App\Models\Channels\Contact;
use App\Services\Socials\FacebookApiService;

class ContactRepository implements IContactRepository
{
    /**
     * Create a new class instance.
     */
    public $fbService;
    public function __construct(FacebookApiService $fbS)
    {
        //
        $this->fbService = $fbS;
    }

    public function createContact(Channel $channel, CreateContactDTO $data) {}

    public function findContactOrCreate($contact_app_id, $contact_app_type, $channel_app_id = null)
    {
        //
        $conv = Contact::where(["contact_app_id" => $contact_app_id, "contact_app_type" => $contact_app_type])->first();
        if (!$conv) {
            $chn = Channel::active()->where(["type" => $contact_app_type, "channel_app_id" => $channel_app_id])->first();
            if (!$chn)
                abort(400, "Channel is not connected to any " . config("app.name") . " account");

            switch ($contact_app_type) {
                case ChannelEnums::facebookChannelCode:
                    $fbData = $this->fbService->getFBContactProfile($contact_app_id, $chn->token);
                    $err = $fbData["error"] ?? null;
                    if (!$fbData || $err)
                        abort(400, "Error fetching facebook user profile");
                    $contData = new CreateContactDTO(
                        name: $fbData["last_name"] . " " . $fbData["first_name"],
                        photo: $fbData["profile_pic"],
                        locale: $fbData["locale"],
                        email: null,
                        phone: null,
                        gender: $fbData["gender"]
                    );
                    break;
                default:
                    abort(400, "Invalid channel request");
                    break;
            }
            $conv = Contact::create([
                "account_id" => $chn->account_id,
                "channel_id" => $chn->id,
                "name" => $contData->name,
                "contact_app_id" => $contact_app_id,
                "token" => null,
                "email" => $contData->email,
                "phone" => $contData->phone,
                "photo" => $contData->photo,
                "gender" => $contData->gender,
                "locale" => $contData->locale,
                "contact_app_type" => $contact_app_type
            ]);
        }
        return $conv;
    }
}
