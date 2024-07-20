<?php

namespace App\Repository\Channels;

use App\Enums\ActivityLogEnums;
use App\Enums\FacebookScopesEnums;
use App\Interfaces\Channels\IChannelsRepository;
use App\Models\Channels\Channel;
use App\Models\Configurations\AccountOption;
use App\Services\Socials\FacebookApiService;
use App\Services\UserService;
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
        UserService::logActivity(ActivityLogEnums::addedChannel, [
            "channel id" => $channel->id
        ]);
        return $channel;
    }

    public function getChannels(Channel $channel = null)
    {
        $data = Channel::where("account_id", Auth::account()->id)->get();
        return $data;
    }

    public function removeChannel(Channel $channel)
    {
        $channel->delete();
        UserService::logActivity(ActivityLogEnums::deletedChannel, [
            "channel_name" => $channel->name,
            "channel_type" => $channel->type,
            "channel_app_id" => $channel->channel_app_id,
        ]);
        return true;
    }
}
