<?php

namespace App\Repository\Channels;

use App\Enums\ActivityLogEnums;
use App\Enums\FacebookScopesEnums;
use App\Enums\InstagramScopesEnums;
use App\Http\Resources\Channels\ChannelResource;
use App\Interfaces\Channels\IChannelsRepository;
use App\Models\Channels\Channel;
use App\Models\Configurations\AccountOption;
use App\Services\Socials\FacebookApiService;
use App\Services\UserService;
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
        UserService::logActivity(ActivityLogEnums::addedChannel, [
            "channel id" => $channel->id
        ]);
        return $channel;
    }

    public function getChannels(Channel $channel = null)
    {
        if (!$channel) {
            $data = Channel::where("account_id", Auth::account()->id)->get();
            return ChannelResource::collection($data);
        } else {
            return Channel::with("contacts")->find($channel->id);
        }
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
