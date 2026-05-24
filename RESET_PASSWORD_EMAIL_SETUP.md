# Admin Password Reset Email Configuration Guide

## Overview

The admin password reset feature now includes email verification with a 6-digit code. Follow these steps to configure the email settings.

## Configuration Steps

### 1. Gmail Configuration (Recommended)

To use Gmail for sending reset codes:

1. **Enable 2-Factor Authentication**:
   - Go to myaccount.google.com
   - Click "Security" in the left sidebar
   - Enable 2-Step Verification

2. **Generate App Password**:
   - In Security settings, find "App passwords" (only visible if 2FA is enabled)
   - Select "Mail" and "Windows Computer" (or your device)
   - Google will generate a 16-character password
   - Copy this password

3. **Update send_reset_code.php**:
   - Open `/api/admin_site/send_reset_code.php`
   - Replace line: `$mail->Username = 'your-email@gmail.com';`
     - Change to your Gmail address
   - Replace line: `$mail->Password = 'your-app-password';`
     - Paste the 16-character app password

4. **Update from email**:
   - Replace line: `$mail->setFrom('noreply@anythinginsideph.com', 'Anything Inside Admin');`
   - Change the sender address if needed

### 2. Alternative: Use Your Own SMTP Server

If you have your own mail server:

1. Replace the SMTP configuration in `send_reset_code.php`:

   ```php
   $mail->Host = 'your-smtp-server.com';
   $mail->SMTPAuth = true;
   $mail->Username = 'your-email@yourdomain.com';
   $mail->Password = 'your-password';
   $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // or ENCRYPTION_SMTPS
   $mail->Port = 587; // or 465 for SMTPS
   ```

2. Update the sender email and name as needed

### 3. Testing the Configuration

1. Go to `/admin/reset_password.php`
2. Enter your admin email address
3. Click "Send Code"
4. Check your email inbox (and spam folder)
5. You should receive an email with a 6-digit code

### 4. Reset Password Flow

**Step 1: Send Code**

- Admin enters their email
- System checks if email exists in database
- 6-digit code is generated
- Email is sent with the code (valid for 10 minutes)

**Step 2: Verify Code**

- Admin enters the 6 digits they received
- System verifies the code matches and hasn't expired
- If valid, redirects to password reset

**Step 3: Reset Password**

- Admin enters new password and confirms it
- System validates password requirements:
  - At least 8 characters
  - At least 1 uppercase letter (A-Z)
  - At least 1 lowercase letter (a-z)
  - At least 1 number (0-9)
  - At least 1 special character (!@#$%^&\*)
- Real-time feedback shows which requirements are met
- On success, password is updated and admin is redirected to login

## Password Requirements

The new password must meet ALL of these requirements:

- ✓ At least 8 characters long
- ✓ Contains at least one uppercase letter (A-Z)
- ✓ Contains at least one lowercase letter (a-z)
- ✓ Contains at least one number (0-9)
- ✓ Contains at least one special character (!@#$%^&\*)

Example valid password: `AdminPass123!`

## Features

1. **Email Verification**: Secure 6-digit code sent to email
2. **Code Expiration**: Codes expire after 10 minutes
3. **Password Requirements**: Real-time validation feedback
4. **Error Handling**: Clear error messages for troubleshooting
5. **Activity Logging**: Password resets are logged for security audit
6. **Session Security**: Verification state is stored in session

## Troubleshooting

### "Failed to send email"

- Check Gmail app password is correct
- Verify 2-Factor Authentication is enabled
- Check if port 587 is not blocked by your firewall

### "Code not received"

- Check email spam/junk folder
- Verify Gmail account is not blocking the sender
- Ensure email address is registered as admin account

### "Code has expired"

- You have 10 minutes to enter the code
- If expired, click "Send Code" again to get a new code

### "Invalid email format"

- Ensure email address is spelled correctly
- Use format: admin@example.com

## Files Modified/Created

1. `/admin/reset_password.php` - Updated UI with new workflow
2. `/api/admin_site/send_reset_code.php` - Generates and sends code
3. `/api/admin_site/verify_reset_code.php` - Verifies code validity
4. `/api/admin_site/reset_password_final.php` - Updates password in database
5. `/admin/includes/auth_toast.php` - Toast notifications (already existed)

## Security Notes

- Codes are valid for 10 minutes only
- Codes are stored in session (server-side only)
- Passwords are hashed using bcrypt (PASSWORD_DEFAULT)
- All inputs are validated server-side
- Activity is logged for audit trail

## Next Steps

1. Configure the email settings (Gmail or custom SMTP)
2. Test the complete password reset flow
3. Verify emails are being delivered
4. Monitor activity logs for any issues
