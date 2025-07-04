<?php

namespace App\Enums;

enum ChannelConversationEnums
{
    //
    const active = 1;
    const inactive = 0;

    const unassigned = "unassigned";
    const assigned = "assigned";
    const opened = "opened";
    const closed = "closed";

    const adminConversationType = "admin";
    const contactConversationType = "contact";

    const readStatus = "read";
    const deliveredStatus = "delivered";
    const sentStatus = "sent";
    const sendingStatus = "sending";
    const failedStatus = "failed";
}
