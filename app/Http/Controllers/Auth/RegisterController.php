<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['guest', 'guest:api']);
    }

    /**
     * The user has been registered.
     */
    /**
     * The user has been registered.
     *
     * @param   \Illuminate\Http\Request    $request
     * @param   \App\Models\User            $user
     *
     * @return  \App\Models\User
     */
    protected function registered(Request $request, User $user)
    {
        return response()->json($user);
    }

    /**
     * Validare the data
     *
     * @param   array  $data
     *
     * @return  \Illuminate\Validation\ValidationException
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|max:255',
            'email' => 'required|email:filter|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param   array  $data
     *
     * @return  \App\Models\User
     */
    protected function create(array $data): User
    {
        $user = User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

		// Assign the default role to the user
		$user->roles()->create([
			'user_id' => $user->id,
			'role' => config('roles.default.tag')
		]);

		// Log the action
		$user->log('user.create');

		// check if user verified email
        if ($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail()) {
			throw ValidationException::withMessages(['Check your inbox for a verification mail.']);

		    $user->sendEmailVerificationNotification();
        }

		return $user;
    }
}
