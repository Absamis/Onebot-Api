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
        $details = $this->user->with(["wallet", "wallet_accounts"])->first();
        return $details;
    }

    public function updateDailyLimit($amount){
        $this->user->wallet->transaction_limit = $amount;
        $this->user->wallet->save();
        return $this->user;
    }

    public function changeProfilePhoto($image){
        $prevImg = $this->user->getRawOriginal('profile_photo');
        if($prevImg){
            if(Storage::disk("upl")->exists($prevImg))
                Storage::disk("upl")->delete($prevImg);
        }
        $url = Storage::disk("upl")->put("images", $image);
        $this->user->profile_photo = $url;
        $this->user->save();
        return $this->user;
    }

    public function changePin($data){
        $newPin = $data["new_pin"];
        $token = $data["token"] ?? null;
        $currentPin = $data["current_pin"] ?? null;
        if($this->user->pin){
            if(!$token){
                if(!$currentPin)
                    abort(400, "Current pin is required");
                if(!Hash::check($currentPin, $this->user->pin)){
                    abort(400, "Current pin is not correct");
                }
            }
        }
        $this->user->pin = $newPin;
        $this->user->save();
        return $this->user;
    }

    public function changePassword($data){
        $newPass = $data["new_password"];
        $currentPass = $data["current_password"] ?? null;
        if (!Hash::check($currentPass, $this->user->password)) {
            abort(400, "Current password is not correct");
        }
        $this->user->password = $newPass;
        $this->user->save();
        return $this->user;
    }

    public function changeUserTag($tag)
    {
        $this->user->user_tag = $tag;
        $this->user->save();
        return $this->user;
    }

    public function validatePin($data){
        if(!Hash::check($data["pin"], $this->user->pin)){
            abort(400, "Incorrect pin");
        }
        return $this->user;
    }
}
