<?php

namespace App\Interfaces;

use App\Models\User;

interface IReferralRepository
{
    //
    public function addReferralData(User $user, User $referee);
    public function getReferrals();
}
