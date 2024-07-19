<?php

namespace App\Repository\Channels;

use App\Enums\FacebookScopesEnums;
use App\Interfaces\Channels\IChannelsRepository;
use App\Models\Channels\Channel;
use App\Models\Configurations\AccountOption;
use App\Services\Socials\FacebookApiService;
use Illuminate\Support\Facades\Auth;

class ChannelsRepository implements IChannelsRepository
{
    /**
     * Create a new class instance.
     */
    public $fbService;
    public function __construct(FacebookApiService $fbSv)
    {
        //
        $this->fbService = $fbSv;
    }

    public function getChannelsCredentials(AccountOption $option)
    {
        switch ($option->code) {
            case "fb":
                return $this->fbService->getCredentials(FacebookScopesEnums::pageScopes);
                break;
            default:
                abort(400, "Invalid account oprion");
        }
    }

    public function addChannel(AccountOption $option, $data)
    {
        $data["account_id"] = Auth::account()->id;
        switch ($option->code) {
            case "fb":
                $data["type"] = "fb";
                break;
            default:
                abort(400, "Invalid request sent");
        }
        $channel = Channel::updateOrCreate([
            "type" => $data["type"],
            "channel_app_id" => $data["channel_app_id"],
            "account_id" => $data["account_id"]
        ], $data);
        return $channel;
    }
}
