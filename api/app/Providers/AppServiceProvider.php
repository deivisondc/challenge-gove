<?php

namespace App\Providers;

use App\Repositories\FileImportErrorRepository;
use App\Repositories\Interfaces\FileImportErrorRepositoryInterface;
use App\Repositories\Interfaces\FileImportRepositoryInterface;
use App\Repositories\FileImportRepository;
use App\Repositories\Interfaces\NotificationRepositoryInterface;
use App\Repositories\NotificationRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            FileImportRepositoryInterface::class,
            FileImportRepository::class
        );
        $this->app->bind(
            FileImportErrorRepositoryInterface::class,
            FileImportErrorRepository::class
        );
        $this->app->bind(
            NotificationRepositoryInterface::class,
            NotificationRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
