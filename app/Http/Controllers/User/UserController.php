<?php

namespace App\Http\Controllers\User;

use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except('logout');
    }

	/**
	 * Get the authenticated user
	 *
	 * @param   Request  $request
	 *
	 * @return  App\Model\User
	 */
    public function current(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Edit the authenticated user
     *
     * @param   Request  $request
     *
	 * @return  App\Model\User
     */
    public function update(Request $request)
    {
        // retrieve the user
        $user = $request->user();

        // check if the email input exists
        $this->updateEmail($request);

        // return the user
        return response()->json($user);
    }

    /**
     * Attempt to update the email
     *
     * @param   Request  $request
     *
     * @return  bool
     */
    protected function attemptUpdateEmail(Request $request): bool
    {
        // check if the input exists
        if (($email = $request->input('email'))) {
            // validate the request
            $request->validate([
                'email' => 'email|unique:users|max:255'
            ]);

            // update the user
            $user->email = $email;
            $user->email_verified_at = NULL;

            // check if user verified email
            if ($user instanceof MustVerifyEmail) {
                $user->sendEmailVerificationNotification();
            }

            return true;
        }

        return false;
    }
}
