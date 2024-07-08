<?php

<<<<<<< HEAD
use App\Http\Controllers\AccountController;
=======
>>>>>>> 7862c7be09181960530f514252b8fff7d0eb6eca
use App\Http\Controllers\Auth\SigninController;
use App\Http\Controllers\Auth\SignupController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Configurations\AccountOptionController;
// use App\Http\Controllers\Configurations\RoleController;
// use App\Http\Controllers\Configurations\SigninOptionController;
use App\Http\Controllers\ConfigurationsController;
use App\Http\Controllers\UserController;


Route::prefix("auth")->group(function () {
    Route::get("{option}/auth-request", [SignupController::class, "signupRequest"]);
    Route::post("{option}/signup", [SignupController::class, "signup"]);
    Route::post("{option}/signin", [SigninController::class, "signin"]);
});

Route::middleware("auth:sanctum")->group(function () {
    Route::prefix("user")->group(function () {
        Route::get("", [UserController::class, "getUserDetails"]);
        Route::post("change-photo", [UserController::class, "changeProfilePhoto"]);
<<<<<<< HEAD
    });
    Route::post("accounts", [AccountController::class, "addAccount"]);
=======
        Route::post('change-email-request', [UserController::class, 'changeEmailRequest']);
        Route::post('verify-email', [UserController::class, 'verifyEmailChange']);
    });
>>>>>>> 7862c7be09181960530f514252b8fff7d0eb6eca
});

Route::prefix("configs")->group(function () {
    Route::get('/account-options', [ConfigurationsController::class, 'getAccountOptions']);
    Route::get('/roles', [ConfigurationsController::class, 'getRoles']);
    Route::get('/signin-options', [ConfigurationsController::class, 'getSigninOptions']);
});
