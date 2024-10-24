<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SendMessageController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Validate request data
        $response = Http::withHeaders([
            'X-TXTBOX-Auth' => env('TXTBOX_API_KEY'), // use env for API key
        ])->post('https://ws-v2.txtbox.com/messaging/v1/sms/push', [
            'message' => "Your OTP code is Hello",
            'number' => '09325897763',
        ]);

        if ($response->failed()) {
            throw new \Exception('Failed to send OTP via Txtbox: ' . $response->body());
        }

        return $response->json();
    }

    function sendOtpViaSms($phoneNumber, $otpCode)
    {
        $response = Http::withHeaders([
            'X-TXTBOX-Auth' => env('TXTBOX_API_KEY'), // use env for API key
        ])->post('https://ws-v2.txtbox.com/messaging/v1/sms/push', [
            'message' => "Your OTP code is $otpCode",
            'number' => $phoneNumber,
        ]);

        if ($response->failed()) {
            throw new \Exception('Failed to send OTP via Txtbox: ' . $response->body());
        }

        return $response->json();
    }

    function generateOtpCode()
    {
        return rand(100000, 999999);  // 6-digit OTP
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
