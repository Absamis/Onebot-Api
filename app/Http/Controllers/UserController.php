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
    public function __construct(IUserRepository $userRepo, IUserProfileRepository $profileRepo, IReferralRepository $refRepo)
    {
        $this->userRepo = $userRepo;
        $this->refRepo = $refRepo;
        $this->profileRepo = $profileRepo;
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
}
