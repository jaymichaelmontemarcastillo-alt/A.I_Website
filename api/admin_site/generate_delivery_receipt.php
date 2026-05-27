<?php

/**
 * api/admin_site/generate_delivery_receipt.php
 * Generates Delivery Receipt PDF - EXACT layout matching example image
 * - Received by is BELOW Delivered by (not side by side)
 * - Left margin larger than right margin
 * - Header contains ONLY the logo image (the image itself has all text)
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

function eq(string $val): string
{
    return htmlspecialchars($val, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function sanitiseDescriptionHtml(string $html): string
{
    if (trim($html) === '') return '';
    $allowedTags = '<b><strong><i><em><u><ul><ol><li><br><p><span><div>';
    $clean = strip_tags($html, $allowedTags);
    $clean = preg_replace('/<([a-z][a-z0-9]*)\s+[^>]*>/i', '<$1>', $clean);
    return $clean;
}

function generateDRNumber($pdo)
{
    $year  = date('Y');
    $month = date('m');

    $stmt = $pdo->prepare("
        SELECT dr_number FROM delivery_receipts
        WHERE dr_number LIKE ?
        ORDER BY id DESC LIMIT 1
    ");
    $pattern = "DR-$year-$month-%";
    $stmt->execute([$pattern]);
    $last = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($last) {
        $lastNum = (int) substr($last['dr_number'], -4);
        $newNum  = str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
    } else {
        $newNum = '0001';
    }

    return "DR-$year-$month-$newNum";
}

try {
    $pdo = getDBConnection();
    if (!$pdo) {
        throw new Exception('Database connection failed.');
    }

    // ── Input ──────────────────────────────────────────────────────────────
    $input       = json_decode(file_get_contents('php://input'), true);
    $quotationId = isset($input['id']) ? (int) $input['id']
        : (isset($_GET['id'])  ? (int) $_GET['id'] : 0);

    if ($quotationId <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid quotation ID.']);
        exit;
    }

    // ── Quotation row ──────────────────────────────────────────────────────
    $stmt = $pdo->prepare('
        SELECT id, quote_number, client_name, contact_person,
               email, phone, address, subtotal, tax, discount, total, notes, created_at, status
        FROM quotations
        WHERE id = ?
        LIMIT 1
    ');
    $stmt->execute([$quotationId]);
    $quote = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$quote) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Quotation not found.']);
        exit;
    }

    // ── Items ──────────────────────────────────────────────────────────────
    $stmtItems = $pdo->prepare('
        SELECT description, quantity, unit_price, total
        FROM quotation_items
        WHERE quotation_id = ?
        ORDER BY id ASC
    ');
    $stmtItems->execute([$quotationId]);
    $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

    // ── Computed values ────────────────────────────────────────────────────
    $drNumber      = generateDRNumber($pdo);
    $formattedDate = date('M d, Y', strtotime($quote['created_at']));
    $orderNo       = $quote['quote_number'];
    $grandTotal    = (float) $quote['total'];
    $itemsJson     = json_encode($items);

    // ── Admin name ─────────────────────────────────────────────────────────
    $adminName = 'Blessie Mae Mabilangan';
    $adminId   = null;
    if (isset($input['admin_name']) && !empty($input['admin_name'])) {
        $adminName = trim((string) $input['admin_name']);
    } elseif (isset($_SESSION['admin_id'])) {
        $adminId    = (int) $_SESSION['admin_id'];
        $stmtAdmin  = $pdo->prepare('SELECT FullName FROM admins WHERE AdminID = ? LIMIT 1');
        $stmtAdmin->execute([$adminId]);
        $admin = $stmtAdmin->fetch(PDO::FETCH_ASSOC);
        if ($admin && !empty($admin['FullName'])) {
            $adminName = $admin['FullName'];
        }
    }

    // ── Deliver-to address ─────────────────────────────────────────────────
    $clientName    = $quote['client_name'];
    $clientAddress = $quote['address'] ?? '';

    // ── Insert delivery_receipts ───────────────────────────────────────────
    $insertStmt = $pdo->prepare("
        INSERT INTO delivery_receipts
        (dr_number, quotation_id, order_no, client_name, client_address, client_phone,
         delivered_by, delivery_date, items, total_amount, pdf_url, created_by)
        VALUES
        (:dr_number, :quotation_id, :order_no, :client_name, :client_address, :client_phone,
         :delivered_by, :delivery_date, :items, :total_amount, :pdf_url, :created_by)
    ");
    $insertStmt->execute([
        ':dr_number'      => $drNumber,
        ':quotation_id'   => $quotationId,
        ':order_no'       => $orderNo,
        ':client_name'    => $clientName,
        ':client_address' => $clientAddress,
        ':client_phone'   => $quote['phone'] ?? '',
        ':delivered_by'   => $adminName,
        ':delivery_date'  => date('Y-m-d'),
        ':items'          => $itemsJson,
        ':total_amount'   => $grandTotal,
        ':pdf_url'        => '',
        ':created_by'     => $adminId,
    ]);
    $drId = $pdo->lastInsertId();

    // ── Logo (base64) - this image contains ALL header text (Address, Contact, Email)
    $logoSrc  = '';
    $logoPath = __DIR__ . '/../../assets/images/delivery_receipt_header.png';
    if (file_exists($logoPath)) {
        $logoData = base64_encode(file_get_contents($logoPath));
        $logoSrc  = 'data:image/png;base64,' . $logoData;
    }

    // ── Build item table rows ───────────────────────────────────────────────
    $itemRows = '';
    foreach ($items as $item) {
        $descHtml  = sanitiseDescriptionHtml($item['description']);
        $itemRows .= '
            <tr>
                <td class="td-desc">'  . $descHtml                                    . '</td>
                <td class="td-qty">'   . eq((string) $item['quantity'])               . '</td>
                <td class="td-price">' . number_format((float) $item['unit_price'], 2) . '</td>
                <td class="td-total">' . number_format((float) $item['total'],      2) . '</td>
            </tr>';
    }

    // ── Full HTML document with EXACT layout matching image ─────────────────
    // Left margin larger than right (padding-left: 50px, padding-right: 30px)
    // "Received by" is BELOW "Delivered by" - NOT side by side
    $html = '<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>

@page {
    margin: 0;
    size: A4 portrait;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    font-size: 11pt;
    color: #000000;
    background: #ffffff;
    /* Left margin larger than right margin - exactly as image */
    padding: 30px 30px 40px 50px;
    line-height: 1.45;
}

