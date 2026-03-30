# Forgot Password Implementation Guide

## Overview
Your Laravel application already has a complete forgot password functionality that sends a 6-digit code via email. The system uses the Mailtrap SMTP configuration from your `.env` file.

## System Flow

### 1. **Forgot Password Page** (`/forgot-password`)
- User enters their email address
- Route: `GET /forgot-password` → Shows form
- POST to: `POST /forgot-password` (route: `password.email`)

### 2. **Send OTP Email** (PasswordResetLinkController)
When user submits the email:
- ✅ Validates that email exists in `users` table
- ✅ Generates a random 6-digit code (100000-999999)
- ✅ Stores hashed OTP in `password_reset_tokens` table
- ✅ Sends email with OTP using Mailtrap SMTP
- ✅ Redirects to reset password page with message

### 3. **Reset Password Page** (`/reset-password`)
- User enters: email, 6-digit code, and new password
- Validates the OTP (15-minute expiration)
- Updates user password
- Deletes the OTP token
- Redirects to login with success message

## Configuration

### Email Service (Mailtrap)
Your `.env` file is already configured with Mailtrap:
```
MAIL_MAILER=smtp
MAIL_SCHEME=smtp
MAIL_HOST=live.smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=api
MAIL_PASSWORD=80f0b3eabf14fafe44239cf2763ca71e
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@panha.com"
```

### Database
OTP tokens are stored in the `password_reset_tokens` table with:
- `email` (primary key)
- `token` (hashed)
- `created_at` (for expiration check)

## Files Involved

### Controllers
- `app/Http/Controllers/Auth/PasswordResetLinkController.php` - Generate & send OTP
- `app/Http/Controllers/Auth/NewPasswordController.php` - Verify OTP & reset password

### Views
- `resources/views/auth/forgot-password.blade.php` - Email input form
- `resources/views/auth/reset-password.blade.php` - OTP & password reset form
- `resources/views/emails/password-otp.blade.php` - Email template

### Routes
- `routes/frontend/auth.php` - All auth routes configured

### Migration
- `database/migrations/0001_01_01_000000_create_users_table.php` - password_reset_tokens table

## How to Test

1. **Go to forgot password page**
   ```
   http://localhost:8000/forgot-password
   ```

2. **Enter your email** (must exist in database)
   - Check Mailtrap inbox for the 6-digit code
   - Or check Laravel logs if mail is sent to log driver

3. **Go to reset password page**
   ```
   http://localhost:8000/reset-password?email=your@email.com
   ```

4. **Enter the code and new password**
   - Code must be 6 digits
   - Password must be 8+ chars with letters and numbers
   - Password must be confirmed

## Security Features

✅ OTP expires after 15 minutes  
✅ OTP is hashed in database (not stored in plain text)  
✅ Email must exist in users table  
✅ Password validation: 8+ chars, letters, numbers  
✅ Rate limiting on routes with `throttle:auth-password-reset`  
✅ Guest-only middleware on forgot/reset routes  

## Email Template

The email sent includes:
- User-friendly message
- Clear 6-digit code (large font, spaced)
- Expiration notice (15 minutes)
- Security warning

Email is sent from: `noreply@panha.com`

---

**Note:** The system is production-ready and already fully implemented! No additional code needed.
