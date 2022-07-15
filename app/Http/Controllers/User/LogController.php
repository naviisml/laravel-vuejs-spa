<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class LogController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except('logout');
    }

	/**
	 * Return the users' logs
	 *
	 * @param   Request  $request
	 * @param   int   	 $id
	 *
	 * @return  App\Models\Log
	 */
    public function get(Request $request, $id = null)
    {
		$user = $request->user();
        $target = $id != null ? User::find($id) : $user;

		// check if we have the permissions to update other users (if neccesary)
		if ($target->id != $user->id && !$user->hasPermissions(['admin.users', 'admin.user.logs'])) {
			return abort(401, "You are not authorized to view this content");
		}

		// check if $target exists
		if (!$target) {
			return abort(404, "The resource that you are looking for doesn't exist");
		}

		// Get the logs
        $logs = $target->logs()->orderBy('id', 'DESC')->paginate(20);

        return response()->json($logs);
    }
}
