<!DOCTYPE html>
<html>
<head>
    <title>Applicant Notification</title>
    <style>
        /* General email styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }
        .email-wrapper {
            display: flex;
            justify-content: center;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            color: #2d3748;
        }
        .email-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .email-header h1 {
            font-size: 24px;
            color: #1a202c;
            margin: 0;
        }
        .email-body {
            font-size: 16px;
            line-height: 1.6;
            color: #4a5568;
        }
        .email-footer {
            text-align: center;
            font-size: 14px;
            color: #a0aec0;
            margin-top: 30px;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
        }
    </style>
</head>
<body>
<div class="email-wrapper">
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>ResourceHub Notification</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p>{{ $emailContent['message'] }}</p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>If you have any questions, please feel free to reach out to us.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</div>
</body>
</html>
