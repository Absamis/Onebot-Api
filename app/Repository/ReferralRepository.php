<?php

namespace App\Repository;

use App\Interfaces\IReferralRepository;
use App\Models\ReferralBonus;
use App\Models\User;

class ReferralRepository implements IReferralRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function addReferralData(User $user, User $referee){
        $ref = ReferralBonus::create([
            "userid" => $user->id,
            "referee_id" => $referee->id,
            "entitled_bonus" => appSettings()->referral_bonus,
            "bonus_earned" => 0,
            "percentage_charge" => appSettings()->referral_percentage
        ]);
        return $ref;
    }

    public function getReferrals(){
        $totBonusEarned = ReferralBonus::where("userid", auth()->user()->id)->sum("bonus_earned");
        $totBonusEntitled = ReferralBonus::where("userid", auth()->user()->id)->sum("entitled_bonus");
        $totBonusLeft = $totBonusEntitled - $totBonusEarned;
        $details = ReferralBonus::where("userid", auth()->user()->id)
                ->with(["referee:id,user_tag,name"])
                ->simplePaginate(pageCount());
        return [
            "totalBonusEarned" => $totBonusEarned,
            "totalBonusEntitled" => $totBonusEntitled,
            "totalBonusLeft" => $totBonusLeft,
            "record" => $details
        ];
    }
}
