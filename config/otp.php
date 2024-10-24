<?php

return [
    'service' => env('OTP_SERVICE','enabled'),
    'table-name' => env('OTP_TABLE_NAME','otps'),
    'timeout' => env('OTP_TIMEOUT', 120),
    'next-try' => env('OTP_NEXT_TRY', 600),
    'digit' => env('OTP_DIGIT', 4),
    'resend' => env('OTP_RESEND_SERVICE', 'enabled'),
    'max-retry' => env('OTP_MAX_RETRY',2),
    'max-resend' => env('OTP_MAX_RESEND',1),
    'service-name' => env('OTP_SERVICE_NAME','OTP Service'),
    'company-name' => env('OTP_COMPANY_NAME','Test Company'),
    'sms-appointment' => env('SMS_APPOINTMENT_SERVICE', 1),
    'send-by' => [
        'email' => env('OTP_SEND_BY_EMAIL',0),
        'sms' => env('OTP_SEND_BY_SMS',1)
    ],
    'txtbox' => [
        'url' => env('TXTBOX_URL'),
        'method' => env('TXTBOX_METHOD', 'POST'),
        'headers' => [
            'X-TXTBOX-Auth' => env('TXTBOX_API_KEY')
        ],
        'params' => [
            'send_to_param_name' => env('OTP_SMSC_PARAM_TO_NAME','number'),
            'msg_param_name' => env('OTP_SMSC_PARAM_MSG_NAME','msg'),
        ]
    ]
];
