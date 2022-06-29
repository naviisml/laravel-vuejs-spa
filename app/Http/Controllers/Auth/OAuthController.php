<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\EmailTakenException;
use App\Http\Controllers\Controller;
use App\Models\OAuthProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest:api');
    }

    /**
     * Redirect the user to the provider authentication page.
     */
    public function redirect(string $provider)
    {
        return response()->json([
            'url' => Socialite::driver($provider)->stateless()->redirect()->getTargetUrl(),
        ]);
    }

    /**
     * Obtain the user information from the provider.
     */
    public function handleCallback(string $provider)
    {
        $user = Socialite::driver($provider)->stateless()->user();
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

    /**
     * Find or create a user.
     */
    protected function findOrCreateUser(string $provider, SocialiteUser $user): User
    {
        $oauthProvider = OAuthProvider::where('provider', $provider)
            ->where('provider_user_id', $user->getId())
            ->first();

        if ($oauthProvider) {
            $oauthProvider->update([
                'access_token' => $user->token,
                'refresh_token' => $user->refreshToken,
            ]);

            $oauthProvider->user->update([
                'username' => $user->realname,
                'email' => $user->email
            ]);

            return $oauthProvider->user;
        }

        if (User::where('email', $user->getEmail())->exists()) {
            throw new EmailTakenException;
        }

        return $this->createUser($provider, $user);
    }

    /**
     * Create a new user.
     */
    protected function createUser(string $provider, SocialiteUser $data): User
    {
        $user = User::create([
            'username' => $data->realname,
            'email' => $data->email
        ]);

		// Create the OAuth provider entry
		$user->oauthProviders()->create([
			'provider' => $provider,
			'provider_user_id' => $data->id,
			'access_token' => $data->token,
			'refresh_token' => $data->refreshToken,
		]);

		$user->markEmailAsVerified();

        return $user;
    }
}
