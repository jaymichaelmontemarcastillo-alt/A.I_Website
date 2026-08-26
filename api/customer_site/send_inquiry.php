<?php
// api/customer_site/send_inquiry.php
header('Content-Type: application/json; charset=utf-8');

ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../connect/config.php';
require_once __DIR__ . '/../../connect/email_crypto.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $firstName     = trim($_POST['firstName']     ?? '');
    $lastName      = trim($_POST['lastName']      ?? '');
    $workEmail     = trim($_POST['workEmail']     ?? '');
    $contactNumber = trim($_POST['contactNumber'] ?? '');
    $quantityNeeded = trim($_POST['quantityNeeded'] ?? '');
    $neededBy      = trim($_POST['neededBy']      ?? '');
    $tellUsMore    = trim($_POST['tellUsMore']    ?? '');

    if (empty($firstName) || empty($workEmail)) {
        throw new Exception('Please fill in all required fields.');
    }

    if (!filter_var($workEmail, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format.');
    }

    // Load credentials from the admin-managed email_settings table
    $pdo  = getDBConnection();
    $stmt = $pdo->prepare('SELECT GmailAddress, AppPasswordEncrypted FROM email_settings WHERE id = 1 LIMIT 1');
    $stmt->execute();
    $emailSettings = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$emailSettings || empty($emailSettings['GmailAddress']) || empty($emailSettings['AppPasswordEncrypted'])) {
        throw new Exception('Outgoing email is not configured yet. Please contact the site administrator.');
    }

    $smtpUser = $emailSettings['GmailAddress'];
    $smtpPass = decryptAppPassword($emailSettings['AppPasswordEncrypted']);

    $mail = new PHPMailer(true);

    // SMTP server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = $smtpUser;
    $mail->Password   = $smtpPass;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Recipients
    $mail->setFrom($smtpUser, 'Anything Inside');
    $mail->addAddress($workEmail, trim($firstName . ' ' . $lastName));
    $mail->addBCC($smtpUser, 'Anything Inside Admin');

    // Attachments
    if (isset($_FILES['attachReference'])) {
        $files = $_FILES['attachReference'];
        
        // Check if multiple files are uploaded (is_array) or a single file
        if (is_array($files['name'])) {
            for ($i = 0; $i < count($files['name']); $i++) {
                if ($files['error'][$i] === UPLOAD_ERR_OK) {
                    $tmpName  = $files['tmp_name'][$i];
                    $fileName = basename($files['name'][$i]);
                    $mail->addAttachment($tmpName, $fileName);
                }
            }
        } else {
            // Fallback for single file upload
            if ($files['error'] === UPLOAD_ERR_OK) {
                $tmpName  = $files['tmp_name'];
                $fileName = basename($files['name']);
                $mail->addAttachment($tmpName, $fileName);
            }
        }
    }

    // Embed the logo directly in the email
    $mail->addEmbeddedImage(__DIR__ . '/../../assets/images/AI_Logo.jpg', 'logo_cid');

    $fullName    = htmlspecialchars(trim($firstName . ' ' . $lastName));
    $safeEmail   = htmlspecialchars($workEmail);
    $safePhone   = htmlspecialchars($contactNumber);
    $safeQty     = htmlspecialchars($quantityNeeded);
    $safeDate    = htmlspecialchars($neededBy);
    $safeNote    = nl2br(htmlspecialchars($tellUsMore));

    $mail->isHTML(true);
    $mail->Subject = 'We received your inquiry — Anything Inside';

    $emailBody = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Inquiry Confirmation</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
      background: #f0f4f8;
      color: #1a2a44;
      -webkit-font-smoothing: antialiased;
    }
    .wrapper {
      max-width: 600px;
      margin: 36px auto;
      padding: 0 16px 40px;
    }
    /* ── Header ── */
    .email-header {
      background: linear-gradient(135deg, #0a2a44 0%, #1a4a6e 100%);
      border-radius: 20px 20px 0 0;
      padding: 32px 36px;
      display: flex;
      align-items: center;
      gap: 16px;
    }
    .email-header img {
      width: 52px;
      height: 52px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid rgba(244,186,49,0.6);
    }
    .email-header-text h1 {
      font-size: 18px;
      font-weight: 700;
      color: #ffffff;
      letter-spacing: -0.3px;
    }
    .email-header-text p {
      font-size: 13px;
      font-weight: 300;
      color: rgba(255,255,255,0.7);
      margin-top: 2px;
    }
    /* ── Body card ── */
    .email-body {
      background: #ffffff;
      padding: 36px 36px 28px;
    }
    .greeting {
      font-size: 22px;
      font-weight: 700;
      color: #0a2a44;
      margin-bottom: 8px;
    }
    .greeting span { color: #f4ba31; }
    .subline {
      font-size: 14px;
      color: #5c6b7a;
      line-height: 1.7;
      margin-bottom: 28px;
    }
    /* ── Summary card ── */
    .summary-card {
      background: #f8fafc;
      border: 1px solid #e8edf2;
      border-radius: 14px;
      overflow: hidden;
      margin-bottom: 28px;
    }
    .summary-card-header {
      background: linear-gradient(90deg, #f4ba31 0%, #ffd966 100%);
      padding: 12px 20px;
    }
    .summary-card-header p {
      font-size: 11px;
      font-weight: 700;
      color: #0a2a44;
      text-transform: uppercase;
      letter-spacing: 1.2px;
    }
    .summary-row {
      display: flex;
      flex-direction: column;
      gap: 6px;
      padding: 12px 20px;
      border-bottom: 1px solid #eef2f6;
      font-size: 14px;
    }
    .summary-row:last-child { border-bottom: none; }
    .summary-label {
      font-weight: 600;
      color: #0a2a44;
    }
    .summary-value { color: #3a4a5c; line-height: 1.6; }
    .summary-row.full-width {
      /* Keep this for backward compatibility if needed */
    }
    /* ── What's next ── */
    .next-steps {
      background: rgba(244,186,49,0.08);
      border-left: 4px solid #f4ba31;
      border-radius: 0 10px 10px 0;
      padding: 14px 18px;
      margin-bottom: 28px;
    }
    .next-steps p {
      font-size: 13px;
      color: #3a4a5c;
      line-height: 1.7;
    }
    .next-steps strong { color: #0a2a44; }
    /* ── Footer ── */
    .email-footer {
      background: #0a2a44;
      border-radius: 0 0 20px 20px;
      padding: 22px 36px;
      text-align: center;
    }
    .email-footer p {
      font-size: 12px;
      color: rgba(255,255,255,0.45);
      line-height: 1.8;
    }
    .email-footer a {
      color: #f4ba31;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <!-- Header -->
    <div class="email-header">
      <img src="cid:logo_cid" alt="Anything Inside Logo" />
      <div class="email-header-text">
        <h1>Anything Inside</h1>
        <p>Custom Merchandise &amp; Curated Gift Sets</p>
      </div>
    </div>

    <!-- Body -->
    <div class="email-body">
      <p class="greeting">Thanks for reaching out, <span>{$firstName}!</span> 🎁</p>
      <p class="subline">
        We've received your inquiry and our team will be in touch with you very soon. 
        Here's a copy of the details you submitted for your reference.
      </p>

      <!-- Summary Card -->
      <div class="summary-card">
        <div class="summary-card-header">
          <p>Your Inquiry Summary</p>
        </div>
        <div class="summary-row">
          <span class="summary-label">Name</span>
          <span class="summary-value">{$fullName}</span>
        </div>
        <div class="summary-row">
          <span class="summary-label">Work Email</span>
          <span class="summary-value">{$safeEmail}</span>
        </div>
        <div class="summary-row">
          <span class="summary-label">Contact Number</span>
          <span class="summary-value">{$safePhone}</span>
        </div>
        <div class="summary-row">
          <span class="summary-label">Quantity Needed</span>
          <span class="summary-value">{$safeQty}</span>
        </div>
        <div class="summary-row">
          <span class="summary-label">Timeline</span>
          <span class="summary-value">{$safeDate}</span>
        </div>
        <div class="summary-row full-width">
          <span class="summary-label">Tell Us More</span>
          <span class="summary-value">{$safeNote}</span>
        </div>
      </div>

      <!-- What's Next -->
      <div class="next-steps">
        <p>
          <strong>What happens next?</strong><br/>
          Our team will review your project details and reach out within 1–2 business days
          with tailored recommendations and a quote.
        </p>
      </div>
    </div>

    <!-- Footer -->
    <div class="email-footer">
      <p>
        &copy; 2026 <a href="#">Anything Inside</a>. All rights reserved.<br/>
        Plaza Agapita Commercial Complex, Batong Malake, Los Baños, Laguna &middot; 0968 730 5403
      </p>
    </div>
  </div>
</body>
</html>
HTML;

    $mail->Body    = $emailBody;
    $mail->AltBody = "Thanks for reaching out, {$firstName}!\n\nWe've received your inquiry and our team will be in touch soon.\n\nYour Inquiry Summary:\n- Name: {$fullName}\n- Work Email: {$safeEmail}\n- Contact Number: {$safePhone}\n- Quantity Needed: {$safeQty}\n- Timeline: {$safeDate}\n- Tell Us More: {$tellUsMore}\n\nWhat happens next?\nOur team will review your project details and reach out within 1-2 business days.\n\n© 2026 Anything Inside";

    $mail->send();

    echo json_encode(['success' => true, 'message' => 'Inquiry sent successfully.']);

} catch (Exception $e) {
    error_log('send_inquiry.php: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
