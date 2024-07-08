<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponse;
use App\Http\Requests\AddAccountRequest;
use App\Http\Resources\UserAccountResource;
use App\Interfaces\IAccountRepository;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    //

    public $accountRepo;
    public function __construct(IAccountRepository $actRepo)
    {
        $this->accountRepo  = $actRepo;
    }

    public function addAccount(AddAccountRequest $request)
    {
        $response = $this->accountRepo->addAccount($request->validated());
        return ApiResponse::success("Account created successfully", new UserAccountResource($response));
    }
}
