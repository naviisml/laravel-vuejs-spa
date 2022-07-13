<?php

namespace Naviisml\Laravel\OpenID\Contracts;

interface Factory
{
    /**
     * Get an OAuth provider implementation.
     *
     * @param  string  $driver
     *
     * @return \Naviisml\Laravel\OpenID\Contracts\Provider
     */
    public function driver($driver = null);
}
