<?php

use App\Http\Controllers\Auth\SignupController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Configurations\AccountOptionController;
use App\Http\Controllers\Configurations\RoleController;
use App\Http\Controllers\Configurations\SigninOptionController;
use App\Http\Controllers\ConfigurationsController;
use App\Http\Controllers\UserController;



Route::middleware("auth:sanctum")->group(function () {
    Route::prefix("user")->group(function () {
        Route::get("", [UserController::class, "getUserDetails"]);
        Route::post("change-profile-photo", [UserController::class, "changeProfilePhoto"]);
        Route::post("change-pin", [UserController::class, "changePin"]);
        Route::post("change-password", [UserController::class, "changePassword"]);
        Route::post("change-tag", [UserController::class, "changeUserTag"]);
        Route::post("validate-pin", [UserController::class, "validatePin"]);
        Route::get("referrals", [UserController::class, "getReferrals"]);
        Route::post("reset-pin", [UserController::class, "resetPin"]);
        Route::post("update-daily-limit", [UserController::class, "updateDailyLimit"])->middleware("auth.pin");;
    });
});

Route::prefix("auth")->group(function () {
    Route::get("{option}/signup-request", [SignupController::class, "signupRequest"]);
    Route::post("{option}/signup", [SignupController::class, "signup"]);
});

Route::prefix("configs")->group(function () {
    Route::get('/account-options', [ConfigurationsController::class, 'getAccountOptions']);
    Route::get('/roles', [ConfigurationsController::class, 'getRoles']);
    Route::get('/signin-options', [ConfigurationsController::class, 'getSigninOptions']);
});
