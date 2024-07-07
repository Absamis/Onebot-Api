<?php

namespace App\Providers;

use App\Interface\Auth\ISignupRepository;
use App\Repository\Auth\SignupRepository;
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
        $this->app->bind(ISignupRepository::class, SignupRepository::class);
        $this->app->bind(FacebookApiService::class, function () {
            return new FacebookApiService(config("services.facebook.api_url"), "", config("services.facebook.app_id"), config("services.facebook.app_secret"));
        });

        $this->app->bind(GoogleService::class, function () {
            return new GoogleService(config("services.google.api_url"), "", config("services.google.client_id"), config("services.google.client_secret"));
        });
    }
}
