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

    public function getUserDetails()
    {
        $details = $this->user;
        return $details;
    }

    public function changeProfilePhoto($image)
    {
        $prevImg = $this->user->getRawOriginal('photo');
        if ($prevImg) {
            if (Storage::disk("upl")->exists($prevImg))
                Storage::disk("upl")->delete($prevImg);
        }
        $url = Storage::disk("upl")->put("images", $image);
        $this->user->photo = $url;
        $this->user->save();
        UserService::logActivity(ActivityLogEnums::userChangePhoto);
        return $this->user;
    }

    public function changeEmail($user, $newEmail)
    {
        // Update the user's email
        $this->user->email = $newEmail;
        $this->user->save();

        UserService::logActivity(ActivityLogEnums::UserChangeEmail);

        return $this->user;
    }
}
