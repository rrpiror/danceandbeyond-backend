<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            padding: 20px 0;
        }

        .content {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 5px;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #4CAF50;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
        }

        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Reset Your Password</h2>
        </div>

        <div class="content">
            <p>Hello,</p>

            <p>We received a request to reset your password. If you didn't make this request, you can safely ignore this
                email.</p>

            <p>To reset your password, click the button below:</p>

            <div style="text-align: center;">
                <a href="{{ env('RESET_PASSWORD_APP_URL') . '/reset-password/' . $token }}" class="button">Reset Password</a>
            </div>

            <p>This password reset link will expire in 24 hours.</p>

            <p>Best regards,<br>Dance & Beyond Team</p>
        </div>

        <div class="footer">
            <p>This is an automated message, please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} Dance & Beyond. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
