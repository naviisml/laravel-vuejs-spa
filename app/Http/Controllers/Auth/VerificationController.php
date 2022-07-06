<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;

class VerificationController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('throttle:5,1')->only('verify', 'resend');
    }

    /**
     * Mark the user's email address as verified.
     */
    public function verify(Request $request, $user_id, $hash)
    {
		if (!$request->hasValidSignature()) {
			return response()->json(["msg" => "Invalid url."], 401);
		}

		$user = User::findOrFail($user_id);

		if (!$user->hasVerifiedEmail()) {
			$user->markEmailAsVerified();
		}

        return redirect()->back();
	}

    /**
     * Resend the email verification notification.
     */
    public function resend(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (is_null($user)) {
            throw ValidationException::withMessages([
                'email' => [trans('verification.user')],
            ]);
        }

        if ($user->hasVerifiedEmail()) {
            throw ValidationException::withMessages([
                'email' => [trans('verification.already_verified')],
            ]);
        }

        $user->sendEmailVerificationNotification();

		// for api's:
		// return response()->json(['status' => trans('verification.sent')]);
        return redirect()->back();
    }
}
