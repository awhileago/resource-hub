<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
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
        ResetPassword::createUrlUsing(function ($user, string $token) {
            $url = 'http://resourcehub.test/api/reset-password?token=';
            if (config('app.env') != 'local') {
                $url = config('app.frontend_url').'/#/reset-password?token=';
            }

            return $url.$token.'&email='.urlencode($user->email);
        });
    }
}
