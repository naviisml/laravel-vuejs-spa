<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Naviisml\IntraApi\Facades\IntraOAuth;
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
			return abort(401);
		}

		// check if $target exists
		if (!$target) {
			return abort(404);
		}

		// Get the logs
		$logs = $target->logs()->orderBy('id', 'DESC')->paginate(30);

        return response()->json($logs);
    }
}
