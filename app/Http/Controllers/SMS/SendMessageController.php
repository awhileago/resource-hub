<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Posting\Posting;
use App\Models\Posting\PostingApplication;
use App\Models\Posting\PostingMessageTemplate;
use App\Models\SMS\SmsLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

    public function sendBulkMessages(Request $request)
    {
        // Get the posting
        $posting = Posting::findOrFail($request->posting_id);

        // Get the message template
        $message = PostingMessageTemplate::where('posting_id', $request->posting_id)->firstOrFail()->message;
        // Get all approved applicants
        $approvedApplicants = PostingApplication::where('posting_id', $request->posting_id)
            ->whereDoesntHave('smsLogs')
            ->where('is_approved', 1) // Assuming 'approved' status
            ->get();
        foreach ($approvedApplicants as $applicant) {
            $contactNumber = $applicant->user->contact_number;
            // Customize the message if needed (e.g., adding applicant name)
            $message = str_replace('{name}', $applicant->user->first_name . ' ' . $applicant->user->last_name, $message);
            // Send SMS
            try {
                $this->sendSms($contactNumber, $message);
                SmsLog::create([
                    'posting_application_id' => $applicant->id,
                    'user_id' => $applicant->user->id,
                    'contact_number' => $contactNumber,
                    'message' => $message,
                    'status' => 'success',
                ]);
            } catch (\Exception $e) {
                // Log failed SMS send
                SmsLog::create([
                    'posting_application_id' => $applicant->id,
                    'user_id' => $applicant->user->id,
                    'contact_number' => $contactNumber,
                    'message' => $message,
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
                // Log or handle failed SMS sending
                Log::error("Failed to send SMS to {$contactNumber}: {$e->getMessage()}");
            }
        }

        return response()->json(['message' => 'Messages sent successfully!']);
    }

// Helper function to send SMS
    private function sendSms($contactNumber, $message)
    {
        $response = Http::withHeaders([
            'X-TXTBOX-Auth' => env('TXTBOX_API_KEY'),
        ])->post(env('TXTBOX_URL'), [
            'message' => $message,
            'number' => $contactNumber,
        ]);

        if ($response->failed()) {
            throw new \Exception('Failed to send SMS via Txtbox: ' . $response->body());
        }
    }
}
