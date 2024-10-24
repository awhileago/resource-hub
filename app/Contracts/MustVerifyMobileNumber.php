<?php

namespace App\Contracts;

interface MustVerifyMobileNumber
{
    /**
     * Determine if the user's mobile number has been verified.
     *
     * @return bool
     */
    public function hasVerifiedMobileNumber();

    /**
     * Mark the given user's mobile number as verified.
     *
     * @return bool
     */
    public function markMobileNumberAsVerified();

    /**
     * Send the mobile number verification notification.
     *
     * @return void
     */
    public function sendMobileNumberVerificationNotification();
}
