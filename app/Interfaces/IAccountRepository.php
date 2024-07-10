<?php

namespace App\Interfaces;

use App\Models\Account;
use App\Models\UserAccount;

interface IAccountRepository
{
    //
    public function addAccount($data);
    public function updateAccount(Account $account, $data);
    public function inviteUser(Account $account, $data);
    public function acceptInvite($token);
    public function removeAccountMember(UserAccount $userAccount);
    public function changeMemberRole(UserAccount $userAccount, $roleid);
}
