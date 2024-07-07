<?php

namespace App\Enums;

enum AccountEnums
{
    //
    const accountVerificationType = "account";
    const passwordVerificationType = "password";
    const pinVerificationType = "pin";
    const resetPasswordVerificationType = "reset-password";
    const resetPinVerificationType = "reset-pin";

    const unverifiedAccount = 0;
    const verifiedAccount = 1;
    const suspendedAccount = -1;
}
