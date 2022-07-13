<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\EmailTakenException;
use App\Http\Controllers\Controller;
use App\Models\OAuthProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Naviisml\Laravel\OpenID\Contracts\User as SocialiteUser;
use Naviisml\Laravel\OpenID\Facades\Socialite;

class OAuthController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['web', 'api']);
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

        // log the user in on the web guard
        Auth::guard('web')->login($user, true);

        return view('oauth/callback', [
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->getPayload()->get('exp') - time(),
        ]);
    }

    /**
     * Find or create a user.
     */
    protected function findOrCreateUser(string $provider, SocialiteUser $socialite): User
    {
        $oauthProvider = OAuthProvider::where('provider', $provider)
            ->where('provider_user_id', $socialite->getId())
            ->first();

        if ($oauthProvider) {
            $user = $oauthProvider->user;

            $oauthProvider->update([
                'provider_user_data' => json_encode($socialite, true),
                'access_token' => $socialite->token,
                'refresh_token' => $socialite->refreshToken,
            ]);

            // Log the action
            $user->log('user.login', ['provider' => $provider]);

            return $user;
        }

        return $this->createUser($provider, $socialite);
    }

    /**
     * Create a new user.
     */
    protected function createUser(string $provider, SocialiteUser $socialite): User
    {
        // check if the user exists, to link the account to that user, or
        // create a new user.
        // NOTE: this is done through cookies, which kinda defeats the purpose
        // of the api, but this is only used as a *side* service (I need to change this..)
        if (!($user = Auth::guard('web')->user())) {
            if (User::where('email', $socialite->getEmail())->exists()) {
                throw new EmailTakenException;
            }

            $user = User::create([
                'username' => $socialite->getName(),
                'email' => $socialite->getEmail()
            ]);

            // Assign the default role to the user
            $user->roles()->create([
                'user_id' => $user->id,
                'role' => config('roles.default.tag')
            ]);

            // Log the action
            $user->log('user.create', ['provider' => $provider]);
        } else {
            // Log the action
            $user->log('user.link', ['provider' => $provider]);
        }

		// Create the OAuth provider entry
		$user->oauthProviders()->create([
			'provider' => $provider,
			'provider_user_id' => $socialite->getId(),
            'provider_user_data' => json_encode($socialite, true),
			'access_token' => $socialite->token,
			'refresh_token' => $socialite->refreshToken,
		]);

		$user->markEmailAsVerified();

        return $user;
    }
}
