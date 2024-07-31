<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],
    'utils' => [
        'invitiation_active_days' => env("INVITATION_ACTIVE_DAYS"),
        'max_chat_file_size' => env("MAX_CHAT_FILE_SIZE"),
        'max_chat_message_count' => env("MEX_CHAT_MESSAGE_COUNT")
    ],
    'facebook' => [
        'api_url' => env("FACEBOOK_API_URL"),
        'app_id' => env("FACEBOOK_APP_ID"),
        'app_secret' => env("FACEBOOK_APP_SECRET"),
        'api_version' => env("FACEBOOK_API_VERSION"),
        'config_id' => env("FACEBOOK_CONFIG_ID"),
        'webhook_verify_token' => env("FACEBOOK_WEBHOOK_VERIFY_TOKEN")
    ],
    'google' => [
        'api_url' => env("GOOGLE_API_URL"),
        'client_id' => env("GOOGLE_CLIENT_ID"),
        'client_secret' => env("GOOGLE_CLIENT_SECRET")
    ],
    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'instagram' => [
        'api_url' => env("INSTAGRAM_API_URL", "https://graph.facebook.com/v20.0"),
        'app_id' => env("INSTAGRAM_APP_ID"),
        'app_secret' => env("INSTAGRAM_APP_SECRET"),
        'config_id' => env("INSTAGRAM_CONFIG_ID"),
    ],
    'stripe' => [
        'public_key' => env("STRIPE_PUBLIC_KEY"),
        'private_key' => env("STRIPE_SECRET_KEY"),
        'webhook_key' => env("STRIPE_WEBHOOK_KEY")
    ],
    'whatsapp' => [
        'api_url' => env('WHATSAPP_API_URL'),
        'token' => env('WHATSAPP_TOKEN'),
        'app_id' => env('WHATSAPP_APP_ID'),
        'app_secret' => env('WHATSAPP_APP_SECRET'),
    ],
];
