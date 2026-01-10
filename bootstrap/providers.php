<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    App\Providers\SocialiteServiceProvider::class,
    Laravel\Socialite\SocialiteServiceProvider::class,
    SocialiteProviders\Manager\ServiceProvider::class,
];
