<?php

namespace App\Enums;

enum ActivityLogEnums
{
    //
    const userSigin = "user-sigin";
    const userChangePhoto = "change-photo";

    const accountCreated = "account-created";


    const logMessages = [
        self::userSigin => "Signed in",
        self::userChangePhoto => "Profile photo updated",
        self::accountCreated => "New account created"
    ];
}
