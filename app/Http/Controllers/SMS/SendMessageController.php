<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Mail\ApplicantNotificationMail;
use App\Models\Posting\Posting;
use App\Models\Posting\PostingApplication;
use App\Models\Posting\PostingMessageTemplate;
use App\Models\SMS\SmsLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
        $message = PostingMessageTemplate::where('posting_id', $request->posting_id)->firstOrFail();
        // Get all approved applicants
        $approvedApplicants = PostingApplication::where('posting_id', $request->posting_id)
            ->where('is_applied', 1)
            ->whereNotNull('is_approved')
            ->where(function ($q) {
                // No smsLogs
                $q->whereDoesntHave('smsLogs')
                    ->orWhereHas('smsLogs', function ($subQuery) {
                        $subQuery->where(function ($q) {
                            // Status failed and for_interview is 1
                            $q->where('status', 'failed')
                                ->where(function ($q) {
                                    $q->where('for_interview', '!=', 1)
                                        ->orWhereNull('for_interview');
                                });
                        })->orWhere(function ($q) {
                            // Status success and for_interview is not 1 or is NULL
                            $q->where('status', 'success')
                                ->where('for_interview', 1);
                        });
                    });
            })
            ->get();

        foreach ($approvedApplicants as $applicant) {
            $contactNumber = $applicant->user->contact_number;
            // Customize the message if needed (e.g., adding applicant name)
            $mobileMessage = $applicant->is_approved == 1 ? $message->mobile_message_approved : $message->mobile_message_rejected;
            $emailMessage = $applicant->is_approved == 1 ? $message->email_message_approved : $message->email_message_rejected;

            $mobileMessage = str_replace('{name}', $applicant->user->first_name . ' ' . $applicant->user->last_name, $mobileMessage);
            $emailMessage = str_replace('{name}', $applicant->user->first_name . ' ' . $applicant->user->last_name, $emailMessage);
            // Send SMS
            try {
                $this->sendSms($contactNumber, $mobileMessage);
                SmsLog::updateOrCreate([
                    'posting_application_id' => $applicant->id,
                    'user_id' => $applicant->user->id,
                    'for_interview' => 0,
                    ],
                    [
                    'contact_number' => $contactNumber,
                    'message' => $mobileMessage,
                    'status' => 'success',
                    'error_message' => '',
                ]);
                Mail::to($applicant->user->email)
                    ->send(new ApplicantNotificationMail(['subject' => $posting->title, 'message' => $emailMessage]));
            } catch (\Exception $e) {
                // Log failed SMS send
                SmsLog::updateOrCreate([
                    'posting_application_id' => $applicant->id,
                    'user_id' => $applicant->user->id,
                    'for_interview' => 0,
                    ],
                    [
                    'contact_number' => $contactNumber,
                    'message' => $mobileMessage,
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
                // Log or handle failed SMS sending
                Log::error("Failed to send SMS to {$contactNumber}: {$e->getMessage()}");
            }
        }

        return response()->json(['message' => 'Messages sent successfully!']);
    }

    public function sendInterviewMessages(Request $request)
    {
        // Get the posting
        $posting = Posting::findOrFail($request->posting_id);

        // Get the message template
        $message = PostingMessageTemplate::where('posting_id', $request->posting_id)->firstOrFail();
        // Get all approved applicants
        $applicants = PostingApplication::where('posting_id', $request->posting_id)
            ->where('is_applied', 1)
            ->whereNotNull('is_approved')
            ->where(function ($q) {
                // No smsLogs
                $q->whereDoesntHave('smsLogs')
                    ->orWhereHas('smsLogs', function ($subQuery) {
                        $subQuery->where(function ($q) {
                            // Status failed and for_interview is 1
                            $q->where('status', 'failed')
                                ->where('for_interview', 1);
                        })->orWhere(function ($q) {
                            // Status success and for_interview is not 1 or is NULL
                            $q->where('status', 'success')
                                ->where(function ($q) {
                                    $q->where('for_interview', '!=', 1)
                                        ->orWhereNull('for_interview');
                                });
                        });
                    });
            })
            ->get();

        foreach ($applicants as $applicant) {
            $contactNumber = $applicant->user->contact_number;
            // Customize the message if needed (e.g., adding applicant name)
            $interviewMessage = $message->interview_message;

            $interviewMessage = str_replace('{name}', $applicant->user->first_name . ' ' . $applicant->user->last_name, $interviewMessage);
            // Send SMS
            try {
                $this->sendSms($contactNumber, $interviewMessage);
                SmsLog::updateOrCreate([
                    'posting_application_id' => $applicant->id,
                    'user_id' => $applicant->user->id,
                    'for_interview' => 1,
                    ],
                    [
                        'contact_number' => $contactNumber,
                        'message' => $interviewMessage,
                        'status' => 'success',
                        'error_message' => '',
                    ]);

            } catch (\Exception $e) {
                // Log failed SMS send
                SmsLog::updateOrCreate([
                    'posting_application_id' => $applicant->id,
                    'user_id' => $applicant->user->id,
                    'for_interview' => 1,
                    ],
                    [
                        'contact_number' => $contactNumber,
                        'message' => $interviewMessage,
                        'status' => 'failed',
                        'error_message' => $e->getMessage(),
                    ]);
                // Log or handle failed SMS sending
                Log::error("Failed to send SMS to {$contactNumber}: {$e->getMessage()}");
            }
        }

        return response()->json(['message' => 'Messages sent successfully!']);
    }

    public function sendMessage(Request $request)
    {
        try {
            $response = Http::withHeaders(config('otp.txtbox.headers'))->post(config('otp.txtbox.url'), [
                'message' => $request->message,
                'number' => $request->contact_number,
            ]);

            if ($response->failed()) {
                throw new \Exception('Failed to send SMS via Txtbox: ' . $response->body());
            }
            $smsLogs = SmsLog::create([
                'user_id' => $request->user_id,
                'contact_number' => $request->contact_number,
                'message' => $request->message,
                'status' => 'success',
                'error_message' => '',
            ]);

            //dd(SmsLog::query()->whereUserId($request->user_id)->count());
            //dd($smsLogs->where('user_id', $request->user_id)->count());
            return $this->sendResponse($smsLogs->where('user_id', $request->user_id)->get(), 'Messages sent successfully!');

        } catch (\Exception $e) {
            // Log failed SMS send
            SmsLog::create([
                'user_id' => $request->user_id,
                'contact_number' => $request->contact_number,
                'message' => $request->message,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                ]);
            // Log or handle failed SMS sending
            Log::error("Failed to send SMS to {$request->contact_number}: {$e->getMessage()}");
        }
        if ($response->failed()) {
            throw new \Exception('Failed to send SMS via Txtbox: ' . $response->body());
        }

        return $response->json();
    }

// Helper function to send SMS
    private function sendSms($contactNumber, $message)
    {
        $response = Http::withHeaders(config('otp.txtbox.headers'))->post(config('otp.txtbox.url'), [
            'message' => $message,
            'number' => $contactNumber,
        ]);

        if ($response->failed()) {
            throw new \Exception('Failed to send SMS via Txtbox: ' . $response->body());
        }
    }
}
