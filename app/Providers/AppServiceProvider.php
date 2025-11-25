<?php

namespace App\Providers;

use App\Events\ResourceSynced;
use App\Events\ResourceSyncFailed;
use App\Listeners\LogResourceSynced;
use App\Listeners\LogResourceSyncFailed;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind service interfaces to implementations
        $this->app->bind(
            \App\Contracts\SwapiServiceInterface::class,
            \App\Services\SwapiService::class
        );

        $this->app->bind(
            \App\Contracts\DataSyncServiceInterface::class,
            \App\Services\DataSyncService::class
        );

        $this->app->bind(
            \App\Contracts\RelationshipMapperInterface::class,
            \App\Services\RelationshipMapper::class
        );

        // Bind repository interfaces to implementations
        $this->app->bind(
            \App\Contracts\CharacterRepositoryInterface::class,
            \App\Repositories\CharacterRepository::class
        );

        $this->app->bind(
            \App\Contracts\PlanetRepositoryInterface::class,
            \App\Repositories\PlanetRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register event listeners
        Event::listen(ResourceSynced::class, LogResourceSynced::class);
        Event::listen(ResourceSyncFailed::class, LogResourceSyncFailed::class);
    }
}
