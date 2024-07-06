<?php

use App\Http\Controllers\Auth\SignupController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix("auth")->group(function () {
    Route::get("{option}/signup-request", [SignupController::class, "signupRequest"]);
    Route::get("{option}/signup", [SignupController::class, "signup"]);
});
