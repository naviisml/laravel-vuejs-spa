<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use App\Exceptions\EmailTakenException;
use App\Http\Controllers\Controller;
use App\Models\OAuthProvider;
use App\Models\User;

class OAuthController extends Controller
{
    use AuthenticatesUsers;

    /**
     * @var array
     */
    protected $provider = null;

    /**
     * @var string
     */
    protected $driver = null;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['web', 'api']);
    }

    /**
     * Retrieve and return the redirect_url for a specific driver
     *
     * @param   string  $driver
     *
     * @return  \Illuminate\Support\Facades\Response
     */
    public function redirect(string $driver = null)
    {
        // set the provider
        if (!is_null($driver)) {
            $this->setDriver($driver);
        }

        $url = Socialite::driver($this->driver)->stateless()->redirect()->getTargetUrl();

        return response()->json(['url' => $url]);
    }

    /**
     * The authentication callback
     *
     * @param   string  $driver
     *
     * @return  \Illuminate\Support\Facades\View
     */
    public function handleCallback(string $driver)
    {
        // set the driver
        if (!is_null($driver)) {
            $this->setDriver($driver);
        }

        $user = Socialite::driver($this->driver)->stateless()->user();
        $user = $this->findOrCreateUser($user);

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
     * Find, create or link a user
     *
     * @param   SocialiteUser  $socialite
     *
     * @return  User
     */
    protected function findOrCreateUser(SocialiteUser $socialite): User
    {
        $this->provider = OAuthProvider::where('provider', $this->driver)
            ->where('provider_user_id', $socialite->getId())
            ->first();

        // check if the provider is not linked, and a user is logged in
        if (($user = Auth::guard('web')->user()) && !$this->provider) {
            return $this->linkUser($socialite, $user);
        }

        // check if the account linked to another account
        if ($user && $this->provider && $this->provider->user_id !== $user->id) {
            return abort(403);
        }

        // check if the provider already exists
        if ($this->provider && ($user = $this->provider->user)) {
            return $this->updateUser($socialite, $user);
        }

        // create
        return $this->createUser($socialite);
    }

    /**
     * Create a new user
     *
     * @param   SocialiteUser  $socialite
     *
     * @return  User
     */
    protected function createUser(SocialiteUser $socialite): User
    {
        if (User::where('email', $socialite->getEmail())->exists()) {
            throw new EmailTakenException;
        }

        $user = User::create([
            'username' => $socialite->getNickname(),
            'email' => $socialite->getEmail() ?? ''
        ]);

        // Assign the default role to the user
        $user->roles()->create([
            'user_id' => $user->id,
            'role' => config('roles.default.tag')
        ]);

        // Log the action
        $user->log('user.create', ['provider' => $this->driver]);

		// Create the OAuth provider entry
		$user = $this->linkUser($socialite, $user);

        // verify the email
		$user->markEmailAsVerified();

        return $user;
    }

    /**
     * Update a existing user
     *
     * @param   SocialiteUser  $socialite
     * @param   User           $user
     *
     * @return  User
     */
    protected function updateUser(SocialiteUser $socialite, User $user): User
    {
        $this->provider->update([
            'provider_user_data' => $socialite,
            'access_token' => $socialite->token,
            'refresh_token' => $socialite->refreshToken,
        ]);

        // Log the action
        $user->log('user.login', ['provider' => $this->driver]);

        return $user;
    }

    /**
     * Link a driver to a existing user
     *
     * @param   SocialiteUser  $socialite
     * @param   User           $user
     *
     * @return  User
     */
    protected function linkUser(SocialiteUser $socialite, User $user): User
    {
		$this->provider = $user->oauthProviders()->create([
			'provider' => $this->driver,
			'provider_user_id' => $socialite->getId(),
            'provider_user_data' => $socialite,
			'access_token' => $socialite->token,
			'refresh_token' => $socialite->refreshToken,
		]);

        // Log the action
        $user->log('user.link', ['provider' => $this->driver]);

        return $user;
    }

    /**
     * Set the driver
     *
     * @param   string  $driver
     *
     * @return  $this->driver
     */
    protected function setDriver($driver = null)
    {
        if (!is_null($driver)) {
            $this->driver = $driver;
        }

        return $this->driver;
    }
}
