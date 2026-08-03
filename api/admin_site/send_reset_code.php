<?php
session_start();

ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../connect/config.php';
require_once __DIR__ . '/../../connect/email_crypto.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

try {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    $email = isset($input['email']) ? trim($input['email']) : '';

    if (empty($email)) {
        throw new Exception('Email is required');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format');
    }

    // Get database connection
    $pdo = getDBConnection();

    // Check if admin exists with this email
    $stmt = $pdo->prepare('SELECT AdminID, FullName, Email FROM admins WHERE Email = ? LIMIT 1');
    $stmt->execute([$email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        throw new Exception('No admin account found with this email');
    }

    // Load the business Gmail + app password configured in Account Settings
    $stmt = $pdo->prepare('SELECT GmailAddress, AppPasswordEncrypted FROM email_settings WHERE id = 1 LIMIT 1');
    $stmt->execute();
    $emailSettings = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$emailSettings || empty($emailSettings['GmailAddress']) || empty($emailSettings['AppPasswordEncrypted'])) {
        throw new Exception('Outgoing email is not configured yet. Set it up under Account Settings > Email Notification Settings.');
    }

    $smtpUsername = $emailSettings['GmailAddress'];
    $smtpPassword = decryptAppPassword($emailSettings['AppPasswordEncrypted']);

    // Generate 6-digit code
    $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

    // Store code in session with timestamp (valid for 10 minutes)
    $_SESSION['reset_code'] = $code;
    $_SESSION['reset_code_email'] = $email;
    $_SESSION['reset_code_time'] = time();
    $_SESSION['reset_code_expire'] = time() + (10 * 60); // 10 minutes

    // Send email with PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $smtpUsername;
        $mail->Password = $smtpPassword;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom($smtpUsername, 'Anything Inside');
        $mail->addAddress($email, $admin['FullName']);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Reset Your Password - Anything Inside';

        // ============================================================
        // LOGO PATH - Using absolute URL that email clients can access
        // ============================================================
        // Get the base URL dynamically
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        $baseUrl = $protocol . $host;
        
        // Option 1: If your assets folder is at the root of your domain
       // $logoPath = $baseUrl . '../../assets/images/AI_Logo.jpg';
        
        // Option 2: If your project is in a subfolder, uncomment and modify this:
         $logoPath = $baseUrl . '/ANYTHING_INSIDE_WEBSITE/assets/images/AI_Logo.jpg';
        
        // Option 3: Hardcode your domain (more reliable)
        // $logoPath = 'https://yourdomain.com/assets/images/AI_Logo.jpg';

        $emailBody = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Password Reset</title>
            <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
            <style>
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                body {
                    font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
                    background-color: #f4f6fa;
                    color: #000000;
                    line-height: 1.6;
                    -webkit-font-smoothing: antialiased;
                    -moz-osx-font-smoothing: grayscale;
                }
                .email-wrapper {
                    max-width: 560px;
                    margin: 40px auto;
                    padding: 20px;
                }
                .email-container {
                    background: #ffffff;
                    border-radius: 10px;
                    overflow: hidden;
                    box-shadow: 0 4px 24px rgba(26, 77, 128, 0.08);
                }
                .email-header {
                    background: #1a4d80;
                    padding: 24px 32px;
                    display: flex;
                    align-items: center;
                    gap: 16px;
                }
                .logo-container {
                    flex-shrink: 0;
                }
                .logo-container img {
                    width: 48px;
                    height: 48px;
                    border-radius: 50%;
                    object-fit: cover;
                    display: block;
                    background: #ffffff;
                    border: 2px solid rgba(255,255,255,0.2);
                }
                .header-text {
                    color: #ffffff;
                }
                .header-text h1 {
                    font-size: 18px;
                    font-weight: 600;
                    letter-spacing: -0.2px;
                    margin: 0;
                    line-height: 1.3;
                }
                .header-text p {
                    font-size: 13px;
                    font-weight: 300;
                    opacity: 0.8;
                    margin: 2px 0 0 0;
                    letter-spacing: 0.3px;
                }
                .email-body {
                    padding: 32px;
                }
                .greeting {
                    font-size: 15px;
                    font-weight: 500;
                    color: #000000;
                    margin-bottom: 6px;
                }
                .greeting-name {
                    font-weight: 600;
                    color: #1a4d80;
                }
                .message-text {
                    font-size: 14px;
                    color: #333333;
                    margin: 8px 0 20px 0;
                }
                .code-box {
                    background: #f8faff;
                    border: 1.5px solid #e8edf5;
                    border-radius: 8px;
                    padding: 20px;
                    text-align: center;
                    margin: 20px 0;
                }
                .code-label {
                    font-size: 12px;
                    font-weight: 500;
                    color: #666666;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                }
                .code-value {
                    font-size: 36px;
                    font-weight: 700;
                    color: #1a4d80;
                    letter-spacing: 4px;
                    margin: 6px 0 4px 0;
                    font-feature-settings: "tnum";
                }
                .code-expiry {
                    font-size: 12px;
                    color: #888888;
                    margin: 0;
                }
                .divider {
                    border: none;
                    border-top: 1px solid #eaedf2;
                    margin: 24px 0;
                }
                .security-note {
                    background: #fafbfc;
                    border-left: 3px solid #1a4d80;
                    padding: 12px 16px;
                    border-radius: 4px;
                    margin: 16px 0 20px 0;
                }
                .security-note strong {
                    color: #1a4d80;
                    font-weight: 600;
                }
                .security-note p {
                    font-size: 13px;
                    color: #444444;
                    margin: 0;
                    line-height: 1.5;
                }
                .ignore-text {
                    font-size: 13px;
                    color: #555555;
                    margin: 16px 0 0 0;
                }
                .email-footer {
                    padding: 20px 32px;
                    border-top: 1px solid #eaedf2;
                    text-align: center;
                }
                .footer-text {
                    font-size: 12px;
                    color: #999999;
                    margin: 0;
                    letter-spacing: 0.2px;
                }
                .footer-text a {
                    color: #1a4d80;
                    text-decoration: none;
                }
                .footer-text a:hover {
                    text-decoration: underline;
                }
                @media only screen and (max-width: 480px) {
                    .email-wrapper {
                        padding: 10px;
                        margin: 20px auto;
                    }
                    .email-header {
                        padding: 18px 20px;
                        flex-direction: column;
                        text-align: center;
                    }
                    .logo-container img {
                        width: 44px;
                        height: 44px;
                    }
                    .header-text h1 {
                        font-size: 16px;
                    }
                    .email-body {
                        padding: 22px 18px;
                    }
                    .code-value {
                        font-size: 28px;
                        letter-spacing: 3px;
                    }
                    .email-footer {
                        padding: 16px 20px;
                    }
                }
            </style>
        </head>
        <body>
            <div class="email-wrapper">
                <div class="email-container">
                    <!-- Header with Logo -->
                    <div class="email-header">
                        <div class="logo-container">
                            <img src="' . $logoPath . '" alt="Anything Inside" />
                        </div>
                        <div class="header-text">
                            <h1>Password Reset</h1>
                            <p>Secure access to your account</p>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="email-body">
                        <p class="greeting">
                            Hello, <span class="greeting-name">' . htmlspecialchars($admin['FullName']) . '</span>
                        </p>
                        <p class="message-text">
                            We received a request to reset the password for your Anything Inside admin account.
                            Please use the verification code below to proceed.
                        </p>

                        <!-- Code Box -->
                        <div class="code-box">
                            <p class="code-label">Verification Code</p>
                            <p class="code-value">' . $code . '</p>
                            <p class="code-expiry">⏱ Valid for 10 minutes</p>
                        </div>

                        <hr class="divider" />

                        <!-- Security Notice -->
                        <div class="security-note">
                            <p>
                                <strong>🔒 Security Notice:</strong> 
                                Never share this code with anyone. Our support team will 
                                <strong>never</strong> ask for your verification code.
                            </p>
                        </div>

                        <p class="ignore-text">
                            If you didn\'t request this password reset, please ignore this email 
                            and your password will remain unchanged.
                        </p>
                    </div>

                    <!-- Footer -->
                    <div class="email-footer">
                        <p class="footer-text">
                            &copy; 2026 <a href="#">Anything Inside</a>. All rights reserved.
                        </p>
                    </div>
                </div>
            </div>
        </body>
        </html>';

        $mail->Body = $emailBody;
        $mail->AltBody = "Password Reset Code\n\nHello " . $admin['FullName'] . ",\n\nYou requested to reset your password. Your verification code is: " . $code . "\n\nThis code is valid for 10 minutes.\n\nIf you did not request this, please ignore this email.\n\nSecurity Notice: Never share this code with anyone.\n\n© 2026 Anything Inside";

        $mail->send();

        exit(json_encode([
            'success' => true,
            'message' => 'Code sent successfully to your email'
        ]));
    } catch (Exception $e) {
        error_log('Email sending failed: ' . $e->getMessage());
        exit(json_encode([
            'success' => false,
            'message' => 'Failed to send email. Please try again.'
        ]));
    }
} catch (Exception $e) {
    error_log('send_reset_code.php: ' . $e->getMessage());
    exit(json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]));
}