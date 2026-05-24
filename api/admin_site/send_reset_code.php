<?php
session_start();

ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../connect/config.php';
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
        $mail->Username = 'jaymichaelmontemarcastillo@gmail.com'; // Configure this
        $mail->Password = 'edwh uwey mjua bibi'; // Configure this (use app-specific password for Gmail)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('noreply@anythinginsideph.com', 'Anything Inside Admin');
        $mail->addAddress($email, $admin['FullName']);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Code - Anything Inside Admin';

        $emailBody = '
        <html>
            <head>
                <style>
                    body { font-family: Inter, Arial, sans-serif; color: #333; }
                    .container { max-width: 500px; margin: 0 auto; padding: 20px; }
                    .header { background: #1a56db; color: white; padding: 20px; text-align: center; border-radius: 4px 4px 0 0; }
                    .content { background: #f9fafb; padding: 20px; border-radius: 0 0 4px 4px; }
                    .code-box { background: white; border: 2px solid #1a56db; padding: 20px; text-align: center; margin: 20px 0; border-radius: 4px; }
                    .code { font-size: 32px; font-weight: bold; color: #1a56db; letter-spacing: 2px; }
                    .warning { color: #dc2626; font-size: 12px; margin-top: 10px; }
                    .footer { text-align: center; margin-top: 20px; color: #6b7280; font-size: 12px; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h2>Password Reset Request</h2>
                    </div>
                    <div class="content">
                        <p>Hello ' . htmlspecialchars($admin['FullName']) . ',</p>
                        <p>You requested to reset your password for the Anything Inside Admin Portal. Use the code below to proceed:</p>
                        <div class="code-box">
                            <div class="code">' . $code . '</div>
                            <p style="margin: 10px 0 0 0; color: #6b7280;">Valid for 10 minutes</p>
                        </div>
                        <p>If you did not request this, please ignore this email and your password will remain unchanged.</p>
                        <div class="warning">
                            <strong>Security Notice:</strong> Never share this code with anyone. The support team will never ask for this code.
                        </div>
                        <div class="footer">
                            <p>© 2024 Anything Inside. All rights reserved.</p>
                        </div>
                    </div>
                </div>
            </body>
        </html>';

        $mail->Body = $emailBody;
        $mail->AltBody = 'Your password reset code is: ' . $code . ' (Valid for 10 minutes)';

        $mail->send();

        exit(json_encode([
            'success' => true,
            'message' => 'Code sent successfully to your email'
        ]));
    } catch (Exception $e) {
        error_log('Email sending failed: ' . $e->getMessage());
        // Continue even if email fails, but log it
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
