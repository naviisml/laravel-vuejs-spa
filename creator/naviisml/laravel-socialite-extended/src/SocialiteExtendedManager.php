<?php

namespace Naviisml\Laravel\SocialiteExtended;

use Naviisml\Laravel\SocialiteExtended\Providers\DiscordProvider;
use Laravel\Socialite\SocialiteManager;
use Laravel\Socialite\Contracts\Factory;

class SocialiteExtendedManager extends SocialiteManager
{
    /**
     * Register the provider.
     *
     * @return \Laravel\Socialite\Two\AbstractProvider
     *
     * @throws BindingResolutionException
     */
    public function boot()
    {
        $socialite = $this->app->make(Factory::class);

        $socialite->extend('discord', function () use ($socialite) {
            $config = config('services.discord');

            return $socialite->buildProvider(DiscordProvider::class, $config);
        });
    }
}
