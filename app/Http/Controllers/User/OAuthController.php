<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class OAuthController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except('logout');
    }

	/**
	 * Unlink a account from the user
	 *
	 * @param   Request  $request
	 * @param   int   	 $id
	 *
	 * @return  App\Models\Log
	 */
    public function unlink(Request $request, $id = null)
    {
		$user = $request->user();
        $target = $id != null ? User::find($id) : $user;

		// check if we have the permissions to update other users (if neccesary)
		if ($target->id != $user->id && !$user->hasPermissions(['admin.users', 'admin.user.edit'])) {
			return abort(401, "You are not authorized to view this content");
		}

		// check if $target exists
		if (!$target) {
			return abort(404, "The resource that you are looking for doesn't exist");
		}

		// work the magic

        return response()->json(200);
    }
}
