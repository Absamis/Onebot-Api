<?php

namespace App\Interfaces;
interface IUserProfileRepository
{
    //
    public function getUserDetails();
    public function changeProfilePhoto($image);
    public function changePin($data);
    public function changePassword($data);
    public function validatePin($data);
    public function changeUserTag($tag);
    public function updateDailyLimit($amount);
}
