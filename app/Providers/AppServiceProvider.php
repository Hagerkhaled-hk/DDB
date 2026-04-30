<?php

namespace App\Providers;

use App\Models\MultibranchAccessToken;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        // Sanctum will now use YOUR findToken() for ALL auth:sanctum checks
    Sanctum::usePersonalAccessTokenModel(MultibranchAccessToken::class);
    }
}
