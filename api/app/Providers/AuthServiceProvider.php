<?php

namespace App\Providers;

use App\Http\Controllers\Auth\AuthController;
use App\Models\Expenses;
use App\Models\User;
use App\Policies\AuthPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Policies\ExpensePolicy;
use App\Policies\UserPolicy;

class AuthServiceProvider extends ServiceProvider
{

    protected $policies = [
        Expenses::class => ExpensePolicy::class,
        User::class => UserPolicy::class,
        AuthController::class => AuthPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        $this->app->bind(UserPolicy::class, function ($app) {
            return new UserPolicy();
        });

        $this->app->bind(ExpensePolicy::class, function ($app) {
            return new ExpensePolicy();
        });
    }
}
