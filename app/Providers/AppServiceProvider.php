<?php

namespace App\Providers;

use App\Repositories\PlanRepository;
use App\Repositories\PostRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Repositories\OrganizationRepository;
use App\Repositories\SocialAccountRepository;
use App\Repositories\SocialNetworkRepository;
use App\Repositories\Interfaces\PlanRepositoryInterface;
use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\OrganizationRepositoryInterface;
use App\Repositories\Interfaces\SocialAccountRepositoryInterface;
use App\Repositories\Interfaces\SocialNetworkRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PlanRepositoryInterface::class, PlanRepository::class);
        $this->app->bind(PostRepositoryInterface::class, PostRepository::class);
        $this->app->bind(SocialAccountRepositoryInterface::class, SocialAccountRepository::class);
        $this->app->bind(SocialNetworkRepositoryInterface::class, SocialNetworkRepository::class);
        $this->app->bind(OrganizationRepositoryInterface::class, OrganizationRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }
}
