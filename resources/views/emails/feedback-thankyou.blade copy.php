<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <title>Thank You for Your Feedback</title>
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #F6FAFB;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 600px;
            margin: auto;
            background: #fff;
            padding: 40px;
            border-radius: 8px;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #888;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Hi {{ $name ?? 'Client' }},</h2>
        <p>Thank you for taking the time to provide feedback on your experience with <strong>NI Ventures</strong>.</p>

        <p>We truly appreciate your input and will use it to improve our products and services.</p>

        <p>If there's anything urgent or unresolved, feel free to reply to this email directly.</p>

        <p>Warm regards,<br><strong>The NI Ventures Team</strong></p>
    </div>

    <div class="footer">
        {{ config('app.name') }} | {{ env('APP_ADDRESS', 'Your Company Address') }}
    </div>
</body>

</html>