<?php

namespace App\Providers;

use App\Auth\QueryBuilderUserProvider;
use App\Auth\ShopUser;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        Auth::provider('query-builder', function (Application $app): QueryBuilderUserProvider {
            return new QueryBuilderUserProvider($app->make(Hasher::class));
        });

        Gate::define('admin', function (ShopUser $user): bool {
            return (bool) $user->is_admin;
        });
    }
}
