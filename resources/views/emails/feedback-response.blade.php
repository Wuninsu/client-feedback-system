<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Your Feedback Has Been Reviewed</title>
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f6f6f6;
        }

        .container {
            width: 600px;
            margin: auto;
            background: #ffffff;
            padding: 30px;
        }

        .button {
            background: #4C83EE;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Hello {{ $user_name ?? 'Client' }},</h2>

        <p>We’ve reviewed your feedback and here is our response:</p>

        <blockquote style="margin: 20px 0; background: #f1f1f1; padding: 15px; border-left: 4px solid #4C83EE;">
            {{ $response_message ?? 'Thank you for your feedback. We’re working on it.' }}
        </blockquote>

        <p>You can view your full feedback history and responses below:</p>

        <a href="{{ $feedback_link ?? '#' }}" class="button">View Feedback</a>

        <p style="margin-top: 20px;">Sincerely,<br><strong>NI Ventures Support Team</strong></p>
    </div>
</body>

</html>