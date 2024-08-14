<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\SigninController;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\Channels\ChannelController;
use App\Http\Controllers\Channels\ConversationController;
use App\Http\Controllers\Channels\FacebookChannelController;
use App\Http\Controllers\Channels\InstagramChannelController;
use App\Http\Controllers\Channels\WhatsAppChannelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConfigurationsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SubscriptionPlanController;
use App\Http\Controllers\TransactionController;

Route::prefix("auth")->group(function () {
    Route::get("{option}/auth-request", [SignupController::class, "signupRequest"]);
    Route::post("{option}/signup", [SignupController::class, "signup"]);
    Route::post("{option}/signin", [SigninController::class, "signin"]);
});

Route::middleware("auth:sanctum")->group(function () {
    Route::get("invitation/{token}/accept", [AccountController::class, "acceptInvite"]);
    Route::get('plans', [SubscriptionPlanController::class, 'getPlans']);
    Route::get('plans/{plan}/features', [SubscriptionPlanController::class, 'getPlanFeatures']);

    Route::prefix("user")->group(function () {
        Route::post('change-email-request', [UserController::class, 'changeEmailRequest']);
        Route::post('verify-email', [UserController::class, 'verifyEmailChange']);
        Route::post("resend-verification-code", [UserController::class, "resendVerificationCode"]);
        Route::get("", [UserController::class, "getUserDetails"]);
        Route::post("change-photo", [UserController::class, "changeProfilePhoto"]);
    });
    Route::post("accounts", [AccountController::class, "addAccount"]);

    Route::prefix("accounts/{account}")->group(function () {
        Route::get("", [AccountController::class, "getAccountDetails"]);
        Route::put("", [AccountController::class, "updateAccount"]);
        Route::post("invite", [AccountController::class, "inviteUser"]);
        Route::delete("members/{member}", [AccountController::class, "removeAccountMember"]);
        Route::put("members/{member}/change-role", [AccountController::class, "changeMemberRole"]);

        Route::get("get-credentials/channels/{option}", [ChannelController::class, "getChannelsCredential"]);
        Route::post("confirm-channel/{option}", [ChannelController::class, "confirmChannel"]);
        Route::post("add-channel/{option}", [ChannelController::class, "addChannel"]);

        Route::get("channels/{channel?}", [ChannelController::class, "getChannels"]);
        Route::prefix("channels/{channel}")->group(function () {
            Route::delete("", [ChannelController::class, "removeChannel"]);
        });

        Route::prefix("conversations/{contact}")->group(function () {
            Route::get("", [ConversationController::class, "getContact"]);
            Route::put("assign-user", [ConversationController::class, "assignUser"]);
            Route::put("status", [ConversationController::class, "changeStatus"]);
            Route::post("send-message", [ConversationController::class, "sendChatMessage"]);
        });

        Route::post("plans/purchase", [SubscriptionPlanController::class, "purchasePlan"]);

        Route::get("transactions/{trans}/verify", [TransactionController::class, "validateTransaction"]);
    });
});

Route::prefix("configs")->group(function () {
    Route::get('/account-options', [ConfigurationsController::class, 'getAccountOptions']);
    Route::get('/roles', [ConfigurationsController::class, 'getRoles']);
    Route::get('/signin-options', [ConfigurationsController::class, 'getSigninOptions']);

    Route::get("subscription-plans", [SubscriptionPlanController::class, "getPlans"]);
});


Route::prefix("webhooks/callback")->group(function () {
    Route::match(["get", "post"], "facebook", [FacebookChannelController::class, "webhook"]);
    Route::match(["get", "post"], "instagram", [InstagramChannelController::class, "webhook"]);
    Route::match(["get", "post"], "whatsapp", [WhatsAppChannelController::class, "webhook"]);

    Route::post("transactions/verify/{gateway}", [TransactionController::class, "validateWebhook"]);
});
