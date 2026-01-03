<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Line\Provider as LineProvider;

class SocialiteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Socialite::extend('line', function ($app) {
            $config = $app['config']['services.line'];

            return Socialite::buildProvider(LineProvider::class, $config);
        });
    }
}
