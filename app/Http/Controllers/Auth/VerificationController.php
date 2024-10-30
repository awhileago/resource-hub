<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VerificationController extends BaseController
{
//    public function __construct()
//    {
//        $this->middleware('auth:api')->except(['verify']);
//        $this->middleware('signed')->only('verify');
//        $this->middleware('throttle:6,1')->only('verify', 'resend');
//    }
    protected $mobileOnlyDomains = [
        '@student.tsu.edu.ph',
        '@tarlac.sti.edu.ph',
        '@cldhei.edu.ph',
        '@pwutarlac.edu.ph'
    ];

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

        if ($this->isMobileOnlyDomain($user->email)) {
            $user->update(['user_verified' => 1, 'is_active' => 1]);
            return response()->json(['message' => 'Your e-mail is verified and activated. You can now login.']);
        } else {
            return response()->json(['message' => 'Your email has been verified. Please wait for the admin to verify your documents before your account is activated.']);
        }


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

    public function checkEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users',
        ]);

        return $this->sendResponse($validated['email'], 'This email is available');
    }

    protected function isMobileOnlyDomain($emailDomain)
    {
        foreach ($this->mobileOnlyDomains as $domain) {
            if (Str::endsWith($emailDomain, $domain)) {
                return true;
            }
        }
        return false;
    }
}
