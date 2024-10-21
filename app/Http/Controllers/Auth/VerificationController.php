<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('auth:api')->except(['verify']);
//        $this->middleware('signed')->only('verify');
//        $this->middleware('throttle:6,1')->only('verify', 'resend');
//    }

    /**
     * Verify email
     *
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function verify($user_id, Request $request)
    {
        if (! $request->hasValidSignature()) {
            return response(['message' => 'Invalid Email Verification']);
        }

        $user = User::findOrFail($user_id);

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return response()->json(['message' => 'Your e-mail is verified. You can now login.']);
        //return redirect()->to('/');
    }

    /**
     * Resend email verification link
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function resend()
    {
        if (auth()->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Already verified']);
        }

        auth()->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Email verification link sent on your email id.']);
    }
}