/* ── HEADER: Only the logo image (image contains all text: Address, Contact, Email) ── */
.header-logo {
    width: 100%;
    height: 200px;
    margin-bottom: 25px;
    text-align: center;

}
    
.header-logo img {
    max-width: 100%;
        height: 170px;
    object-fit: contain;
}

/* ── DATE AND ORDER NO (stacked vertically, left aligned) ───────────────── */
.meta-block {
    margin-bottom: 25px;
    padding-left: 40px;
}
.meta-line {
    font-size: 10pt;
}
.meta-label {
    font-weight: 700;
    display: inline-block;
    width: 80px;
}
.meta-value {
    font-weight: normal;
    display: inline-block;
}

/* ── TITLE ──────────────────────────────────────────────────────────────── */
.doc-title {
    text-align: center;
    font-size: 20px;
    font-weight: 800;
    color: #0a3e6d;
    margin: 15px 0 20px 0;
    margin-bottom: 50px;
    text-transform: uppercase;
}

/* ── DELIVER TO SECTION ─────────────────────────────────────────────────── */
.deliver-to-table {
    width: 100%;
    margin-bottom: 35px;
    border-collapse: collapse;
     padding-left: 40px;
}
.deliver-label {
    font-size: 10pt;
    font-weight: 800;
    color: #000000;
    width: 85px;
    vertical-align: top;
}
.deliver-value {
    font-size: 10pt;
    font-weight: 800;
    color: #000000;
    padding-left: 20px;
    vertical-align: top;
}
.deliver-address {
    font-size: 10pt;
    font-weight: normal;
    color: #000000;
    margin-top: 2px;
    line-height: 1.4;
}

