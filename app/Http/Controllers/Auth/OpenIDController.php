<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Invisnik\LaravelSteamAuth\SteamAuth;
use App\Http\Controllers\Controller;
use App\Models\OAuthProvider;
use App\Models\User;
use Auth;

class OpenIDController extends Controller
{
    use AuthenticatesUsers;

    /**
     * The SteamAuth instance.
     *
     * @var SteamAuth
     */
    protected $steam;

    /**
     * Create a new controller instance.
     */
    public function __construct(SteamAuth $steam)
    {
        $this->middleware('guest:api');

        $this->steam = $steam;
    }

    /**
     * Redirect the user to the provider authentication page.
     */
    public function redirect(string $provider)
    {
        return response()->json([
            'url' => $this->steam->getAuthUrl(),
        ]);
    }

    /**
     * Obtain the user information from the provider.
     */
    public function handleCallback(string $provider)
    {
        if ($this->steam->validate()) {
            $user = $this->steam->getUserInfo();

            if (!is_null($user)) {
                $user = $this->findOrCreateUser($provider, $user);

                $this->guard()->setToken(
                    $token = $this->guard()->login($user)
                );

                return view('oauth/callback', [
                    'token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => $this->guard()->getPayload()->get('exp') - time(),
                ]);
            }
        }

        return redirect($this->steam->getAuthUrl());
    }

    /**
     * Find or create a user.
     */
    protected function findOrCreateUser(string $provider, $user): User
    {
        $oauthProvider = OAuthProvider::where('provider', $provider)
            ->where('provider_user_id', $user->steamID64)
            ->first();

        if ($oauthProvider) {
            $oauthProvider->user->update([
                'username' => $user->personaname,
                'email' => "{$user->personaname}@steamcommunity.com"
            ]);

            return $oauthProvider->user;
        }

        return $this->createUser($provider, $user);
    }

    /**
     * Create a new user.
     */
    protected function createUser(string $provider, $data): User
    {
        $user = User::create([
            'username' => $data->personaname,
            'email' => "{$data->personaname}@steamcommunity.com"
        ]);

		// Create the OAuth provider entry
		$user->oauthProviders()->create([
			'provider' => $provider,
			'provider_user_id' => $data->steamID64,
			'access_token' => null,
			'refresh_token' => null,
		]);

		$user->markEmailAsVerified();

        return $user;
    }
}
