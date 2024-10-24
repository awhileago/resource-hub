<?php

namespace App\Traits;

use App\Models\SMS\Otp;
use App\Services\SMS\SmsService;

trait VerifiesMobileNumber
{
    /**
     * Determine if the user's mobile number has been verified.
     *
     * @return bool
     */
    public function hasVerifiedMobileNumber()
    {
        return ! is_null($this->mobile_verified_at);
    }

    /**
     * Mark the given user's mobile number as verified.
     *
     * @return bool
     */
    public function markMobileNumberAsVerified()
    {
        return $this->forceFill([
            'mobile_verified_at' => now(),
        ])->save();
    }

    /**
     * Send the mobile number verification notification (in this case, OTP).
     *
     * @return void
     */
    public function sendMobileNumberVerificationNotification()
    {
        $otpCode = rand(100000, 999999);

        // Create the OTP record
        Otp::create([
            'user_id' => $this->id,
            'otp_code' => $otpCode,
            'is_verified' => false,
            'expires_at' => now()->addMinutes(5),
        ]);

        // Send OTP via SMS
        app(SmsService::class)->sendOtp($this->contact_number, $otpCode);
    }
}
