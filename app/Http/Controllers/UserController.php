<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponse;
use App\Enums\AccountEnums;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ChangePinRequest;
use App\Http\Resources\Auth\VerificationResource;
use App\Http\Resources\UserResource;
use App\Interfaces\Auth\IVerificationRepository;
use App\Interfaces\IReferralRepository;
use App\Interfaces\IUserProfileRepository;
use App\Interfaces\IUserRepository;
use App\Mail\PasswordChangedMail;
use App\Mail\EmailChangeVerificationMail;
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
    protected $vrfRepo;
    public function __construct(IUserRepository $userRepo, IUserProfileRepository $profileRepo, IReferralRepository $refRepo, IVerificationRepository $vrfRepo)
    {
        $this->userRepo = $userRepo;
        $this->profileRepo = $profileRepo;
        $this->refRepo = $refRepo;
        $this->vrfRepo = $vrfRepo;
    }

    public function getUserDetails()
    {
        $res = $this->profileRepo->getUserDetails();
        return ApiResponse::success("User details fetched", new UserResource($res));
    }

    public function changeProfilePhoto(Request $request)
    {
        $data = $request->validate([
            "image" => ["required", "image", "max:10048"]
        ]);
        $resp = $this->profileRepo->changeProfilePhoto($request->file("image"));
        return ApiResponse::success("Profile photo uploaded successfully", new UserResource($resp));
    }

    public function changeEmailRequest(Request $request)
    {
        $data = $request->validate([
            'new_email' => ['required', 'email', 'unique:users,email'],
        ]);
        $user = auth()->user();
        $vrf = $this->vrfRepo->getVerificationCode($user, AccountEnums::emailChangeVerificationType, $data['new_email']);

        $user->email = $data["new_email"];
        Notification::send($user, new VerificationCodeNotification($vrf));
        return ApiResponse::success('Verification code sent to new email');
    }
    public function verifyEmailChange(Request $request)
    {
        $data = $request->validate([
            'code' => ['required'],
        ]);

        $user = auth()->user();
        $vrf = $this->vrfRepo->verifyEmailChangeCode($data['code']);
        $newEmail = $vrf->data;
        $resp = $this->profileRepo->changeEmail($user, $newEmail);
        return ApiResponse::success('Email changed successfully', new UserResource($resp));
    }
}
