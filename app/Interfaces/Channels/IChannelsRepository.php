<?php

namespace App\Interfaces\Channels;

use App\Models\Configurations\AccountOption;

interface IChannelsRepository
{
    //
    public function getChannelsCredentials(AccountOption $option);
    public function addChannel(AccountOption $option, $data);
}
