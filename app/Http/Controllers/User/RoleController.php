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
     * @var array
     */
    protected $role;

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

		$this->roles = Role::select(['id', 'displayname', 'tag'])->get();

        if (!$this->roles) {
            return abort(404);
        }

		return $this->roles;
	}

	/**
	 * Return a specific role
	 *
	 * @param   Request  $request
     * @param   integer   $id
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

		$this->role = Role::where('id', $id)->first();

        if (!$this->role) {
            return abort(404);
        }

		return $this->role;
	}

	/**
	 * Create a role
	 *
	 * @param   Request  $request
	 *
	 * @return  App\Models\Role
	 */
    public function create(Request $request)
    {
		$user = $request->user();

		// check if we have the permissions to update other users (if neccesary)
		if (!$user->hasPermissions(['admin.roles'])) {
			return abort(401);
		}

        // validate the request input
        $this->validator($request);

		// Log the action
		$user->log("role.create", [
            'tag' => $request->tag
        ]);

        // update the account
        $this->role = Role::create([
            'displayname' => $request->displayname,
            'tag' => $request->tag,
            'permissions' => $request->permissions,
            'override' => $request->override,
            'default' => false
        ]);

        return response()->json($this->role);
    }

	/**
	 * Update a role
	 *
	 * @param   Request  $request
     * @param   integer   $id
	 *
	 * @return  App\Models\Role
	 */
    public function update(Request $request, $id)
    {
		$user = $request->user();

		// check if we have the permissions to update other users (if neccesary)
		if (!$user->hasPermissions(['admin.roles'])) {
			return abort(401);
		}

        // check if the role exists
		$this->role = Role::where('id', $id)->first();

        if (!$this->role) {
            return abort(404);
        }

        // validate the request input
        $this->validator($request);

		// Log the action
		$user->log("role.update", [
            'tag' => $request->tag
        ]);

        // update the account
        $this->role->update($request->only('displayname', 'tag', 'permissions', 'override'));

        return response()->json($this->role);
    }

    /**
     * Delete a Role
     *
     * @param   Request  $request
     * @param   integer   $id
     *
     * @return  void
     */
    public function remove(Request $request, $id)
    {
		$user = $request->user();

		// check if we have the permissions to update other users (if neccesary)
		if (!$user->hasPermissions(['admin.roles'])) {
			return abort(401);
		}

        // check if the role exists
		$this->role = Role::where('id', $id)->first();

        if (!$this->role) {
            return abort(404);
        }

        // validate the request input
        $this->validator($request);

		// Log the action
		$user->log("role.remove", [
            'tag' => $request->tag
        ]);

        // update the account
        $this->role->delete();

        return abort(200);
    }

    /**
     * Validate the form data
     *
     * @param   Request  $request
     *
     * @return  Validator
     */
    protected function validator(Request $request)
    {
        return $this->validate($request, [
            'displayname' => 'required|string',
            'tag' => [
                'required', 'unique:roles,tag' . ($this->role ? ",{$this->role->id}" : ''),
                function($attribute, $value, $fail) {
                    if ($this->role && $this->role->default == true && $this->role->tag != $value) {
                        $fail("This role is protected, so the role tag cannot be changed.");
                    }
                }
            ],
            'permissions' => 'required|array',
            'override' => 'required',
        ]);
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
		$this->role = Role::where(['id' => $role_id])->first();

		if (!$this->role) {
			return abort(404);
		}

		// Assign the user role
		$user_role = UserRole::create([
			'user_id' => $target->id,
			'role' => $this->role->tag
		]);

		// Log the action
		$user->log("role.assign", [
			"role" => $this->role->tag,
		], $target->id);

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
	public function unassign(Request $request)
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
		$this->role = $target->roles()->where('id', $role_id)->first();

		// Log the action
		$user->log("role.unassign", [
			"role" => $this->role->role,
		], $target->id);

		// Delete the role
		$this->role->delete();

		return response()->json(['message' => 'Deleted role #' . $target->id]);
	}
}
