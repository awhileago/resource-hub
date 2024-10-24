<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\SMS\Otp;
use App\Models\User;
use App\Services\SMS\SmsService;
use Illuminate\Http\Request;

class OtpController extends BaseController
{
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'contact_number' => 'required|string',
            'otp_code' => 'required|string',
        ]);

        // Find the OTP record associated with the user's contact number
        $otp = Otp::where('otp_code', $request->otp_code)
            ->whereHas('user', function ($query) use ($request) {
                $query->where('contact_number', $request->contact_number);
            })
            ->first();

        if (!$otp) {
            return response()->json(['message' => 'Invalid OTP or contact number.'], 400);
        }

        // Check if the OTP has expired
        if ($otp->expires_at < now()) {
            return response()->json(['message' => 'OTP has expired.'], 400);
        }

        // Mark OTP as verified
        $otp->is_verified = true;
        $otp->save();

        // Mark the user's mobile number as verified
        $user = $otp->user;
        $user->markMobileNumberAsVerified(); // This will set mobile_verified_at to the current timestamp

        return response()->json(['message' => 'Mobile number verified successfully.']);
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'contact_number' => 'required|string',
        ]);

        $user = User::where('contact_number', $request->contact_number)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $existingOtp = Otp::where('user_id', $user->id)
            ->where('is_verified', false)
            ->first();

        if ($existingOtp) {
            if ($existingOtp->expires_at < now()) {
                $otpCode = rand(100000, 999999);
                $existingOtp->otp_code = $otpCode;
                $existingOtp->expires_at = now()->addMinutes(5);
                $existingOtp->save();
            } else {
                return response()->json(['message' => 'Please wait for the existing OTP to expire.'], 429);
            }
        } else {
            $otpCode = rand(100000, 999999);
            Otp::create([
                'user_id' => $user->id,
                'otp_code' => $otpCode,
                'is_verified' => false,
                'expires_at' => now()->addMinutes(5),
            ]);
        }

        app(SmsService::class)->sendOtp($user->contact_number, $otpCode);

        return response()->json(['message' => 'New OTP sent via SMS.']);
    }

}
