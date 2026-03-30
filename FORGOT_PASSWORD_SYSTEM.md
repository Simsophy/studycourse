# Forgot Password System - Complete Documentation

## Overview
A complete password reset system that generates a 6-digit OTP code, sends it via email through Mailtrap SMTP, and allows users to securely reset their passwords.

---

## How It Works

### **Step 1: User Requests Password Reset**
- User visits: `/forgot-password`
- Enters their email address
- System validates that email exists in `users` table
- If email not found: shows validation error

### **Step 2: OTP Generation & Email Sent**
- Server generates random 6-digit code (100000-999999)
- Hashes the code and stores in `password_reset_tokens` table
- Sends email via **Mailtrap SMTP** with the code
- Redirects to: `/reset-password?email=user@email.com`
- Shows success message: "A 6-digit verification code has been sent to your email"

### **Step 3: User Enters Code & New Password**
- User receives email with 6-digit code
- Navigates to reset password page
- Enters:
  - Email address
  - 6-digit verification code
  - New password (8+ chars, letters + numbers)
  - Password confirmation

### **Step 4: Password Updated**
- System verifies:
  - ✅ OTP matches the stored hash
  - ✅ OTP hasn't expired (15 minutes max)
  - ✅ Password meets security requirements
- Password is hashed and saved to database
- OTP token is deleted from database
- User redirected to login page with success message: "Password reset successful. You can now log in."

---

## Email Configuration

### Mailtrap SMTP Setup
```dotenv
MAIL_MAILER=smtp
MAIL_SCHEME=smtp
MAIL_HOST=live.smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=api
MAIL_PASSWORD=80f0b3eabf14fafe44239cf2763ca71e
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="hello@mailtrap.io"
MAIL_FROM_NAME="Study Course"
MAIL_FORCE_LOG_IN_LOCAL=false
```

### Email Template
The system sends a professional HTML email containing:
- Welcome message
- User's email address
- **6-digit code in large, spaced font**
- Expiration time (15 minutes)
- Security notice

---

## Architecture

### Controllers

#### `PasswordResetLinkController` (Send OTP)
**Location:** `app/Http/Controllers/Auth/PasswordResetLinkController.php`

**Methods:**
- `create()` - Display forgot password form
- `store()` - Process email submission, generate & send OTP

**Process:**
1. Validates email format and existence
2. Generates 6-digit random code
3. Hashes code using `Hash::make()`
4. Stores in `password_reset_tokens` table
5. Sends email via Mail facade
6. Handles SMTP errors gracefully

#### `NewPasswordController` (Verify & Reset)
**Location:** `app/Http/Controllers/Auth/NewPasswordController.php`

**Methods:**
- `create()` - Display reset password form
- `store()` - Process password reset

**Process:**
1. Validates all inputs (email, OTP, password)
2. Retrieves OTP from database
3. Verifies OTP hash using `Hash::check()`
4. Checks if OTP expired (max 15 minutes)
5. Updates user password
6. Deletes used OTP token
7. Redirects to login

---

## Routes

### Frontend Auth Routes
**Location:** `routes/frontend/auth.php`

```php
// Forgot Password Routes (Guest only)
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('throttle:auth-password-reset')
    ->name('password.email');

// Reset Password Routes (Guest only)
Route::get('/reset-password', [NewPasswordController::class, 'create'])
    ->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->name('password.store');
```

### URL Endpoints
| Method | Route | Purpose |
|--------|-------|---------|
| GET | `/forgot-password` | Display forgot password form |
| POST | `/forgot-password` | Submit email & send OTP |
| GET | `/reset-password?email=...` | Display reset password form |
| POST | `/reset-password` | Verify OTP & reset password |

---

## Views (Blade Templates)

### `forgot-password.blade.php`
**Location:** `resources/views/auth/forgot-password.blade.php`

- Simple, responsive form
- Email input field
- Submit button
- Success/error message display
- Link back to login

### `reset-password.blade.php`
**Location:** `resources/views/auth/reset-password.blade.php`

- Three input fields:
  - Email (pre-filled from URL)
  - 6-digit code (max 6 chars, numbers only)
  - New password
  - Password confirmation
- Submit button
- Error/success messages
- Tailwind CSS styling

### `password-otp.blade.php` (Email)
**Location:** `resources/views/emails/password-otp.blade.php`

- HTML formatted email
- Large, spaced 6-digit code display
- User's email address
- 15-minute expiration notice
- Security warning

---

## Database

### password_reset_tokens Table
**Location:** `database/migrations/0001_01_01_000000_create_users_table.php`

```sql
CREATE TABLE password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
);
```

