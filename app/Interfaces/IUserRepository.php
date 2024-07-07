<?php

namespace App\Interfaces;

use App\Models\User;

interface IUserRepository
{
    //
    public function getUserDetails($id);
    public function updatePassword(User $user, $data);
    public function createNewAccount($data);
    public function validatePassword(User $user, $password): bool;
    public function loginUser(User $user);
    public function verifyAccount(User $user);
    public function getTagUser($tag);
}
