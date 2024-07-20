<?php

namespace App\Interfaces\Channels;

use App\Models\Channels\Channel;
use App\Models\Configurations\AccountOption;

interface IChannelsRepository
{
    //
    public function getChannelsCredentials(AccountOption $option);
    public function addChannel(AccountOption $option, $data);
    public function getChannels(Channel $channel = null);
    public function removeChannel(Channel $channel);
}
