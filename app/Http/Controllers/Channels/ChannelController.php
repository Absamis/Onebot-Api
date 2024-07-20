<?php

namespace App\Http\Controllers\Channels;

use App\Classes\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Channels\AddChannelRequest;
use App\Http\Resources\Channels\ChannelResource;
use App\Interfaces\Channels\IChannelsRepository;
use App\Models\Account;
use App\Models\Channels\Channel;
use App\Models\Configurations\AccountOption;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    //
    public $channelsRepo;
    public function __construct(IChannelsRepository $channelsRepo)
    {
        $this->channelsRepo = $channelsRepo;
    }

    public function getChannelsCredential(Request $request, Account $account, AccountOption $option)
    {
        $response = $this->channelsRepo->getChannelsCredentials($option);
        return ApiResponse::success("Credentials fetched", $response);
    }


    public function addChannel(AddChannelRequest $request, Account $account, AccountOption $option)
    {
        $response = $this->channelsRepo->addChannel($option, $request->validated());
        return ApiResponse::success("Channel added successfully", $response);
    }

    public function getChannels(Account $account, Channel $channel = null)
    {
        $response = $this->channelsRepo->getChannels($channel);
        return ApiResponse::success("Channeld fetched", ChannelResource::collection($response));
    }

    public function removeChannel(Account $account, Channel $channel)
    {
        $response = $this->channelsRepo->removeChannel($channel);
        return ApiResponse::success("Channel removed");
    }
}
