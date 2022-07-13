<?php

namespace Naviisml\Laravel\OpenID\Facades;

use Illuminate\Support\Facades\Facade;
use Naviisml\Laravel\OpenID\Contracts\Factory;

/**
 * @method static \Naviisml\Laravel\OpenID\Contracts\Provider driver(string $driver = null)
 *
 * @see \Naviisml\Laravel\OpenID\OpenIDManager
 */
class OpenID extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Factory::class;
    }
}
