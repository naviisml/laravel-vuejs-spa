<?php

namespace Naviisml\Laravel\OpenID\Contracts;

interface Provider
{
    /**
     * Redirect the user to the authentication page for the provider.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Illuminate\Http\RedirectResponse
     */
    public function redirect();

    /**
     * Get the User instance for the authenticated user.
     *
     * @return \Naviisml\Laravel\OpenID\Contracts\User
     */
    public function user();
}
