<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request)
    {
		$user = $request->user();

        $this->validate($request, [
            'password' => 'required|confirmed|min:6',
			'old_password' => [
				'required', function ($attribute, $value, $fail) {
					$user = request()->user();
					
					if (!Hash::check($value, $user->password)) {
						$fail('Old password is incorrect.');
					}
				},
			],
        ]);

        $user->update([
            'password' => bcrypt($request->password),
        ]);

		// Log the action
		$user->log("password.update");

        return response()->json(null, 204);
    }
}
