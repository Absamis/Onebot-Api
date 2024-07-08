<?php

namespace App\Interfaces;

interface IUserProfileRepository
{
    //
    public function getUserDetails();
    public function changeProfilePhoto($image);
    public function changeEmail($user, $newEmail);
}
