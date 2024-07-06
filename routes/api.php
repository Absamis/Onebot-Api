<?php

use App\Http\Controllers\Auth\SignupController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Configurations\AccountOptionController;
use App\Http\Controllers\Configurations\RoleController;
use App\Http\Controllers\Configurations\SigninOptionController;
use App\Http\Controllers\ConfigurationsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix("auth")->group(function () {
    Route::get("{option}/signup-request", [SignupController::class, "signupRequest"]);
    Route::get("{option}/signup", [SignupController::class, "signup"]);
});

Route::prefix("configs")->group(function () {
    Route::get('/account-options', [ConfigurationsController::class, 'getAccountOptions']);
    Route::get('/roles', [ConfigurationsController::class, 'getRoles']);
    Route::get('/signin-options', [ConfigurationsController::class, 'getSigninOptions']);
});
