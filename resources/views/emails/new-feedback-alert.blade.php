<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>New Feedback Received</title>
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background: #f6f6f6;
        }

        .container {
            background: #fff;
            padding: 30px;
            margin: auto;
            width: 600px;
        }

        .info {
            background: #f1f1f1;
            padding: 10px;
            margin: 15px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>New Feedback Submitted</h2>

        <p><strong>From:</strong> {{ $user_name ?? 'Guest' }} ({{ $user_email ?? 'N/A' }})</p>

        <p><strong>Category:</strong> {{ $category_name ?? 'General' }}</p>

        <div class="info">
            <strong>Message:</strong><br>
            {{ $message ?? 'No message provided.' }}
        </div>

        <p>Visit the admin panel to review and respond.</p>
    </div>
</body>

</html>