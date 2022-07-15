<?php

namespace App\Providers;

use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\RegisteredAdminController;
use App\Http\Controllers\Admin\ProfileInformationController;
use App\Http\Controllers\Admin\VerifyEmailController;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Auth;
use App\Actions\Admin\AttemptToAuthenticate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app
            ->when([
                LoginController::class,
                AttemptToAuthenticate::class,
                RegisteredAdminController::class,
                ProfileInformationController::class,
                VerifyEmailController::class
                ])
            ->needs(StatefulGuard::class)
            ->give(function () {
                return Auth::guard('admin');
            });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::define('isAdmin',function($admin){
            return $admin->role === 1;
        });
    }
}
