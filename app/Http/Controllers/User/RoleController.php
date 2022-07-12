<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Role;

class RoleController extends Controller
{
	/**
	 * Return the roles
	 *
	 * @param   Request  $request
	 *
	 * @return  App\Models\Role
	 */
	public function list(Request $request)
	{
		$user = $request->user();

		// Check if the user has these permissions
		if (!$user || !$user->hasPermissions(['admin.roles'])) {
			return abort(401);
		}

		$roles = Role::select(['id', 'displayname', 'tag'])->get();

        if (!$roles) {
            return abort(404);
        }

		return $roles;
	}

	/**
	 * Return a specific role
	 *
	 * @param   Request  $request
	 *
	 * @return  App\Models\Role
	 */
	public function get(Request $request, $id)
	{
		$user = $request->user();

		// Check if the user has these permissions
		if (!$user || !$user->hasPermissions(['admin.roles', 'admin.role.edit'])) {
			return abort(401);
		}

		$role = Role::where('id', $id)->first();

        if (!$role) {
            return abort(404);
        }

		return $role;
	}

	/**
	 * Assign a new role to the user
	 *
	 * @param   Request  $request
	 * @param   string   $id
	 *
	 * @return  json
	 */
	public function assign(Request $request)
	{
		$user = $request->user();
		$id = $request->input('user_id');
		$role_id = $request->input('role_id');

		// check if the user has these permissions
		if (!$user || !$user->hasPermissions(['admin.user.roles', 'admin.user.roles.assign'])) {
			return abort(401);
		}

		// check if the user exists
		$target = User::where('id', $id)->first();

		if (!$target) {
			return abort(401);
		}

		// check if the role exists
		$role = Role::where(['id' => $role_id])->first();

		if (!$role) {
			return abort(404);
		}

		// Assign the user role
		$user_role = UserRole::create([
			'user_id' => $target->id,
			'role' => $role->tag
		]);

		// Log the action
		$user->log("admin.role.assign", [
			"user_id" => $target->id,
			"target_id" => $target->id,
			"role" => $role->tag,
		]);

		return response()->json(['message' => 'Assigned role #' . $target->id]);
	}

	/**
	 * Delete a specific role from a user
	 *
	 * @param   Request  $request
	 * @param   string   $id
	 *
	 * @return  json
	 */
	public function delete(Request $request)
	{
		$user = $request->user();
		$id = $request->input('user_id');
		$role_id = $request->input('role_id');

		// Check if the user has these permissions
		if (!$user || !$user->hasPermissions(['admin.user.roles', 'admin.user.roles.delete'])) {
			return abort(401);
		}

		// Get the user
		$target = User::where('id', $id)->first();

		if (!$target) {
			return abort(404);
		}

		// Get the role
		$role = $target->roles()->where('id', $role_id)->first();

		// Log the action
		$user->log("admin.role.delete", [
			"user_id" => $target->id,
			"target_id" => $target->id,
			"role" => $role->role,
		]);

		// Delete the role
		$role->delete();

		return response()->json(['message' => 'Deleted role #' . $target->id]);
	}
}
