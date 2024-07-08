<?php

namespace App\Repository;

use App\Enums\ActivityLogEnums;
use App\Interfaces\IUserProfileRepository;
use App\Services\UserService;
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

<<<<<<< HEAD
    public function getUserDetails(){
=======
    public function getUserDetails()
    {
>>>>>>> 7862c7be09181960530f514252b8fff7d0eb6eca
        $details = $this->user;
        return $details;
    }


<<<<<<< HEAD
    public function changeProfilePhoto($image){
        $prevImg = $this->user->getRawOriginal('photo');
        if($prevImg){
            if(Storage::disk("upl")->exists($prevImg))
=======
    public function changeProfilePhoto($image)
    {
        $prevImg = $this->user->getRawOriginal('photo');
        if ($prevImg) {
            if (Storage::disk("upl")->exists($prevImg))
>>>>>>> 7862c7be09181960530f514252b8fff7d0eb6eca
                Storage::disk("upl")->delete($prevImg);
        }
        $url = Storage::disk("upl")->put("images", $image);
        $this->user->photo = $url;
        $this->user->save();
        UserService::logActivity(ActivityLogEnums::userChangePhoto);
        return $this->user;
    }
<<<<<<< HEAD
=======

    public function changeEmail($user, $newEmail)
    {
        // Update the user's email
        $this->user->email = $newEmail;
        $this->user->save();

        UserService::logActivity(ActivityLogEnums::userChangeEmail);

        return $this->user;
    }
>>>>>>> 7862c7be09181960530f514252b8fff7d0eb6eca
}
