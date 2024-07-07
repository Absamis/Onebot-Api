<?php

use App\Http\Controllers\Auth\SignupController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Configurations\AccountOptionController;
use App\Http\Controllers\Configurations\RoleController;
use App\Http\Controllers\Configurations\SigninOptionController;
use App\Http\Controllers\ConfigurationsController;
use App\Http\Controllers\UserController;


Route::prefix("auth")->group(function () {
    Route::get("{option}/auth-request", [SignupController::class, "signupRequest"]);
    Route::post("{option}/signup", [SignupController::class, "signup"]);
});

Route::middleware("auth:sanctum")->group(function () {
    Route::prefix("user")->group(function () {
        Route::get("", [UserController::class, "getUserDetails"]);
        Route::post("change-photo", [UserController::class, "changeProfilePhoto"]);
    });
});

Route::prefix("configs")->group(function () {
    Route::get('/account-options', [ConfigurationsController::class, 'getAccountOptions']);
    Route::get('/roles', [ConfigurationsController::class, 'getRoles']);
    Route::get('/signin-options', [ConfigurationsController::class, 'getSigninOptions']);
});
