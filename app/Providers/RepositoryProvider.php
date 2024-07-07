<?php

namespace App\Providers;

use App\Interfaces\Auth\ISocialsAuthRepository;
use App\Interfaces\IReferralRepository;
use App\Interfaces\IUserProfileRepository;
use App\Interfaces\IUserRepository;
use App\Repository\Auth\SocialsAuthRepository;
use App\Repository\ReferralRepository;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use App\Services\Socials\FacebookApiService;
use App\Services\Socials\GoogleService;
use Illuminate\Support\ServiceProvider;

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


        $this->app->bind(ISocialsAuthRepository::class, SocialsAuthRepository::class);
        $this->app->bind(FacebookApiService::class, function () {
            return new FacebookApiService(config("services.facebook.api_url"), "", config("services.facebook.app_id"), config("services.facebook.app_secret"));
        });
        $this->app->bind(GoogleService::class, function () {
            return new GoogleService(config("services.google.api_url"), "", config("services.google.client_id"), config("services.google.client_secret"));
        });
    }
}
