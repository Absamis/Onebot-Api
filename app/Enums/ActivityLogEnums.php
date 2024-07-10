<?php

namespace App\Enums;

enum ActivityLogEnums
{
    //
    const userSigin = "user-sigin";
    const userChangePhoto = "change-photo";
    const accountCreated = "account-created";
    const accountUpdated = "account-updated";
    const userChangeEmail = "user-change-email";
    const invitedMember = "invited-member";
    const deletedMember = "delete-memeber";


    const logMessages = [
        self::userSigin => "Signed in",
        self::userChangePhoto => "Profile photo updated",
        self::accountCreated => "New account created",
        self::userChangeEmail => "Changed email to new one",
        self::accountUpdated => "Account details updated",
        self::invitedMember => "Invited a memeber to the account",
        self::deletedMember => "Deleted a memeber of the account"
    ];
}