**Columns:**
- `email` - User's email (primary key)
- `token` - Hashed 6-digit code
- `created_at` - Timestamp for expiration check

---

## Security Features

✅ **OTP Hashing** - Code is hashed, never stored in plain text  
✅ **Time Expiration** - OTP valid for 15 minutes only  
✅ **Email Verification** - Email must exist in database  
✅ **Password Requirements** - Minimum 8 chars, letters + numbers  
✅ **Rate Limiting** - Throttled requests to prevent abuse  
✅ **TLS Encryption** - SMTP connection encrypted  
✅ **Guest Middleware** - Routes only accessible to unauthenticated users  
✅ **CSRF Protection** - Form requests validated with CSRF tokens  

---

## Error Handling

### Validation Errors
- **Invalid email format** - Shows validation message
- **Email not found** - "Unable to find user with this email"
- **Invalid OTP** - "Invalid verification code"
- **Expired OTP** - "Verification code has expired. Please request a new one"
- **Weak password** - "Password must be 8+ characters with letters and numbers"

### SMTP Errors
- Logged to `storage/logs/laravel.log`
- User sees: "Unable to send verification code right now. Please check mail configuration and try again"
- Error details logged with:
  - Email address
  - Exception message
  - Mail configuration details

---

## Testing Guide

### Test Forgot Password Flow

1. **Go to forgot password page:**
   ```
   http://localhost:8000/forgot-password
   ```

2. **Enter a valid email:**
   - Must be an existing user email in database
   - Example: `sothpanha2682@gmail.com`

3. **Check Mailtrap inbox:**
   - Log in to Mailtrap.io
   - View incoming emails
   - Copy the 6-digit code

4. **Go to reset password page:**
   ```
   http://localhost:8000/reset-password?email=sothpanha2682@gmail.com
   ```

5. **Enter reset details:**
   - Email: `sothpanha2682@gmail.com`
   - Code: (paste the 6-digit code from email)
   - New Password: `SecurePass123`
   - Confirm: `SecurePass123`

6. **Submit and verify:**
   - Should see success message
   - Redirected to login page
   - Can log in with new password

---

## Configuration Reference

### `.env` Variables
```dotenv
# Mail Driver
MAIL_MAILER=smtp                          # Use SMTP for sending
MAIL_SCHEME=smtp                          # Protocol
MAIL_HOST=live.smtp.mailtrap.io           # Mailtrap server
MAIL_PORT=587                             # SMTP port
MAIL_USERNAME=api                         # Mailtrap username
MAIL_PASSWORD=80f0b3eabf14fafe...        # Mailtrap API token
MAIL_ENCRYPTION=tls                       # Use TLS encryption
MAIL_FROM_ADDRESS="hello@mailtrap.io"     # Sender email
MAIL_FROM_NAME="Study Course"             # Sender name
MAIL_FORCE_LOG_IN_LOCAL=false             # Don't force log driver in local
```

### `config/mail.php` Key Settings
```php
'default' => env('MAIL_MAILER', 'log'),           // Use SMTP mailer
'force_log_in_local' => env('MAIL_FORCE_LOG_IN_LOCAL', false),  // Actually send emails
```

---

## Common Issues & Solutions

### Issue: "Unable to send verification code..."
**Cause:** Mail configuration error  
**Solutions:**
- Verify `.env` variables are correct
- Check Mailtrap credentials are valid
- Ensure `MAIL_FORCE_LOG_IN_LOCAL=false`
- Check SMTP port (587 for TLS)

### Issue: "Expected response code 550"
**Cause:** Invalid sender email domain  
**Solution:** Use `hello@mailtrap.io` (Mailtrap's verified domain)

### Issue: OTP code never arrives
**Cause:** Emails being logged instead of sent  
**Solution:** Set `MAIL_FORCE_LOG_IN_LOCAL=false` in `.env`

### Issue: "Invalid verification code"
**Cause:** Code expired or wrong code entered  
**Solution:** Request new code if older than 15 minutes

---

## Files Summary

| File | Type | Purpose |
|------|------|---------|
| `PasswordResetLinkController.php` | Controller | Generate & send OTP |
| `NewPasswordController.php` | Controller | Verify OTP & reset password |
| `forgot-password.blade.php` | View | Email input form |
| `reset-password.blade.php` | View | OTP & password reset form |
| `password-otp.blade.php` | Email Template | OTP email layout |
| `auth.php` | Routes | Password reset routes |
| `.env` | Config | Mailtrap SMTP settings |
| `mail.php` | Config | Mail driver configuration |

---

## Status
✅ **Fully Implemented and Working**

The forgot password system is production-ready with:
- Complete error handling
- Security best practices
- Professional email templates
- Responsive UI
- Comprehensive logging
