<?php

namespace App\Repository;

use App\Enums\ActivityLogEnums;
use App\Enums\RoleEnums;
use App\Interfaces\IAccountRepository;
use App\Models\Account;
use App\Models\UserAccount;
use App\Services\UserService;

class AccountRepository implements IAccountRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function addAccount($data)
    {
        if ($this->isAccountExist($data["name"]))
            abort(400, "You have an account with same name");
        $data["userid"] = auth()->user()->id;
        $acct = Account::create($data);
        $acct = UserAccount::create([
            "userid" => auth()->user()->id,
            "account_id" => $acct->id,
            "role_id" => getRole(RoleEnums::ownerRole)->id
        ]);
        UserService::logActivity(ActivityLogEnums::accountCreated, [
            "model" => Account::class,
            "id" => $acct->id,
        ]);
        return $acct;
    }

    private function isAccountExist($name)
    {
        $check = Account::where(["userid" => auth()->user()->id, "name" => $name])->first();
        return $check ? true : false;
    }
}
