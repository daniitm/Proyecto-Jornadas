<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // Define tus políticas aquí
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Define tus gates aquí
        Gate::define('admin', function ($user) {
            return $user->isAdmin();
        });
    }
}
