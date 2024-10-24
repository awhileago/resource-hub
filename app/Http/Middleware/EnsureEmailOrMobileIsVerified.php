<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailOrMobileIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user's email or mobile number is verified
        if (!$request->user() ||
            (!$request->user()->hasVerifiedEmail() && !$request->user()->hasVerifiedMobileNumber())) {
            return response()->json(['message' => 'Neither your email nor mobile number is verified.'], 403);
        }

        return $next($request);
    }
}
