<?php

namespace App\Repository;

use App\Enums\AccountEnums;
use App\Interfaces\IUserRepository;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository implements IUserRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getUserDetails($id){
        $user = User::where("id", $id)->orWhere("email", $id)->first();
        return $user;
    }

    public function createNewAccount($data){
        $user = User::create($data);
        $user->wallet()->create([
            "transaction_limit" => appSettings()->max_transaction_limit,
        ]);
        $this->generateTag($user);
        return $user;
    }

    public function getTagUser($tag)
    {
        if(!$tag)
            return null;
        $user = User::where("user_tag", $tag)->first();
        return $user;
    }

    public function verifyAccount(User $user){
        $user->status = AccountEnums::verifiedAccount;
        $user->save();
        return $user;
    }

    public function validatePassword(User $user, $password): bool
    {
        $check = Hash::check($password, $user->password);
        return $check;
    }

    public function loginUser(User $user){
        $user = $this->updateLoginData($user);
        $token = $user->createToken($user->last_login_ip)->plainTextToken;
        $user->access_token = $token;
        return $user;
    }

    private function generateTag(User $user){
        $tag = substr($user->name, 0, 4);
        $dig = mt_rand(100, 9999);
        $tag = trim($tag.$dig);
        $user->user_tag = $tag;
        $user->save();
        return $tag;
    }

    public function updatePassword(User $user, $data){
        $user->password = $data;
        $user = $this->updateLoginData($user);
        $this->logout($user);
        return $user;
    }

    public function logout(User $user){
        $user->tokens()->delete();
        return $user;
    }

    private function updateLoginData(User $user){
        $user->last_login = now()->toDateTime();
        $user->last_login_location = getClientUserAgent();
        $user->last_login_ip = getClientIP();
        $user->save();
        return $user;
    }
}
