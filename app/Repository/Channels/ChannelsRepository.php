<?php

namespace App\Repository\Channels;

use App\Enums\ActivityLogEnums;
use App\Enums\ChannelEnums;
use App\Enums\FacebookScopesEnums;
use App\Enums\InstagramScopesEnums;
use App\Http\Resources\Channels\ChannelResource;
use App\Interfaces\Channels\IChannelsRepository;
use App\Models\Channels\Channel;
use App\Models\Configurations\AccountOption;
use App\Services\Socials\FacebookApiService;
use App\Services\UserService;
use App\Services\Socials\InstagramApiService;
use App\Services\Socials\WhatsAppApiService;
use Illuminate\Support\Facades\Auth;

class ChannelsRepository implements IChannelsRepository
{
    public $fbService;
    public $igService;
    public $waService;

    public function __construct(FacebookApiService $fbSv, InstagramApiService $igSv, WhatsAppApiService $waSv)
    {
        $this->fbService = $fbSv;
        $this->igService = $igSv;
        $this->waService = $waSv;
    }

    public function getChannelsCredentials(AccountOption $option)
    {
        $rdr = $_GET["redirect_url"] ?? abort(400, "Redirect url is required for instagram channel");
        switch ($option->code) {
            case ChannelEnums::facebookChannelCode:
                return $this->fbService->getLoginUrl($rdr, FacebookScopesEnums::pageScopes);
            case ChannelEnums::instagramChannelCode:
                return $this->igService->getLoginUrl($rdr, InstagramScopesEnums::loginScope, true);
            case ChannelEnums::whatsappChanelCode:
                return $this->waService->getLoginUrl($rdr);
            default:
                abort(400, "Invalid account option");
        }
    }

    public function confirmChannel(AccountOption $option, $data)
    {
        switch ($option->code) {
            case ChannelEnums::facebookChannelCode:
                verifyLoginState($data["state"], "fb-login-state");
                $signupData = $this->fbService->getFbPages($data["code"]);
                return $signupData;
            case ChannelEnums::instagramChannelCode:
                verifyLoginState($data["state"], "ig-login-state");
                $signupData = $this->igService->getIgUserData($data["code"]);
                $channelData = [
                    "name" => $signupData->name,
                    "description" => "",
                    "channel_app_id" => $signupData->app_id,
                    "token" => $signupData->accessToken,
                    "photo" => $signupData->photo
                ];
                return $this->addChannel($option, $channelData);
            case ChannelEnums::whatsappChanelCode:
                verifyLoginState($data["state"], "wa-login-state");
                $signupData = $this->waService->getWaUserData($data["code"]);
                $channelData = [
                    "name" => $signupData->name,
                    "description" => "",
                    "channel_app_id" => $signupData->app_id,
                    "token" => $signupData->accessToken,
                    "photo" => $signupData->photo
                ];
                return $this->addChannel($option, $channelData);
            default:
                abort(400, "Account option is not available");
        }
    }

    public function addChannel(AccountOption $option, $data)
    {
        $data["account_id"] = Auth::account()->id;
        switch ($option->code) {
            case ChannelEnums::facebookChannelCode:
                $data["type"] = ChannelEnums::facebookChannelCode;
                break;
            case ChannelEnums::instagramChannelCode:
                $data["type"] = ChannelEnums::instagramChannelCode;
                break;
            case ChannelEnums::whatsappChanelCode:
                $data["type"] = ChannelEnums::whatsappChanelCode;
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
