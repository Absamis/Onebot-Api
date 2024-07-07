<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponse;
use App\Enums\Enums\AccountEnums;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ChangePinRequest;
use App\Http\Resources\Auth\VerificationResource;
use App\Http\Resources\UserResource;
use App\Interfaces\Auth\IVerificationRepository;
use App\Interfaces\IReferralRepository;
use App\Interfaces\IUserProfileRepository;
use App\Interfaces\IUserRepository;
use App\Mail\PasswordChangedMail;
use App\Notifications\VerificationCodeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class UserController extends Controller
{
    //
    public $userRepo;
    public $profileRepo;
    public $refRepo;
    public IVerificationRepository $vrfRepo;
    public function __construct(IUserRepository $userRepo, IUserProfileRepository $profileRepo, IReferralRepository $refRepo, IVerificationRepository $vrfRepo)
    {
        $this->userRepo = $userRepo;
        $this->vrfRepo = $vrfRepo;
        $this->refRepo = $refRepo;
        $this->profileRepo = $profileRepo;
    }

    public function getUserDetails()
    {
        $res = $this->profileRepo->getUserDetails();
        return ApiResponse::success("Profile fetched", $res);
    }

    public function changeProfilePhoto(Request $request)
    {
        $data = $request->validate([
            "image" => ["required", "image", "max:10048"]
        ]);
        $resp = $this->profileRepo->changeProfilePhoto($request->file("image"));
        return ApiResponse::success("Profile photo uploaded successfully", new UserResource($resp));
    }

    public function changePin(ChangePinRequest $request)
    {
        $data = $request->validated();
        if (isset($data["token"])) {
            $vrf = $this->vrfRepo->verifyToken($data["token"], true);
            if (!$vrf)
                abort(400, "Invalid request");
            if ($vrf->user->id != auth()->user()->id)
                abort(400, "Invalid request for user");
        }

        $resp = $this->profileRepo->changePin($data);
        Mail::to($resp->email)->queue(new PasswordChangedMail($resp, "pin"));
        return ApiResponse::success("Pin changed succesfully", new UserResource($resp));
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $data = $request->validated();
        $resp = $this->profileRepo->changePassword($data);
        Mail::to($resp->email)->queue(new PasswordChangedMail($resp));
        return ApiResponse::success("Password changed succesfully", new UserResource($resp));
    }

    public function changeUserTag(Request $request)
    {
        $data = $request->validate([
            "tag" => ["required", "unique:users,user_tag", "max:10"]
        ]);
        $resp = $this->profileRepo->changeUserTag($data["tag"]);
        return ApiResponse::success("Tag changed succesfully", new UserResource($resp));
    }

    public function getReferrals()
    {
        $data = $this->refRepo->getReferrals();
        return ApiResponse::success("Referrals Fetched", $data);
    }

    public function validatePin(Request $request)
    {
        $data = $request->validate([
            "pin" => ["required", "numeric", "digits:4"]
        ]);
        $resp = $this->profileRepo->validatePin($data);
        return ApiResponse::success("Pin is valid", new UserResource($resp));
    }

    public function resetPin()
    {
        $user = auth()->user();
        $email = $user->email;
        $vrf = $this->vrfRepo->getVerificationCode($user, AccountEnums::pinVerificationType);
        Notification::send($user, new VerificationCodeNotification($vrf));
        return ApiResponse::success("verification code sent", new VerificationResource($vrf));
    }

    // public function updateDailyLimit(Request $request)
    // {
    //     $minAmt = appSettings()->min_transaction_limit;
    //     $maxAmt = appSettings()->max_transaction_limit;
    //     $data = $request->validate([
    //         "amount" => ["required", "numeric", "min:$minAmt", "max:$maxAmt"],
    //     ]);
    //     $resp = $this->profileRepo->updateDailyLimit($data["amount"]);
    //     return ApiResponse::success("Transaction limit updated successfully", new UserResource($resp));
    // }
}