/* ── ITEMS SECTION ─────────────────────────────────────────────────────── */
.section-label {
    font-size: 11pt;
    font-weight: 800;
    color: #000000;
    margin-bottom: 8px;
     padding-left: 40px;
}

.items-table {
    width: 97%;
    border-collapse: collapse;
    margin-bottom: 5px;
     padding-left: 40px;
}
.items-table th {
    border: 1px solid #000000;
    padding: 8px 10px;
    font-size: 10pt;
    font-weight: 800;
    background: #f5f5f5;
    text-align: center;
}
.items-table td {
    border: 1px solid #000000;
    padding: 8px 10px;
    font-size: 10pt;
    vertical-align: top;
}
.td-desc {
    text-align: left;
    width: 52%;
}
.td-qty {
    text-align: center;
    width: 12%;
}
.td-price {
    text-align: right;
    width: 18%;
}
.td-total {
    text-align: right;
    width: 18%;
}
.total-row td {
    font-weight: 800;
    border-top: 1px solid #000000;
}
.total-label {
    text-align: right;
}
.total-value {
    text-align: right;
}

/* Content styling inside description */
.td-desc b, .td-desc strong { font-weight: 800; }
.td-desc ul, .td-desc ol { margin: 3px 0 3px 18px; padding: 0; }
.td-desc li { margin: 2px 0; }
.td-desc p { margin: 3px 0; }

/* ── SIGNATURE SECTION - Delivered by FIRST, then Received by BELOW (NOT side by side) ── */
.signature-delivered {
    width: 100%;
    margin-top: 35px;
    margin-bottom: 30px;
     padding-left: 40px;
}
.sig-heading {
    font-size: 10pt;
    font-weight: 800;
    color: #000000;
    margin-bottom: 60px;
}
.sig-line {
    border-top: 1px solid #000000;
    width: 70%;
    margin-bottom: 6px;
}
.sig-name {
    font-size: 10pt;
    font-weight: 800;
    color: #000000;
    margin-top: 5px;
}
.sig-company {
    font-size: 9pt;
    color: #333333;
    margin-top: 2px;
}

/* Received by section - placed BELOW Delivered by */
.signature-received {
    width: 100%;
    margin-top: 10px;
     padding-left: 40px;
}
.received-heading {
    font-size: 10pt;
    font-weight: 800;
    color: #000000;
    margin-bottom: 6px;
}
.received-note {
    font-size: 8pt;
    font-style: italic;
    color: #555555;
    margin-bottom: 12px;
    line-height: 1.35;
}
.received-signature-line {
    border-top: 1px solid #000000;
    width: 70%;
    margin-bottom: 18px;
}
.received-field-line {
    font-size: 9pt;
    margin-bottom: 12px;
}
.received-field-label {
    display: inline-block;
    width: 50px;
    font-weight: normal;
}
.received-field-dash {
    display: inline-block;
    width: 180px;
    border-bottom: 1px solid #000000;
    margin-left: 8px;
}
.signature-printed-line {
    margin-bottom: 15px;
}
.signature-printed-label {
    font-size: 9pt;
    display: inline-block;
    width: 160px;
}
.signature-printed-dash {
    display: inline-block;
    width: 200px;
    border-bottom: 1px solid #000000;
    margin-left: 8px;
}

</style>
</head>
<body>

<!-- ── HEADER: Only the logo image (image contains Address, Contact, Email text) ── -->
<div class="header-logo">
    ' . ($logoSrc ? '<img src="' . $logoSrc . '" alt="Anything Inside Printing Services">' : '<div style="font-weight:800; font-size:18px;">ANYTHING INSIDE PRINTING SERVICES</div>') . '
</div>

<!-- ── DATE AND ORDER NO (stacked, left aligned) ───────────────────────── -->
<div class="meta-block">
    <div class="meta-line">
        <span class="meta-label">DATE:</span>
        <span class="meta-value">' . eq($formattedDate) . '</span>
    </div>
    <div class="meta-line">
        <span class="meta-label">ORDER NO:</span>
        <span class="meta-value">' . eq($orderNo) . '</span>
    </div>
