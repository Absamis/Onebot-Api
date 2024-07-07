<?php

namespace App\Repository;

use App\Interfaces\IUserProfileRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserProfileRepository implements IUserProfileRepository
{
    /**
     * Create a new class instance.
     */
    public $user;
    public function __construct()
    {
        //
        $this->user = auth()->user();
    }

    public function getUserDetails(){
        $details = $this->user;
        return $details;
    }

    public function updateDailyLimit($amount){
        $this->user->wallet->transaction_limit = $amount;
        $this->user->wallet->save();
        return $this->user;
    }

    public function changeProfilePhoto($image){
        $prevImg = $this->user->getRawOriginal('photo');
        if($prevImg){
            if(Storage::disk("upl")->exists($prevImg))
                Storage::disk("upl")->delete($prevImg);
        }
        $url = Storage::disk("upl")->put("images", $image);
        $this->user->photo = $url;
        $this->user->save();
        return $this->user;
    }
}
