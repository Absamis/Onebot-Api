<?php

namespace App\Enums;

enum InstagramScopesEnums
{
    const loginScope = "instagram_basic,instagram_manage_comments,instagram_manage_insights";
    const pageScopes = "instagram_basic,instagram_manage_comments,instagram_manage_insights,instagram_content_publish";
}
