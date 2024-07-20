<?php

namespace App\Repository\Channels;

use App\Enums\FacebookScopesEnums;
use App\Enums\InstagramScopesEnums;
use App\Interfaces\Channels\IChannelsRepository;
use App\Models\Channels\Channel;
use App\Models\Configurations\AccountOption;
use App\Services\Socials\FacebookApiService;
use App\Services\Socials\InstagramApiService;
use Illuminate\Support\Facades\Auth;

class ChannelsRepository implements IChannelsRepository
{
    public $fbService;
    public $igService;

    public function __construct(FacebookApiService $fbSv, InstagramApiService $igSv)
    {
        $this->fbService = $fbSv;
        $this->igService = $igSv;
    }

    public function getChannelsCredentials(AccountOption $option)
    {
        switch ($option->code) {
            case "fb":
                return $this->fbService->getCredentials(FacebookScopesEnums::pageScopes);
            case "ig":
                return $this->igService->getCredentials(InstagramScopesEnums::pageScopes);
            default:
                abort(400, "Invalid account option");
        }
    }

    public function addChannel(AccountOption $option, $data)
    {
        $data["account_id"] = Auth::account()->id;
        switch ($option->code) {
            case "fb":
                $data["type"] = "fb";
                break;
            case "ig":
                $data["type"] = "ig";
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