</div>

<!-- ── TITLE: DELIVERY RECEIPT ─────────────────────────────────────────── -->
<div class="doc-title">DELIVERY RECEIPT</div>

<!-- ── DELIVER TO (exactly as image) ───────────────────────────────────── -->
<table class="deliver-to-table" cellpadding="0" cellspacing="0">
    <tr>
        <td class="deliver-label">DELIVER TO:</td>
        <td class="deliver-value">
            ' . eq($clientName) . '
            ' . (!empty($clientAddress) ? '<div class="deliver-address">' . eq($clientAddress) . '</div>' : '') . '
         </td>
    </tr>
</table>

<!-- ── ITEMS TO BE RECEIVED ───────────────────────────────────────────── -->
<div class="section-label">Items to be Received:</div>

<table class="items-table" cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th class="td-desc">Description</th>
            <th class="td-qty">Quantity</th>
            <th class="td-price">Unit Price</th>
            <th class="td-total">Total Amount</th>
        </tr>
    </thead>
    <tbody>
        ' . $itemRows . '
        <tr class="total-row">
            <td colspan="3" class="total-label">Total</td>
            <td class="total-value">' . number_format($grandTotal, 2) . '</td>
        </tr>
    </tbody>
</table>

<!-- ── SIGNATURE SECTION: Delivered by FIRST, THEN Received by BELOW (exactly as image) ── -->
<!-- Delivered by section -->
<div class="signature-delivered">
    <div class="sig-heading">Delivered by:</div>
    <div class="sig-name">' . eq($adminName) . '</div>
    <div class="sig-company">Anything Inside Printing Services</div>
</div>

<!-- Received by section - placed BELOW Delivered by, NOT side by side -->
<div class="signature-received">
    <div class="received-heading">Received by:</div>
    <div class="received-note">
        Note Upon Signing:<br>
        Received the item(s) are complete and are in good condition.
    </div>

    
    <!-- Signature over Printed Name field (exactly as image shows) -->
    <div class="signature-printed-line">
        <span class="signature-printed-label">Signature over Printed Name:</span>
        <span class="signature-printed-dash"></span>
    </div>
    
    <div class="received-field-line">
        <span class="received-field-label">Office:</span>
        <span class="received-field-dash"></span>
    </div>
    <div class="received-field-line">
        <span class="received-field-label">Date:</span>
        <span class="received-field-dash"></span>
    </div>
</div>

</body>
</html>';

    // ── Dompdf ─────────────────────────────────────────────────────────────
    $opts = new Options();
    $opts->set('defaultFont',          'Helvetica');
    $opts->set('isRemoteEnabled',      false);
    $opts->set('isHtml5ParserEnabled', true);

    $dompdf = new Dompdf($opts);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $pdfOutput = $dompdf->output();

    if (strlen($pdfOutput) < 1000) {
        throw new Exception('PDF generation failed — output too small.');
    }

    // ── Save to disk ───────────────────────────────────────────────────────
    $uploadDir = __DIR__ . '/../../uploads/delivery_receipts/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $filename = 'delivery_receipt_' . $drNumber . '_' . date('Ymd_His') . '.pdf';
    $filePath = $uploadDir . $filename;
    $pdfUrl   = '/Anything_Inside_Website/uploads/delivery_receipts/' . $filename;

    file_put_contents($filePath, $pdfOutput);

    // ── Update DB ──────────────────────────────────────────────────────────
    $pdo->prepare("UPDATE delivery_receipts SET pdf_url = ? WHERE id = ?")
        ->execute([$pdfUrl, $drId]);

    $pdo->prepare("UPDATE quotations SET pdf_url = ? WHERE id = ?")
        ->execute([$pdfUrl, $quotationId]);

    echo json_encode([
        'success'   => true,
        'pdf_url'   => $pdfUrl,
        'dr_number' => $drNumber,
        'dr_id'     => $drId,
        'filename'  => $filename,
        'message'   => 'Delivery Receipt generated successfully.',
    ]);
} catch (Exception $e) {
    error_log('generate_delivery_receipt.php ERROR: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
    ]);
}

exit;
