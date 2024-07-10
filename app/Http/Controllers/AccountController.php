<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponse;
use App\Http\Requests\Accounts\InviteUserRequest;
use App\Http\Requests\AddAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Http\Resources\AccountResource;
use App\Http\Resources\Accounts\AccountInvitationResource;
use App\Http\Resources\UserAccountResource;
use App\Interfaces\IAccountRepository;
use App\Models\Account;
use App\Models\UserAccount;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    //

    public $accountRepo;
    public function __construct(IAccountRepository $actRepo)
    {
        $this->accountRepo  = $actRepo;
    }

    public function getAccountDetails(Account $account)
    {
        return ApiResponse::success("Account details fetched", new AccountResource($account));
    }
    public function addAccount(AddAccountRequest $request)
    {
        $response = $this->accountRepo->addAccount($request->validated());
        return ApiResponse::success("Account created successfully", new UserAccountResource($response));
    }

    public function updateAccount(AddAccountRequest $request, Account $account)
    {
        $response = $this->accountRepo->updateAccount($account, $request->validated());
        return ApiResponse::success("Account created successfully", $response);
    }

    public function inviteUser(InviteUserRequest $request, Account $account)
    {
        if (!isset($_GET["redirect_url"]))
        abort(400, "Add a redirect url to receive token as a url parameter. e.g. domain.com/:token_param");
        $response = $this->accountRepo->inviteUser($account, $request->validated());
        return ApiResponse::success("Invitation sent successfully", new AccountInvitationResource($response));
    }

    public function acceptInvite($token)
    {
        $response = $this->accountRepo->acceptInvite($token);
        return ApiResponse::success("Invite accepted", new UserAccountResource($response));
    }

    public function changeMemberRole(Request $request, Account $account, UserAccount $member)
    {
        $data = $request->validate(["role_id" => ["required", "exists:roles,id"]]);
        $response = $this->accountRepo->changeMemberRole($member, $data["role_id"]);
        return ApiResponse::success("Member removed successfully", new UserAccountResource($response));
    }

    public function removeAccountMember(Request $request, Account $account, UserAccount $member)
    {
        $response = $this->accountRepo->removeAccountMember($member);
        return ApiResponse::success("Member removed successfully", new AccountResource($response));
    }
}
