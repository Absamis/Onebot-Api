<?php

use App\Http\Controllers\Auth\SignupController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Configurations\AccountOptionController;
use App\Http\Controllers\Configurations\RoleController;
use App\Http\Controllers\Configurations\SigninOptionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix("auth")->group(function () {
    Route::get("{option}/signup", [SignupController::class, "signup"]);
});

Route::get('/account-options', [AccountOptionController::class, 'index']);
Route::get('/roles', [RoleController::class, 'index']);
Route::get('/signin-options', [SigninOptionController::class, 'index']);
