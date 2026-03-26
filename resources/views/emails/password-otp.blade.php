<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Code</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111827; line-height: 1.5;">
    <p>Hello,</p>
    <p>We received a request to reset your password for <strong>{{ $email }}</strong>.</p>
    <p>Your 6-digit verification code is:</p>
    <p style="font-size: 28px; font-weight: 700; letter-spacing: 4px; margin: 16px 0;">{{ $otp }}</p>
    <p>This code will expire in 15 minutes.</p>
    <p>If you did not request this, please ignore this email.</p>
</body>
</html>