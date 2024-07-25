<?php

namespace App\Providers;

use App\Interfaces\Auth\ISocialsAuthRepository;
use App\Interfaces\Auth\IVerificationRepository;
use App\Interfaces\Channels\IChannelsRepository;
use App\Interfaces\Channels\IConversationsRepository;
use App\Interfaces\IAccountRepository;
use App\Interfaces\IReferralRepository;
use App\Interfaces\IUserProfileRepository;
use App\Interfaces\IUserRepository;
use App\Repository\AccountRepository;
use App\Repository\Auth\SocialsAuthRepository;
use App\Repository\Auth\VerificationRepository;
use App\Repository\Channels\ChannelsRepository;
use App\Repository\Channels\ConversationsRepository;
use App\Repository\ReferralRepository;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use App\Services\Socials\FacebookApiService;
use App\Services\Socials\InstagramApiService;
use App\Services\Socials\GoogleService;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\ISubscriptionPlanRepository;
use App\Repository\SubscriptionPlanRepository;

class RepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IUserProfileRepository::class, UserProfileRepository::class);
        $this->app->bind(IReferralRepository::class, ReferralRepository::class);
        $this->app->bind(IAccountRepository::class, AccountRepository::class);
        $this->app->bind(IVerificationRepository::class, VerificationRepository::class);
        $this->app->bind(IChannelsRepository::class, ChannelsRepository::class);
        $this->app->bind(IConversationsRepository::class, ConversationsRepository::class);
        $this->app->bind(ISubscriptionPlanRepository::class, SubscriptionPlanRepository::class);

        $this->app->bind(ISocialsAuthRepository::class, SocialsAuthRepository::class);
        $this->app->bind(FacebookApiService::class, function () {
            return new FacebookApiService(config("services.facebook.api_url"), "", config("services.facebook.app_id"), config("services.facebook.app_secret"));
        });
        $this->app->bind(GoogleService::class, function () {
            return new GoogleService(config("services.google.api_url"), "", config("services.google.client_id"), config("services.google.client_secret"));
        });
        $this->app->bind(InstagramApiService::class, function () {
            return new InstagramApiService(config("services.instagram.api_url"), "", config("services.instagram.app_id"), config("services.instagram.app_secret"));
        });
    }
}
