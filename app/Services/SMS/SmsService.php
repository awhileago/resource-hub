<?php

namespace App\Services\SMS;

use Illuminate\Support\Facades\Http;

class SmsService
{
    public function sendOtp($contactNumber, $otpCode)
    {
        $response = Http::withHeaders([
            'X-TXTBOX-Auth' => env('TXTBOX_API_KEY'),
        ])->post('https://ws-v2.txtbox.com/messaging/v1/sms/push', [
            'message' => "Your OTP code is: $otpCode",
            'number' => $contactNumber,
        ]);

        if ($response->failed()) {
            throw new \Exception('Failed to send OTP via Txtbox: ' . $response->body());
        }
    }
}
