<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Setup</title>
    <style>
        /* Reset styles to ensure consistency across email clients */
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        /* Wrapper for email content */
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        /* Header styles */
        .header {
            background-color: #f0f0f0;
            padding: 20px;
            text-align: center;
        }
        /* Body styles */
        .body-content {
            padding: 20px;
        }
        /* Button styles */
        .button {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
        }
        /* Footer styles */
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="header">
            <h1>Password Setup</h1>
        </div>
        <div class="body-content">
            <p>Hello,</p>
            <p>Thank you for signing up. Please click the button below to set up your password:</p>
            <a href="{{ $passwordSetupUrl }}" class="button">Set Up Password</a>
            <p>If you didn't sign up for this service, you can safely ignore this email.</p>
        </div>
        <div class="footer">
            <p>This email was sent automatically. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
