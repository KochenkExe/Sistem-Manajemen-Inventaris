<?php

namespace App\Providers;

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
        \Illuminate\Support\Facades\Gate::define('isAdmin', function ($user) {
            return $user->hasRole('admin');
        });

        \Illuminate\Support\Facades\Gate::define('isStaff', function ($user) {
            return $user->hasRole(['staff', 'admin']);
        });

        \Illuminate\Support\Facades\Gate::define('isManager', function ($user) {
            return $user->hasRole(['manager', 'admin']);
        });
    }
}
