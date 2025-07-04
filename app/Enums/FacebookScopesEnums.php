<?php

namespace App\Enums;

enum FacebookScopesEnums
{
    //
    const loginScope = "email,pages_manage_metadata,pages_manage_posts,pages_manage_engagement,pages_show_list";
    const pageScopes = "pages_messaging,pages_manage_metadata,pages_manage_posts,pages_manage_engagement,pages_show_list,pages_read_engagement,publish_video";

    const fbMessageWebhook = "messages";
    const fbMessageEditsWebhook = "message_edits";
}
