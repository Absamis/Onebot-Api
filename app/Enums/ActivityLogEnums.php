<?php

namespace App\Enums;

enum ActivityLogEnums
{
    //
    const userSigin = "user-sigin";
    const userChangePhoto = "change-photo";


    const logMessages = [
        "user-sigin" => "Signed in",
        "change-photo" => "Profile photo updated"
    ];
}
