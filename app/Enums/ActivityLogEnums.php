<?php

namespace App\Enums;

enum ActivityLogEnums
{
    const userSignin = "user-signin";
    const UserChangeEmail = "user-change-email";
    const userChangePhoto = "user-change-photo";
    const accountCreated = "account-created";

    const logMessages = [
        self::userSignin => "Signed in",
        self::UserChangeEmail => 'Email changed',
        self::userChangePhoto => "Profile photo updated",
        self::accountCreated => "New account created"
    ];
}
