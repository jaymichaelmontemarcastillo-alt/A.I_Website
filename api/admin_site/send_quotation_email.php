<?php
// api/admin_site/send_quotation_email.php
declare(strict_types=1);
session_start();

if (empty($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}
include '../../connect/config.php';

require_once __DIR__ . '../../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

// ── SMTP configuration ─────────────────────────────────────────
define('SMTP_HOST',     'smtp.yourprovider.com');
define('SMTP_PORT',     587);
define('SMTP_USER',     'your@email.com');
define('SMTP_PASS',     'your_smtp_password');
define('SMTP_FROM',     'your@email.com');
define('SMTP_FROM_NAME','Your Company Name');
define('APP_BASE_URL',  'https://yourdomain.com'); // No trailing slash
// ───────────────────────────────────────────────────────────────

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, null, 'Method not allowed', 405);
}

$input = json_decode(file_get_contents('php://input'), true);
$id    = sanitizeInt($input['id'] ?? null);

if (!$id) {
    jsonResponse(false, null, 'Invalid quotation ID', 400);
}

try {
  $pdo = getDBConnection();


    $stmt = $pdo->prepare('SELECT * FROM quotations WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $quote = $stmt->fetch();

    if (!$quote) {
        jsonResponse(false, null, 'Quotation not found', 404);
    }

    if (empty($quote['email'])) {
        jsonResponse(false, null, 'Quotation has no recipient email', 400);
    }

    // Ensure token exists
    if (empty($quote['token'])) {
        $token = bin2hex(random_bytes(32));
        $pdo->prepare('UPDATE quotations SET token = :token WHERE id = :id')
            ->execute([':token' => $token, ':id' => $id]);
        $quote['token'] = $token;
    }

    // Generate PDF if not already generated
    if (empty($quote['pdf_path'])) {
        $pdfResult = generatePdfInternally($id, $pdo);
        if (!$pdfResult['success']) {
            jsonResponse(false, null, 'Could not generate PDF: ' . $pdfResult['message'], 500);
        }
        $quote['pdf_path'] = $pdfResult['pdf_path'];
    }

    $pdfAbsPath = __DIR__ . '/../../' . ltrim($quote['pdf_path'], '/');

    // Build URLs
    $viewUrl   = APP_BASE_URL . '/admin/pages/quotation-view.php?id=' . $id;
    $acceptUrl = APP_BASE_URL . '/quotation-accept.php?token=' . urlencode($quote['token']);

    // Send email
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = SMTP_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = SMTP_USER;
    $mail->Password   = SMTP_PASS;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = SMTP_PORT;

    $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
    $mail->addAddress($quote['email'], $quote['contact_person'] ?: $quote['client_name']);
    $mail->addReplyTo(SMTP_FROM, SMTP_FROM_NAME);

    $mail->isHTML(true);
    $mail->Subject = 'Quotation ' . $quote['quote_number'] . ' from ' . SMTP_FROM_NAME;
    $mail->Body    = buildEmailHtml($quote, $viewUrl, $acceptUrl);
    $mail->AltBody = buildEmailPlain($quote, $viewUrl, $acceptUrl);

    // Attach PDF
    if (file_exists($pdfAbsPath)) {
        $mail->addAttachment($pdfAbsPath, 'Quotation_' . $quote['quote_number'] . '.pdf');
    }

    $mail->send();

    // Update status to 'sent'
    $pdo->prepare("UPDATE quotations SET status = 'sent', updated_at = NOW() WHERE id = :id")
        ->execute([':id' => $id]);

    jsonResponse(true, null, 'Quotation sent successfully to ' . $quote['email']);

} catch (Exception $e) {
    error_log('send_quotation_email Mailer: ' . $e->getMessage());
    jsonResponse(false, null, 'Email could not be sent: ' . $e->getMessage(), 500);
} catch (PDOException $e) {
    error_log('send_quotation_email DB: ' . $e->getMessage());
    jsonResponse(false, null, 'Database error', 500);
}

// ── Internal PDF generation (mirrors generate_quotation_pdf.php logic) ──────
function generatePdfInternally(int $id, PDO $pdo): array {
  require_once __DIR__ . '/../../vendor/autoload.php';

    use Dompdf\Dompdf;
    use Dompdf\Options;

    $stmt = $pdo->prepare('SELECT * FROM quotations WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $quote = $stmt->fetch();

    $itemStmt = $pdo->prepare('SELECT * FROM quotation_items WHERE quotation_id = :id ORDER BY sort_order');
    $itemStmt->execute([':id' => $id]);
    $items = $itemStmt->fetchAll();

    // Re-use same HTML builder from generate_quotation_pdf.php
    // For DRY code in production, extract buildPdfHtml() to a shared file.
    // Included inline here for standalone correctness.
    $html = buildPdfHtmlForEmail($quote, $items);

    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', false);

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $uploadDir = __DIR__ . '/../../uploads/quotations/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    $filename = 'quotation_' . preg_replace('/[^A-Za-z0-9\-_]/', '_', $quote['quote_number']) . '.pdf';
    $filePath = $uploadDir . $filename;
    $fileUrl  = '/uploads/quotations/' . $filename;

    file_put_contents($filePath, $dompdf->output());

    $pdo->prepare('UPDATE quotations SET pdf_path = :path WHERE id = :id')
        ->execute([':path' => $fileUrl, ':id' => $id]);

    return ['success' => true, 'pdf_path' => $fileUrl];
}

function buildPdfHtmlForEmail(array $q, array $items): string {
    // Minimal version — full template is in generate_quotation_pdf.php
    // In production, include a shared template file
    ob_start();
    include __DIR__ . '/../../includes/pdf_template.php'; // Optional shared template
    return ob_get_clean() ?: '<html><body><h1>Quotation ' . htmlspecialchars($q['quote_number']) . '</h1></body></html>';
}

// ── Email HTML template ───────────────────────────────────────────────────────
function buildEmailHtml(array $q, string $viewUrl, string $acceptUrl): string {
    $company     = SMTP_FROM_NAME;
    $clientName  = htmlspecialchars($q['contact_person'] ?: $q['client_name'], ENT_QUOTES);
    $quoteNumber = htmlspecialchars($q['quote_number'], ENT_QUOTES);
    $total       = '$' . number_format((float)$q['total'], 2);
    $date        = date('F j, Y', strtotime($q['created_at']));
    $expiry      = $q['expires_at'] ? date('F j, Y', strtotime($q['expires_at'])) : 'N/A';

    return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Quotation {$quoteNumber}</title>
</head>
<body style="margin:0;padding:0;background:#f3f4f6;font-family:Helvetica,Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f3f4f6;padding:40px 16px;">
  <tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.08);">

      <!-- Top accent bar -->
      <tr><td style="background:#1e3a5f;height:6px;"></td></tr>

      <!-- Header -->
      <tr>
        <td style="padding:36px 40px 24px;border-bottom:1px solid #e5e7eb;">
          <table width="100%"><tr>
            <td><span style="font-size:20px;font-weight:700;color:#1e3a5f;">{$company}</span></td>
            <td align="right"><span style="font-size:11px;font-weight:700;letter-spacing:2px;color:#6b7280;text-transform:uppercase;">Quotation</span><br>
              <span style="font-size:14px;font-weight:600;color:#1f2937;">#{$quoteNumber}</span>
            </td>
          </tr></table>
        </td>
      </tr>

      <!-- Greeting -->
      <tr>
        <td style="padding:32px 40px 0;">
          <p style="font-size:15px;color:#374151;margin:0 0 12px;">Dear <strong>{$clientName}</strong>,</p>
          <p style="font-size:14px;color:#6b7280;line-height:1.7;margin:0;">
            Thank you for your interest. Please find your quotation details below. The attached PDF contains the complete breakdown of items and pricing.
          </p>
        </td>
      </tr>

      <!-- Summary card -->
      <tr>
        <td style="padding:24px 40px;">
          <table width="100%" style="background:#f9fafb;border-radius:6px;border:1px solid #e5e7eb;">
            <tr>
              <td style="padding:20px 24px;">
                <table width="100%">
                  <tr>
                    <td style="font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:1px;padding-bottom:16px;" colspan="2">Quotation Summary</td>
                  </tr>
                  <tr>
                    <td style="font-size:12px;color:#6b7280;padding:5px 0;">Quote Number</td>
                    <td style="font-size:12px;color:#1f2937;font-weight:600;text-align:right;">#{$quoteNumber}</td>
                  </tr>
                  <tr>
                    <td style="font-size:12px;color:#6b7280;padding:5px 0;">Issue Date</td>
                    <td style="font-size:12px;color:#1f2937;font-weight:600;text-align:right;">{$date}</td>
                  </tr>
                  <tr>
                    <td style="font-size:12px;color:#6b7280;padding:5px 0;">Valid Until</td>
                    <td style="font-size:12px;color:#1f2937;font-weight:600;text-align:right;">{$expiry}</td>
                  </tr>
                  <tr>
                    <td style="font-size:14px;color:#1e3a5f;font-weight:700;border-top:1px solid #e5e7eb;padding-top:12px;margin-top:8px;">Total Amount</td>
                    <td style="font-size:18px;color:#1e3a5f;font-weight:700;text-align:right;border-top:1px solid #e5e7eb;padding-top:12px;">{$total}</td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>

      <!-- CTA Buttons -->
      <tr>
        <td style="padding:8px 40px 36px;">
          <table width="100%"><tr>
            <td style="padding-right:8px;">
              <a href="{$viewUrl}" style="display:block;text-align:center;background:#1e3a5f;color:#fff;text-decoration:none;padding:14px 20px;border-radius:6px;font-size:13px;font-weight:600;letter-spacing:0.3px;">
                View Full Quotation
              </a>
            </td>
            <td style="padding-left:8px;">
              <a href="{$acceptUrl}" style="display:block;text-align:center;background:#16a34a;color:#fff;text-decoration:none;padding:14px 20px;border-radius:6px;font-size:13px;font-weight:600;letter-spacing:0.3px;">
                ✓ Accept Quotation
              </a>
            </td>
          </tr></table>
        </td>
      </tr>

      <!-- Footer -->
      <tr>
        <td style="background:#f9fafb;border-top:1px solid #e5e7eb;padding:20px 40px;text-align:center;">
          <p style="font-size:11px;color:#9ca3af;margin:0;line-height:1.6;">
            This quotation was sent by {$company}. If you have any questions, please reply to this email.<br>
            If you did not request this quotation, you may safely ignore this message.
          </p>
        </td>
      </tr>

    </table>
  </td></tr>
</table>
</body>
</html>
HTML;
}

function buildEmailPlain(array $q, string $viewUrl, string $acceptUrl): string {
    return sprintf(
        "Dear %s,\n\nPlease find your quotation #%s attached.\n\nTotal: \$%s\nIssue Date: %s\n\nView Quotation: %s\nAccept Quotation: %s\n\nThank you,\n%s",
        $q['contact_person'] ?: $q['client_name'],
        $q['quote_number'],
        number_format((float)$q['total'], 2),
        date('F j, Y', strtotime($q['created_at'])),
        $viewUrl,
        $acceptUrl,
        SMTP_FROM_NAME
    );
}