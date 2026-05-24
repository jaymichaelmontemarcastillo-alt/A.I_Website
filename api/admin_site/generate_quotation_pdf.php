<?php

/**
 * api/admin_site/generate_quotation_pdf.php
 *
 * FIXED VERSION: Properly handles admin name from AJAX request
 * 
 * Changes:
 *  - Admin name passed from frontend via AJAX
 *  - Item descriptions stored as HTML (from rich contenteditable editor)
 *  - HTML sanitised before rendering in PDF
 *  - render() called BEFORE output()
 */

ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../connect/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Escape a plain string for safe HTML attribute / text output.
 */
function eq(string $val): string
{
    return htmlspecialchars($val, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/**
 * Sanitise HTML that came from the rich-text editor.
 * Allows only safe formatting tags; strips everything else.
 */
function sanitiseDescriptionHtml(string $html): string
{
    if (trim($html) === '') return '';

    // Tags we consider safe for description output
    $allowedTags = '<b><strong><i><em><u><ul><ol><li><br><p><span><div>';

    // Strip disallowed tags first (keeps inner text)
    $clean = strip_tags($html, $allowedTags);

    // Remove all HTML attributes except those on safe tags
    // (protects against style="..." injections etc.)
    $clean = preg_replace('/<([a-z][a-z0-9]*)\s+[^>]*>/i', '<$1>', $clean);

    return $clean;
}

try {
    /* ── 1. DB ───────────────────────────────────────────────────────────── */
    $pdo = getDBConnection();

    /* ── 2. Input ─────────────────────────────────────────────────────────── */
    $input = json_decode(file_get_contents('php://input'), true);
    $id    = isset($input['id']) ? (int) $input['id'] : 0;

    if ($id <= 0) {
        exit(json_encode(['success' => false, 'message' => 'Invalid quotation ID.']));
    }

    /* ── 3. Quotation row ─────────────────────────────────────────────────── */
    $stmt = $pdo->prepare('
        SELECT id, quote_number, client_name, contact_person,
               email, phone, subtotal, tax, discount, total, notes, created_at
        FROM   quotations
        WHERE  id = ?
        LIMIT  1
    ');
    $stmt->execute([$id]);
    $quote = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$quote) {
        exit(json_encode(['success' => false, 'message' => 'Quotation not found.']));
    }

    /* ── 4. Items ─────────────────────────────────────────────────────────── */
    $stmtItems = $pdo->prepare('
        SELECT description, quantity, unit_price, total
        FROM   quotation_items
        WHERE  quotation_id = ?
        ORDER  BY id ASC
    ');
    $stmtItems->execute([$id]);
    $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

    /* ── 5. Computed values ───────────────────────────────────────────────── */
    $subtotal      = (float)($quote['subtotal'] ?? 0);
    $taxPct        = (float)($quote['tax']      ?? 0);
    $discount      = (float)($quote['discount'] ?? 0);
    $taxAmount     = $subtotal * ($taxPct / 100);
    $grandTotal    = (float)($quote['total']    ?? 0);
    $formattedDate = date('F j, Y', strtotime($quote['created_at']));

    /* ── 6. Line-item <tr> rows ───────────────────────────────────────────── */
    $itemRows = '';
    foreach ($items as $item) {
        // Description is HTML from rich editor — sanitise then output as-is.
        $descHtml = sanitiseDescriptionHtml($item['description']);

        $itemRows .= '
            <tr>
                <td class="td-qty">'   . eq((string)$item['quantity'])                . '</td>
                <td class="td-desc">'  . $descHtml                                   . '</td>
                <td class="td-price">' . number_format((float)$item['unit_price'], 2) . '</td>
                <td class="td-total">' . number_format((float)$item['total'],      2) . '</td>
            </tr>';
    }
    if ($itemRows === '') {
        $itemRows = '<tr><td colspan="4" style="text-align:center;padding:10px;color:#9ca3af;">No items.</td></tr>';
    }

    /* ── 7. Summary rows ──────────────────────────────────────────────────── */
    $summaryRows = '';

    if ($taxPct > 0 || $discount > 0) {
        $summaryRows .= '
            <tr class="sum-row">
                <td colspan="3" class="sum-label">Subtotal</td>
                <td class="sum-value">' . number_format($subtotal, 2) . '</td>
            </tr>';
    }
    if ($taxPct > 0) {
        $summaryRows .= '
            <tr class="sum-row">
                <td colspan="3" class="sum-label">Tax (' . number_format($taxPct, 2) . '%)</td>
                <td class="sum-value">' . number_format($taxAmount, 2) . '</td>
            </tr>';
    }
    if ($discount > 0) {
        $summaryRows .= '
            <tr class="sum-row">
                <td colspan="3" class="sum-label">Discount</td>
                <td class="sum-value" style="color:#dc2626;">- ' . number_format($discount, 2) . '</td>
            </tr>';
    }

    $summaryRows .= '
        <tr class="total-row">
            <td colspan="3" class="total-label">Total</td>
            <td class="total-value">' . number_format($grandTotal, 2) . '</td>
        </tr>';

    /* ── 8. Notes block ───────────────────────────────────────────────────── */
    $notesHtml = '';
    $notesRaw  = trim($quote['notes'] ?? '');

    if ($notesRaw !== '') {
        $lines = preg_split('/\r\n|\r|\n/', $notesRaw);
        $lines = array_values(array_filter(array_map('trim', $lines)));

        if (!empty($lines)) {
            $notesHtml .= '<div class="notes-wrap">';
            $notesHtml .= '<div class="notes-heading">Special Notes and Instructions</div>';
            $notesHtml .= '<table class="notes-table" cellpadding="0" cellspacing="0">';

            $itemNumber = 1;
            foreach ($lines as $line) {
                $cleanLine = trim(preg_replace('/^\d+[\.\)]\s*/', '', $line));
                if ($cleanLine === '') continue;

                $notesHtml .= '
                <tr>
                    <td class="notes-num">' . $itemNumber . '.</td>
                    <td class="notes-text">' . eq($cleanLine) . '</td>
                </tr>';
                $itemNumber++;
            }

            $notesHtml .= '</table></div>';
        }
    }

    /* ── 9. Admin user ────────────────────────────────────────────────────── */
    $adminName = 'Authorized Representative';

    // ✅ First priority: Admin name passed from frontend
    if (isset($input['admin_name']) && !empty($input['admin_name'])) {
        $adminName = trim((string)$input['admin_name']);
    }
    // ✅ Fallback: Get from session
    elseif (isset($_SESSION['admin_id'])) {
        $stmtAdmin = $pdo->prepare('SELECT FullName FROM admins WHERE AdminID = ? LIMIT 1');
        $stmtAdmin->execute([$_SESSION['admin_id']]);
        $admin = $stmtAdmin->fetch(PDO::FETCH_ASSOC);
        if ($admin && !empty($admin['FullName'])) {
            $adminName = $admin['FullName'];
        }
    }

    error_log('PDF Generation - Admin Name: ' . $adminName);

    /* ── 10. Logo ─────────────────────────────────────────────────────────── */
    $logoSrc = '';
    $logoPath = __DIR__ . '/../../assets/images/admin-site/header_image.png';

    if (file_exists($logoPath)) {
        $logoData = base64_encode(file_get_contents($logoPath));
        $logoSrc = 'data:image/png;base64,' . $logoData;
    } else {
        // Fallback: SVG placeholder if logo not found
        error_log('Warning: Logo not found at ' . $logoPath);
        $logoSrc = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="110" height="180"%3E%3Crect fill="%23e5e7eb" width="110" height="180"/%3E%3Ctext x="50" y="90" font-family="Arial" font-size="10" fill="%23999" text-anchor="middle" dominant-baseline="middle"%3ELogo%3C/text%3E%3C/svg%3E';
    }

    /* ── 11. Full HTML document ───────────────────────────────────────────── */
    $html = '<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
@page { margin: 0px 40px 40px 40px; }

body, table, td, div, p { margin: 0; padding: 0; }
body {
    font-family: DejaVu Sans, Arial, sans-serif;
    font-size: 14px;
    color: #000000;
    background: #fff;
    padding: 38px 46px 38px 46px;
    line-height: 1.4;
}

/* ─── Letterhead ─── */
.lh-table  { width:100%; padding-bottom:14px; margin-bottom:20px; }
.lh-logo   { width:110px; vertical-align:middle; }
.lh-logo img { width:101%; height:180px; object-fit:cover; }
.lh-text   { vertical-align:middle; padding-left:12px; }
.lh-name   { font-size:25px; font-weight:700; color:#1a56db; letter-spacing:1px; }
.lh-addr   { font-size:10px; color:#374151; margin-top:7px; line-height:1.75; }

/* ─── Client / meta ─── */
.meta-table { width:100%; margin-bottom:18px; }
.meta-left  { width:55%; vertical-align:top; }
.meta-right { width:45%; vertical-align:top; text-align:right; }
.meta-line  { font-size:13px; line-height:1.75; color:#000; }
.meta-bold  { font-weight:700; }
.meta-qnum  { font-size:13px; line-height:1.75; color:#000; }

/* ─── Table heading ─── */
.tbl-heading {
    text-align:center; font-size:20px; font-weight:bold;
    color:#0f3d67; margin-bottom:7px;
}

/* ─── Items table ─── */
.items-table { width:100%; border-collapse:collapse; border:0.8px solid #000; }
.items-table th {
    border:0.8px solid #000; padding:8px 10px; font-size:14px;
    font-weight:700; color:#000; background:#fff; text-align:left;
}
.items-table td {
    border:0.8px solid #000; padding:8px 10px; font-size:14px;
    color:#000; vertical-align:top;
}

/* Description rich-text rendering */
.td-desc ul, .td-desc ol { margin: 4px 0 4px 18px; padding: 0; }
.td-desc li  { margin: 2px 0; font-size:14px; }
.td-desc b, .td-desc strong { font-weight:700; }
.td-desc p   { margin: 2px 0; }

/* Column widths */
.th-qty,  .td-qty  { width:60px;  text-align:center; }
.th-price,.td-price { width:145px; text-align:right; }
.th-total,.td-total { width:120px; text-align:right; }
.td-desc { text-align:left; }

/* Summary rows */
.sum-row .sum-label {
    text-align:right; font-size:14px; color:#000;
    padding:5px 10px; border:0.8px solid #000; font-weight:normal;
}
.sum-row .sum-value {
    text-align:right; font-size:14px; font-weight:600;
    color:#000; padding:5px 10px; border:0.8px solid #000;
}
.total-row .total-label {
    text-align:right; font-size:14px; font-weight:700;
    color:#000; padding:8px 10px; border:0.8px solid #000;
    background:#f3f4f6;
}
.total-row .total-value {
    text-align:right; font-size:14px; font-weight:700;
    color:#000; padding:8px 10px; border:0.8px solid #000;
    background:#f3f4f6;
}

/* ─── Notes ─── */
.notes-wrap    { margin-top:20px; margin-bottom:22px; width:100%; }
.notes-heading { font-size:14px; font-weight:700; color:#000; margin-bottom:3px; }
.notes-table   { width:100%; border-collapse:collapse; }
.notes-num  { width:25px; font-size:9px; color:#000; text-align:right; vertical-align:top; padding-top:2px; }
.notes-text { font-size:9px; color:#000; line-height:1.6; padding-left:10px; padding-bottom:5px; vertical-align:top; }

/* ─── Signature ─── */
.sig-wrap      { margin-top:34px; }
.sig-prepared  { font-size:12px; color:#000; font-weight:normal; }
.sig-name-line { font-size:12px; font-weight:700; color:#000; min-width:190px; text-align:left; }
.sig-company   { font-size:11px; color:#000; margin-top:3px; text-align:left; }
</style>
</head>
<body>

<!-- LETTERHEAD -->
<table class="lh-table" cellpadding="0" cellspacing="0">
    <tr>
        <td class="lh-logo">
            <img src="' . $logoSrc . '" alt="Anything Inside">
        </td>
    </tr>
</table>

<!-- CLIENT / QUOTE META -->
<table class="meta-table" cellpadding="0" cellspacing="0">
    <tr>
        <td class="meta-left">
            <div class="meta-line"><span class="meta-bold">To: </span>' . eq($quote['client_name']) . '</div>
            <div class="meta-line"><span class="meta-bold">Contact Person: </span>' . eq($quote['contact_person'] ?? '') . '</div>
        </td>
        <td class="meta-right">
            <div class="meta-qnum">
                Quotation Number: <strong>' . eq($quote['quote_number']) . '</strong><br>
                Date: ' . eq($formattedDate) . '
            </div>
        </td>
    </tr>
</table>

<!-- ITEMS TABLE -->
<div class="tbl-heading">Quotation Details</div>
<table class="items-table" cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th class="th-qty">Qty</th>
            <th>Item/Description</th>
            <th class="th-price">Unit Price (Php)</th>
            <th class="th-total">Total (Php)</th>
        </tr>
    </thead>
    <tbody>
        ' . $itemRows . '
        ' . $summaryRows . '
    </tbody>
</table>

<!-- NOTES -->
' . $notesHtml . '

<!-- SIGNATURE -->
<div class="sig-wrap">
    <div class="sig-prepared">Prepared by:</div>
    <div class="sig-name-line">' . eq($adminName) . '</div>
    <div class="sig-company">Anything Inside</div>
</div>

</body>
</html>';

    /* ── 12. Dompdf - Initialize ──────────────────────────────────────────── */
    $opts = new Options();
    $opts->set('defaultFont',          'DejaVu Sans');
    $opts->set('isRemoteEnabled',      false);
    $opts->set('isHtml5ParserEnabled', true);

    $dompdf = new Dompdf($opts);

    /* ── 13. Load and Render ──────────────────────────────────────────────── */
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();  // ✅ RENDER FIRST

    /* ── 14. Validate output ──────────────────────────────────────────────── */
    $pdfOutput = $dompdf->output();

    if (strlen($pdfOutput) < 1000) {
        throw new Exception('PDF output too small (' . strlen($pdfOutput) . ' bytes). Rendering may have failed.');
    }

    /* ── 15. Save to disk ─────────────────────────────────────────────────── */
    $uploadDir = __DIR__ . '/../../uploads/quotations/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (!is_writable($uploadDir)) {
        throw new Exception('Upload directory not writable: ' . $uploadDir);
    }

    $safeNum  = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $quote['quote_number']);
    $filename = 'quotation_' . $safeNum . '_' . date('Ymd_His') . '.pdf';
    $filePath = $uploadDir . $filename;

    if (file_put_contents($filePath, $pdfOutput) === false) {
        throw new Exception('Failed to write PDF file: ' . $filePath);
    }

    $pdfUrl = '/Anything_Inside_Website/uploads/quotations/' . $filename;

    /* ── 16. Update database ──────────────────────────────────────────────── */
    $updateStmt = $pdo->prepare("
        UPDATE quotations
        SET pdf_url = ?, status = 'converted'
        WHERE id = ?
    ");
    $updateStmt->execute([$pdfUrl, $id]);

    /* ── 17. Success response ─────────────────────────────────────────────── */
    echo json_encode([
        'success' => true,
        'pdf_url' => $pdfUrl,
        'filename' => $filename,
        'message' => 'PDF generated successfully.',
    ]);
} catch (Throwable $e) {
    error_log('generate_quotation_pdf.php ERROR: ' . $e->getMessage()
        . ' in ' . $e->getFile() . ':' . $e->getLine());

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
    ]);
}

exit;
