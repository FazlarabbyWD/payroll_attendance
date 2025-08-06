<?php

namespace App\Providers;

use App\Repositories\DeviceRepository;
use App\Repositories\DeviceRepositoryInterface;
use App\Services\DeviceService;
use App\Services\DeviceServiceInterface;
use App\Services\UserService;
use App\Repositories\UserRepository;
use App\Services\UserServiceInterface;
use Illuminate\Support\ServiceProvider;
use App\Repositories\UserRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);

         $this->app->bind(DeviceRepositoryInterface::class, DeviceRepository::class);
        $this->app->bind(DeviceServiceInterface::class, DeviceService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
