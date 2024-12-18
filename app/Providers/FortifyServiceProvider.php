<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Support\ServiceProvider;

class FortifyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Binding the interface to the implementation
        $this->app->singleton(CreatesNewUsers::class, CreateNewUser::class);

        // Register Fortify views
        Fortify::loginView(fn() => view('auth.login'));
        Fortify::registerView(fn() => view('auth.register'));
        Fortify::requestPasswordResetLinkView(fn() => view('auth.passwords.email'));
        Fortify::resetPasswordView(fn() => view('auth.passwords.reset'));

        // You can register other custom Fortify responses here if needed.
    }
}
