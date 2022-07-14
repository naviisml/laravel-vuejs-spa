<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

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
	 * List the users paginated by 30
	 *
	 * @param   Request  $request
	 *
	 * @return  App\Model\User
	 */
    public function list(Request $request)
    {
		$user = $request->user();

		// Check if the user has these permissions
		if (!$user->hasPermission('admin.users')) {
			return abort(401);
		}

		// Get the user
		$users = User::paginate(30);

        return response()->json($users);
    }

	/**
	 * Return the user's account data
	 *
	 * @param   Request  $request
	 *
	 * @return  App\Model\User
	 */
    public function get(Request $request, $id = null)
    {
		$user = $request->user();
        $target = $id != null ? User::find($id) : $user;

		// check if we have the permissions to update other users (if neccesary)
		if ($target->id != $user->id && !$user->hasPermissions(['admin.users', 'admin.user.get'])) {
			return abort(401);
		}

		// check if $target exists
		if (!$target) {
			return abort(404);
		}

        return response()->json($target);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request, $id = null)
    {
		$user = $request->user();
        $target = $id != null ? User::find($id) : $user;

		// check if we have the permissions to update other users (if neccesary)
		if ($target->id != $user->id && !$user->hasPermissions(['admin.users', 'admin.user.edit'])) {
			return abort(401);
		}

		// check if $target exists
		if (!$target) {
			return abort(404);
		}

        // validate the request input
        $this->validate($request, [
            'email' => 'required|email|unique:users,email,' . $target->id
        ]);

		// Log the action
		$user->log(("profile.update"), [
            'email' => [
                'old' => $target->email,
                'new' => $request->email
            ]
        ], $target->id);

        // update the account
        $target->update($request->only('email'));

        return response()->json($target);
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
