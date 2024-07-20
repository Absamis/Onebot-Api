<?php

namespace App\Enums;

enum InstagramScopesEnums
{
    const loginScope = "instagram_basic,instagram_manage_comments,instagram_manage_insights,pages_show_list";
    const pageScopes = "instagram_basic,instagram_manage_comments,instagram_manage_insights,instagram_content_publish,pages_show_list,pages_read_engagement,instagram_shopping_tag_products";
}
