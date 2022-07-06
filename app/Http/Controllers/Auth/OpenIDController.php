<?php

namespace App\Http\Controllers\Auth;

use Invisnik\LaravelSteamAuth\SteamAuth;
use App\Http\Controllers\Controller;
use App\Models\OAuthProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
        $this->middleware(['api', 'web']);

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
    public function handleCallback(Request $request, string $provider)
    {
        if ($this->steam->validate()) {
            $user = $this->steam->getUserInfo();

            if (!is_null($user)) {
                $user = $this->findOrCreateUser($provider, $user);

                $this->guard()->setToken(
                    $token = $this->guard()->login($user)
                );

                // log the user in on the web guard
                Auth::guard('web')->login($user, true);

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
            // Create the OAuth provider entry
            $user->oauthProviders()->update([
                'provider_user_data' => json_encode($user, true),
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
        // check if the user exists, to link the account to that user, or
        // create a new user.
        // _NOTE: this is done through cookies, which kinda defeats the purpose
        // of the api, but this is only used at a *side* service_
        if (!($user = Auth::guard('web')->user())) {
            $user = User::create([
                'username' => $data->personaname,
                'email' => "{$data->personaname}@steamcommunity.com"
            ]);
        }

		// Create the OAuth provider entry
		$user->oauthProviders()->create([
			'provider' => $provider,
			'provider_user_id' => $data->steamID64,
            'provider_user_data' => json_encode($data, true),
			'access_token' => null,
			'refresh_token' => null,
		]);

		$user->markEmailAsVerified();

        return $user;
    }
}
