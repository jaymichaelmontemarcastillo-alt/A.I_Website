<?php

/**
 * api/admin_site/generate_quotation_docx.php
 *
 * Generates an MS Word-compatible document (HTML → .doc export).
 * Responsive layout: percentage-based widths, pt font-sizes, fluid logo.
 */

ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(dirname(__DIR__)) . '/connect/config.php';

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

    $allowedTags = '<b><strong><i><em><u><ul><ol><li><br><p><span><div>';
    $clean = strip_tags($html, $allowedTags);
    $clean = preg_replace('/<([a-z][a-z0-9]*)\s+[^>]*>/i', '<$1>', $clean);

    return $clean;
}

try {
    $pdo = getDBConnection();
    
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    if ($id <= 0) {
        die('Invalid quotation ID.');
    }

    $stmt = $pdo->prepare('
        SELECT id, quote_number, client_name, contact_person,
               email, phone, subtotal, tax, discount, total, notes, created_at, address
        FROM   quotations
        WHERE  id = ?
        LIMIT  1
    ');
    $stmt->execute([$id]);
    $quote = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$quote) {
        die('Quotation not found.');
    }

    $stmtItems = $pdo->prepare('
        SELECT description, quantity, unit_price, total
        FROM   quotation_items
        WHERE  quotation_id = ?
        ORDER  BY id ASC
    ');
    $stmtItems->execute([$id]);
    $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

    $subtotal      = (float)($quote['subtotal'] ?? 0);
    $taxPct        = (float)($quote['tax']      ?? 0);
    $discount      = (float)($quote['discount'] ?? 0);
    $taxAmount     = $subtotal * ($taxPct / 100);
    $grandTotal    = (float)($quote['total']    ?? 0);
    $formattedDate = date('F j, Y', strtotime($quote['created_at']));

    // ── Item rows ──────────────────────────────────────────────────────────────
    $itemRows = '';
    foreach ($items as $item) {
        $descHtml = sanitiseDescriptionHtml($item['description']);
        $itemRows .= '
            <tr>
                <td class="col-qty">'   . eq((string)$item['quantity'])                . '</td>
                <td class="col-desc">'  . $descHtml                                   . '</td>
                <td class="col-price">' . number_format((float)$item['unit_price'], 2) . '</td>
                <td class="col-total">' . number_format((float)$item['total'],      2) . '</td>
            </tr>';
    }
    if ($itemRows === '') {
        $itemRows = '<tr><td colspan="4" style="text-align:center;padding:8pt;color:#9ca3af;">No items.</td></tr>';
    }

    // ── Summary rows ───────────────────────────────────────────────────────────
    $summaryRows = '';
    if ($taxPct > 0 || $discount > 0) {
        $summaryRows .= '
            <tr>
                <td colspan="3" class="sum-label">Subtotal</td>
                <td class="sum-value">' . number_format($subtotal, 2) . '</td>
            </tr>';
    }
    if ($taxPct > 0) {
        $summaryRows .= '
            <tr>
                <td colspan="3" class="sum-label">Tax (' . number_format($taxPct, 2) . '%)</td>
                <td class="sum-value">' . number_format($taxAmount, 2) . '</td>
            </tr>';
    }
    if ($discount > 0) {
        $summaryRows .= '
            <tr>
                <td colspan="3" class="sum-label">Discount</td>
                <td class="sum-value" style="color:#dc2626;">- ' . number_format($discount, 2) . '</td>
            </tr>';
    }
    $summaryRows .= '
        <tr>
            <td colspan="3" class="total-label">Total</td>
            <td class="total-value">' . number_format($grandTotal, 2) . '</td>
        </tr>';

    // ── Special Notes ──────────────────────────────────────────────────────────
    $specialNotes = [
        'File Formats: Please submit your file in the following formats: PDF, TIFF, PNG or high-resolution JPEG for best results.',
        'Color Specifications: Ensure your files are set to CMYK color mode to avoid any discrepancies in color printing.',
        'Proof Approval: A digital proof and test print will be sent for your approval before the final print run. Please review carefully to avoid any errors.',
        'Payment Terms: Full payment is due upon delivery of the product, with 50% required downpayment upon order confirmation.',
        'Delivery & Lead Time: The delivery time may vary depending on the quantity of your order. Standard turnaround time will be provided once we confirm the details of your order. Please let us know if you have any specific deadlines, and we\'ll do our best to accommodate them.',
        'Shipment and fees: Customers can pick up their orders at our designated pickup points in Laguna and Mandaluyong. Please confirm your preferred pickup location when placing the order. Shipping fees will be shouldered by the client and are based on the courier\'s delivery rates.',
        'Additional Requests: For custom sizes or any specific finishing (e.g., lamination, binding), please mention your requirements when placing the order.'
    ];

    // ── Admin name ─────────────────────────────────────────────────────────────
    $adminName = 'Authorized Representative';
    if (isset($_POST['admin_name']) && !empty($_POST['admin_name'])) {
        $adminName = trim((string)$_POST['admin_name']);
    } elseif (isset($_SESSION['admin_id'])) {
        $stmtAdmin = $pdo->prepare('SELECT FullName FROM admins WHERE AdminID = ? LIMIT 1');
        $stmtAdmin->execute([$_SESSION['admin_id']]);
        $admin = $stmtAdmin->fetch(PDO::FETCH_ASSOC);
        if ($admin && !empty($admin['FullName'])) {
            $adminName = $admin['FullName'];
        }
    }

    // ── Logo (embedded base64) ─────────────────────────────────────────────────
    $logoSrc  = '';
    $logoPath = dirname(dirname(__DIR__)) . '/assets/images/New_AI_Logo.png';
    if (!empty($logoPath) && file_exists($logoPath)) {
        $rawLogo = @file_get_contents($logoPath);
        if ($rawLogo !== false) {
            $logoSrc = 'data:image/png;base64,' . base64_encode($rawLogo);
        }
    }
    if (empty($logoSrc)) {
        // 1×1 transparent placeholder
        $logoSrc = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';
    }

    // ── Output headers ─────────────────────────────────────────────────────────
    $filename = 'Quotation_' . str_replace(' ', '_', $quote['quote_number']) . '.doc';
    header('Content-Type: application/vnd.ms-word');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('content-disposition: attachment;filename=' . $filename);

    // ── Build note rows ────────────────────────────────────────────────────────
    $noteRows = '';
    $noteNumber = 1;
    foreach ($specialNotes as $note) {
        $noteRows .= '
        <tr>
            <td class="sn-num">' . $noteNumber . '.</td>
            <td class="sn-text">' . eq($note) . '</td>
        </tr>';
        $noteNumber++;
    }

    // ── Address line helper ────────────────────────────────────────────────────
    $addressLine = !empty($quote['address'])
        ? '<p><strong>Address:</strong> ' . eq($quote['address']) . '</p>'
        : '';

    // Safe escaped vars for heredoc
    $clientName     = eq($quote['client_name']);
    $contactPerson  = eq($quote['contact_person'] ?? '');
    $quoteNumber    = eq($quote['quote_number']);
    $adminNameEsc   = eq($adminName);

    // ── HTML document ──────────────────────────────────────────────────────────
    echo <<<HTML
<html xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:w="urn:schemas-microsoft-com:office:word"
      xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta charset="UTF-8">
<style>
/* ── Page setup ── */
@page WordSection1 {
    size: 8.5in 11in;
    margin: 0.75in 0.75in 0.75in 0.75in;
    mso-header-margin: 0.5in;
    mso-footer-margin: 0.5in;
}
div.WordSection1 { page: WordSection1; }

/* ── Base ── */
body, table, td, p, div { margin: 0; padding: 0; box-sizing: border-box; }
body {
    font-family: Arial, sans-serif;
    font-size: 11pt;
    color: #000;
    background: #fff;
}
p { margin-bottom: 3pt; }

/* ── Letterhead ── */
.lh-table   { width: 82%; border-collapse: collapse; }
.lh-logo    { width: 100pt; vertical-align: middle; border: none; padding: 0; text-align: left; }
.lh-logo img{ width: 100pt; height: auto; display: block; }
.lh-text    { vertical-align: middle; padding-left: 18pt; border: none; }
.lh-brand   {
    font-family: Arial, sans-serif;
    font-size: 28pt;
    font-weight: bold;
    color: #2e3092;
    margin: 0 0 4pt 0;
    letter-spacing: 0.5px;
    line-height: 1.1;
}
.lh-info    { font-size: 10pt; font-weight: bold; color: #000; line-height: 1.6; }
.lh-info strong { font-weight: bold; }

/* ── Client / meta section ── */
.meta-table { width: 100%; border-collapse: collapse; margin-bottom: 18pt; }
.meta-table td { border: none; vertical-align: top; font-size: 11pt; line-height: 1.6; padding: 0; }

/* ── Section heading ── */
.tbl-heading {
    text-align: center;
    font-size: 16pt;
    font-weight: bold;
    color: #0f3d67;
    margin: 14pt 0 6pt 0;
}

/* ── Items table ── */
.items-table { width: 100%; border-collapse: collapse; }
.items-table th {
    border: 1pt solid #000;
    padding: 5pt 7pt;
    font-size: 10pt;
    font-weight: bold;
    background-color: #f3f4f6;
    vertical-align: middle;
}
.items-table td {
    border: 1pt solid #000;
    padding: 5pt 7pt;
    font-size: 10pt;
    vertical-align: top;
}
.col-qty   { width: 7%;  text-align: center; }
.col-desc  { width: 54%; text-align: left;   }
.col-price { width: 20%; text-align: right;  }
.col-total { width: 19%; text-align: right;  }

/* ── Summary / total ── */
.sum-label  { text-align: right; font-size: 10pt; border: 1pt solid #000; padding: 4pt 7pt; }
.sum-value  { text-align: right; font-size: 10pt; font-weight: 600; border: 1pt solid #000; padding: 4pt 7pt; }
.total-label{ text-align: right; font-size: 10pt; font-weight: bold; border: 1pt solid #000; padding: 5pt 7pt; background-color: #f3f4f6; }
.total-value{ text-align: right; font-size: 10pt; font-weight: bold; border: 1pt solid #000; padding: 5pt 7pt; background-color: #f3f4f6; }

/* ── Special Notes ── */
.sn-heading { font-size: 11pt; font-weight: bold; margin: 16pt 0 4pt 0; }
.sn-table   { width: 100%; border-collapse: collapse; }
.sn-num     { font-size: 10pt; width: 18pt; vertical-align: top; padding-top: 1pt; white-space: nowrap; }
.sn-text    { font-size: 10pt; line-height: 1.55; padding-left: 5pt; padding-bottom: 4pt; vertical-align: top; }

/* ── Signature ── */
.sig-wrap { margin-top: 22pt; font-size: 11pt; }
</style>
</head>
<body>
<div class="WordSection1">

<!-- LETTERHEAD -->
<table class="lh-table" cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td class="lh-logo">
      <img src="{$logoSrc}" alt="Anything Inside Logo" width="100" style="width:100pt;height:auto;display:block;">
    </td>
    <td class="lh-text">
      <div class="lh-brand">ANYTHING INSIDE</div>
      <div class="lh-info">
        <strong>Address:</strong> Plaza Agapita Commercial Complex, Los Ba&ntilde;os, Laguna<br>
        <strong>Contact No.:</strong> 0968 730 5403<br>
        <strong>Email Address:</strong> <u>ai.anythinginside@gmail.com</u>
      </div>
    </td>
  </tr>
</table>
<p style="font-size:28pt;line-height:28pt;margin:0;">&nbsp;</p>

<!-- CLIENT / META -->
<table class="meta-table">
  <tr>
    <td style="width:55%;">
      <p><strong>To:</strong> {$clientName}</p>
      <p><strong>Contact Person:</strong> {$contactPerson}</p>
      {$addressLine}
    </td>
    <td style="width:45%; text-align:right;">
      <p>Quotation Number: <strong>{$quoteNumber}</strong></p>
      <p>Date: {$formattedDate}</p>
    </td>
  </tr>
</table>

<!-- ITEMS TABLE -->
<div class="tbl-heading">Quotation Details</div>
<table class="items-table">
  <thead>
    <tr>
      <th class="col-qty">Qty</th>
      <th class="col-desc">Item/Description</th>
      <th class="col-price">Unit Price (Php)</th>
      <th class="col-total">Total (Php)</th>
    </tr>
  </thead>
  <tbody>
    {$itemRows}
    {$summaryRows}
  </tbody>
</table>

<!-- SPECIAL NOTES -->
<div class="sn-heading">Special Notes and Instructions</div>
<table class="sn-table">
  {$noteRows}
</table>

<!-- SIGNATURE -->
<div class="sig-wrap">
  <p><strong>Prepared by:</strong><br>{$adminNameEsc}<br>Anything Inside</p>
</div>

</div>
</body>
</html>
HTML;

} catch (Exception $e) {
    error_log('generate_quotation_docx.php Exception: ' . $e->getMessage());
    die('An error occurred generating the DOCX document.');
}
