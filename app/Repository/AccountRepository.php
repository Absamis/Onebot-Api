<?php

namespace App\Repository;

use App\Enums\ActivityLogEnums;
use App\Enums\AppEnums;
use App\Enums\RoleEnums;
use App\Interfaces\IAccountRepository;
use App\Mail\SendAccountInvitationMail;
use App\Models\Account;
use App\Models\Accounts\AccountInvitation;
use App\Models\Configurations\Role;
use App\Models\User;
use App\Models\UserAccount;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AccountRepository implements IAccountRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    // public function getAccountDetails()
    // {
    //     return Auth::account();
    // }
    public function addAccount($data)
    {
        if ($this->isAccountExist($data["name"]))
            abort(400, "You have an account with same name");
        $data["userid"] = auth()->user()->id;
        $account = Account::create($data);

        $acct = $this->addUserToAccount(auth()->user(), $account, getRole(RoleEnums::adminRole)->id);
        UserService::logActivity(ActivityLogEnums::accountCreated, [
            "model" => Account::class,
            "id" => $acct->id,
        ]);
        return $acct;
    }

    private function addUserToAccount(User $user, Account $account, $role_id)
    {
        $acct = UserAccount::create([
            "userid" => $user->id,
            "account_id" => $account->id,
            "role_id" => $role_id
        ]);
        return $acct;
    }

    public function updateAccount(Account $account, $data)
    {
        $this->isAccountExist($data["name"], $account->id);
        $account->update($data);
        UserService::logActivity(ActivityLogEnums::accountUpdated, [
            "model" => Account::class,
            "id" => $account->id,
        ]);
        return $account;
    }

    public function inviteUser(Account $account, $data)
    {
        // die($_GET["redirect_url"]);
        $user = UserService::getUser($data["email"]);
        if ($this->isUserOnAccount($account, $user->id ?? null))
            abort(400, "This user is already a team member on this account");
        $check = AccountInvitation::where(["email" => $data["email"], "account_id" => $account->id])->first();
        if ($check)
            abort(200, "An invitation has already been sent to the user");
        $data["token"] = md5($data["email"] . uniqid(mt_rand(000, 999)));
        $data["userid"] = $user->id ?? null;
        $invite = AccountInvitation::updateOrCreate([
            "email" => $data["email"],
            "account_id" => $account->id
        ], $data);
        UserService::logActivity(ActivityLogEnums::invitedMember, [
            "email" => $data["email"]
        ]);
        Mail::to($invite->email)->queue(new SendAccountInvitationMail($account, $invite, $_GET["redirect_url"]));
        return $invite;
    }


    public function acceptInvite($token)
    {
        $invite = AccountInvitation::active()->where("token", $token)->first();
        $mUser = null;
        if (!$invite)
            abort(400, "Invalid or expired invitation link");
        if (!$invite->userid) {
            $mUser = UserService::getUser($invite->email);
            if (!$mUser)
                abort(200, "You are not registered yet. kindly signup with your email $invite->email", ["data" => $invite]);
        }
        if (auth()->user()->id != $invite->userid && auth()->user()->email != $invite->email)
            abort(403, "Invite link is not valid for this account");
        $user = $invite->user ?? $mUser;
        $acct = $this->addUserToAccount($user, $invite->account, $invite->role_id);
        $this->invalidateInvite($invite);
        return $acct;
    }

    public function changeMemberRole(UserAccount $userAccount, $roleid)
    {
        if (Auth::account()->userid == $userAccount->userid)
            abort(400, "You can't change role of account owner");
        $userAccount->role_id = $roleid;
        $userAccount->save();
        return $userAccount;
    }

    public function removeAccountMember(UserAccount $userAccount)
    {
        if (Auth::account()->userid == $userAccount->userid)
            abort(400, "You can't remove the account owner");
        $userAccount->delete();
        UserService::logActivity(ActivityLogEnums::deletedMember, [
            "userid" => $userAccount->userid
        ]);
        return $userAccount->account;
    }

    private function invalidateInvite(AccountInvitation $invite)
    {
        $invite->status = AppEnums::inactive;
        $invite->token = null;
        $invite->save();
    }

    private function isUserOnAccount(Account $account, $uid)
    {
        if (!$uid) return false;
        $check = UserAccount::where(["userid" => $user->id, "account_id" => $account->id])->first();
        return $check ? true : false;
    }

    private function isAccountExist($name, $except_id = null)
    {
        $check = Account::where(["userid" => auth()->user()->id, "name" => $name]);
        if ($except_id)
            $check = $check->where("id", "<>", $except_id);
        $check = $check->first();
        return $check ? true : false;
    }
}
