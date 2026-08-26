<?php
require_once '../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    http_response_code(400);
    echo "No data provided";
    exit;
}

$format = isset($data['format']) ? $data['format'] : 'pdf';

// Build HTML content
$html = "
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Inventory Audit</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; padding: 0; font-size: 18px; }
        .header p { margin: 5px 0; font-size: 14px; }
        .section-title { font-size: 14px; font-weight: bold; margin-bottom: 8px; border-bottom: 1px solid #ddd; padding-bottom: 4px; font-family: Arial, sans-serif; }
    </style>
</head>
<body style=\"font-family: Arial, sans-serif; font-size: 12px; color: #333;\">

    <div class='header' style=\"text-align: center; margin-bottom: 20px;\">
        <h2 style=\"margin: 0; padding: 0; font-size: 18px;\">INVENTORY AUDIT</h2>
        <p style=\"margin: 5px 0; font-size: 14px;\">" . htmlspecialchars($data['audit_name'] ?? '') . "</p>
    </div>

    <table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" style=\"margin-bottom: 20px; font-family: Arial, sans-serif; font-size: 12px; line-height: 1.2;\">
        <tr>
            <td width=\"80\" style=\"font-weight: bold; vertical-align: top; padding: 2px 0;\">Quote #:</td>
            <td style=\"vertical-align: top; padding: 2px 0;\">" . htmlspecialchars($data['quote_number'] ?? '') . "</td>
            <td width=\"80\" style=\"font-weight: bold; vertical-align: top; padding: 2px 0;\">Customer:</td>
            <td style=\"vertical-align: top; padding: 2px 0;\">" . htmlspecialchars($data['customer'] ?? '') . "</td>
        </tr>
        <tr>
            <td style=\"font-weight: bold; vertical-align: top; padding: 2px 0;\">Contact:</td>
            <td style=\"vertical-align: top; padding: 2px 0;\">" . htmlspecialchars($data['contact'] ?? '') . "</td>
            <td style=\"font-weight: bold; vertical-align: top; padding: 2px 0;\">Email:</td>
            <td style=\"vertical-align: top; padding: 2px 0;\">" . htmlspecialchars($data['email'] ?? '') . "</td>
        </tr>
        <tr>
            <td style=\"font-weight: bold; vertical-align: top; padding: 2px 0;\">Address:</td>
            <td colspan='3' style=\"vertical-align: top; padding: 2px 0;\">" . htmlspecialchars($data['address'] ?? '') . "</td>
        </tr>
    </table>

    <div class='section-title' style=\"font-size: 14px; font-weight: bold; margin-bottom: 8px; border-bottom: 1px solid #ddd; padding-bottom: 4px;\">Materials Used</div>
    <table width=\"100%\" border=\"1\" cellpadding=\"6\" cellspacing=\"0\" style=\"border-collapse: collapse; margin-bottom: 20px; border: 1px solid #ddd; font-family: Arial, sans-serif; font-size: 12px;\">
        <thead>
            <tr>
                <th style=\"background-color: #f5f5f5; text-align: left; font-weight: bold;\">Material Name</th>
                <th style=\"background-color: #f5f5f5; text-align: right; font-weight: bold;\">Qty</th>
                <th style=\"background-color: #f5f5f5; text-align: right; font-weight: bold;\">Price</th>
                <th style=\"background-color: #f5f5f5; text-align: right; font-weight: bold;\">Subtotal</th>
            </tr>
        </thead>
        <tbody>";
if (!empty($data['materials'])) {
    foreach ($data['materials'] as $mat) {
        $html .= "<tr>
            <td style=\"border: 1px solid #ddd;\">" . htmlspecialchars($mat['name']) . "</td>
            <td align=\"right\" style=\"border: 1px solid #ddd; text-align: right;\">" . htmlspecialchars($mat['qty']) . "</td>
            <td align=\"right\" style=\"border: 1px solid #ddd; text-align: right;\">" . htmlspecialchars($mat['price']) . "</td>
            <td align=\"right\" style=\"border: 1px solid #ddd; text-align: right;\">" . htmlspecialchars($mat['subtotal']) . "</td>
        </tr>";
    }
} else {
    $html .= "<tr><td colspan='4' align=\"center\" style=\"border: 1px solid #ddd; text-align:center;\">No materials used.</td></tr>";
}
$html .= "</tbody></table>

    <div class='section-title' style=\"font-size: 14px; font-weight: bold; margin-bottom: 8px; border-bottom: 1px solid #ddd; padding-bottom: 4px;\">Rejects / Wastage</div>
    <table width=\"100%\" border=\"1\" cellpadding=\"6\" cellspacing=\"0\" style=\"border-collapse: collapse; margin-bottom: 20px; border: 1px solid #ddd; font-family: Arial, sans-serif; font-size: 12px;\">
        <thead>
            <tr>
                <th style=\"background-color: #f5f5f5; text-align: left; font-weight: bold;\">Material Name</th>
                <th style=\"background-color: #f5f5f5; text-align: right; font-weight: bold;\">Qty</th>
                <th style=\"background-color: #f5f5f5; text-align: right; font-weight: bold;\">Price</th>
                <th style=\"background-color: #f5f5f5; text-align: right; font-weight: bold;\">Subtotal</th>
            </tr>
        </thead>
        <tbody>";
if (!empty($data['rejects'])) {
    foreach ($data['rejects'] as $rej) {
        $html .= "<tr>
            <td style=\"border: 1px solid #ddd;\">" . htmlspecialchars($rej['name']) . "</td>
            <td align=\"right\" style=\"border: 1px solid #ddd; text-align: right;\">" . htmlspecialchars($rej['qty']) . "</td>
            <td align=\"right\" style=\"border: 1px solid #ddd; text-align: right;\">" . htmlspecialchars($rej['price']) . "</td>
            <td align=\"right\" style=\"border: 1px solid #ddd; text-align: right;\">" . htmlspecialchars($rej['subtotal']) . "</td>
        </tr>";
    }
} else {
    $html .= "<tr><td colspan='4' align=\"center\" style=\"border: 1px solid #ddd; text-align:center;\">No rejects recorded.</td></tr>";
}
$html .= "</tbody></table>

    <div class='section-title' style=\"font-size: 14px; font-weight: bold; margin-bottom: 8px; border-bottom: 1px solid #ddd; padding-bottom: 4px;\">Total Sales (Items Produced)</div>
    <table width=\"100%\" border=\"1\" cellpadding=\"6\" cellspacing=\"0\" style=\"border-collapse: collapse; margin-bottom: 20px; border: 1px solid #ddd; font-family: Arial, sans-serif; font-size: 12px;\">
        <thead>
            <tr>
                <th style=\"background-color: #f5f5f5; text-align: left; font-weight: bold;\">Item Name</th>
                <th style=\"background-color: #f5f5f5; text-align: right; font-weight: bold;\">Qty</th>
                <th style=\"background-color: #f5f5f5; text-align: right; font-weight: bold;\">Unit Price</th>
                <th style=\"background-color: #f5f5f5; text-align: right; font-weight: bold;\">Total</th>
            </tr>
        </thead>
        <tbody>";
if (!empty($data['sales'])) {
    foreach ($data['sales'] as $sale) {
        $html .= "<tr>
            <td style=\"border: 1px solid #ddd;\">" . htmlspecialchars($sale['name']) . "</td>
            <td align=\"right\" style=\"border: 1px solid #ddd; text-align: right;\">" . htmlspecialchars($sale['qty']) . "</td>
            <td align=\"right\" style=\"border: 1px solid #ddd; text-align: right;\">" . htmlspecialchars($sale['price']) . "</td>
            <td align=\"right\" style=\"border: 1px solid #ddd; text-align: right;\">" . htmlspecialchars($sale['total']) . "</td>
        </tr>";
    }
} else {
    $html .= "<tr><td colspan='4' align=\"center\" style=\"border: 1px solid #ddd; text-align:center;\">No sales items.</td></tr>";
}
$html .= "</tbody></table>

    <div class='section-title' style=\"font-size: 14px; font-weight: bold; margin-bottom: 8px; border-bottom: 1px solid #ddd; padding-bottom: 4px;\">Overhead Cost</div>
    <table width=\"100%\" border=\"1\" cellpadding=\"6\" cellspacing=\"0\" style=\"border-collapse: collapse; margin-bottom: 20px; border: 1px solid #ddd; font-family: Arial, sans-serif; font-size: 12px;\">
        <thead>
            <tr>
                <th style=\"background-color: #f5f5f5; text-align: left; font-weight: bold;\">Item Name</th>
                <th style=\"background-color: #f5f5f5; text-align: right; font-weight: bold;\">Qty</th>
                <th style=\"background-color: #f5f5f5; text-align: right; font-weight: bold;\">Cost</th>
                <th style=\"background-color: #f5f5f5; text-align: right; font-weight: bold;\">Pct (%)</th>
                <th style=\"background-color: #f5f5f5; text-align: right; font-weight: bold;\">Total</th>
            </tr>
        </thead>
        <tbody>";
if (!empty($data['overhead'])) {
    foreach ($data['overhead'] as $ov) {
        $html .= "<tr>
            <td style=\"border: 1px solid #ddd;\">" . htmlspecialchars($ov['item']) . "</td>
            <td align=\"right\" style=\"border: 1px solid #ddd; text-align: right;\">" . htmlspecialchars($ov['qty']) . "</td>
            <td align=\"right\" style=\"border: 1px solid #ddd; text-align: right;\">" . htmlspecialchars($ov['cost']) . "</td>
            <td align=\"right\" style=\"border: 1px solid #ddd; text-align: right;\">" . htmlspecialchars($ov['percentage']) . "</td>
            <td align=\"right\" style=\"border: 1px solid #ddd; text-align: right;\">" . htmlspecialchars($ov['total']) . "</td>
        </tr>";
    }
} else {
    $html .= "<tr><td colspan='5' align=\"center\" style=\"border: 1px solid #ddd; text-align:center;\">No overhead costs.</td></tr>";
}
$html .= "</tbody></table>

    <div class='section-title' style=\"font-size: 14px; font-weight: bold; margin-bottom: 8px; border-bottom: 1px solid #ddd; padding-bottom: 4px;\">Summary</div>
    <table width=\"100%\" border=\"1\" cellpadding=\"6\" cellspacing=\"0\" style=\"border-collapse: collapse; margin-bottom: 20px; border: 1px solid #ddd; font-family: Arial, sans-serif; font-size: 12px;\">
        <thead>
            <tr>
                <th style=\"background-color: #f5f5f5; text-align: left; font-weight: bold;\">Description</th>
                <th style=\"background-color: #f5f5f5; text-align: right; font-weight: bold;\">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr><td style=\"border: 1px solid #ddd; font-weight: bold;\">Material Cost</td><td align=\"right\" style=\"border: 1px solid #ddd; text-align: right;\">" . htmlspecialchars(str_replace('₱', 'Php ', $data['total_materials'] ?? '0.00')) . "</td></tr>
            <tr><td style=\"border: 1px solid #ddd; font-weight: bold;\">Reject Cost</td><td align=\"right\" style=\"border: 1px solid #ddd; text-align: right;\">" . htmlspecialchars(str_replace('₱', 'Php ', $data['total_rejects'] ?? '0.00')) . "</td></tr>
            <tr><td style=\"border: 1px solid #ddd; font-weight: bold;\">Overhead Cost</td><td align=\"right\" style=\"border: 1px solid #ddd; text-align: right;\">" . htmlspecialchars(str_replace('₱', 'Php ', $data['total_overhead'] ?? '0.00')) . "</td></tr>
            <tr><td style=\"border: 1px solid #ddd; font-weight: bold;\">Total Sales</td><td align=\"right\" style=\"border: 1px solid #ddd; text-align: right;\">" . htmlspecialchars(str_replace('₱', 'Php ', $data['total_sales'] ?? '0.00')) . "</td></tr>
            <tr><td style=\"border: 1px solid #ddd; font-weight: bold;\">Total Cost</td><td align=\"right\" style=\"border: 1px solid #ddd; text-align: right;\">" . htmlspecialchars(str_replace('₱', 'Php ', $data['total_cost'] ?? '0.00')) . "</td></tr>
            <tr style=\"background-color: #f5f5f5;\"><td style=\"border: 1px solid #ddd; font-weight: bold;\">Profit</td><td align=\"right\" style=\"border: 1px solid #ddd; text-align: right; font-weight: bold;\">" . htmlspecialchars(str_replace('₱', 'Php ', $data['profit'] ?? '0.00')) . "</td></tr>
        </tbody>
    </table>
";

$remarks = trim($data['remarks'] ?? '');
if (!empty($remarks) && strtolower($remarks) !== 'none') {
    $html .= "
    <div style=\"font-size: 14px; font-weight: bold; margin-bottom: 8px; border-bottom: 1px solid #ddd; padding-bottom: 4px; font-family: Arial, sans-serif;\">Remarks</div>
    <div style=\"padding: 10px; background-color: #f9f9f9; border: 1px solid #ddd; min-height: 50px; margin-bottom: 20px; font-family: Arial, sans-serif; font-size: 12px;\">
        " . nl2br(htmlspecialchars($remarks)) . "
    </div>";
}

$html .= "
    <table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" style=\"margin-top: 40px; text-align: center; font-family: Arial, sans-serif; font-size: 12px;\">
        <tr>
            <td width=\"33%\" align=\"center\">
                <div style=\"border-bottom: 1px solid #000; margin-bottom: 5px; height: 30px; line-height: 30px; font-style: italic;\">" . htmlspecialchars($data['created_by'] ?? '') . "</div>
                Created By
            </td>
            <td width=\"33%\" align=\"center\">
                <div style=\"border-bottom: 1px solid #000; margin-bottom: 5px; height: 30px; line-height: 30px; font-style: italic;\">" . htmlspecialchars($data['audited_by'] ?? '') . "</div>
                Audited By
            </td>
            <td width=\"33%\" align=\"center\">
                <div style=\"border-bottom: 1px solid #000; margin-bottom: 5px; height: 30px; line-height: 30px; font-style: italic;\">" . htmlspecialchars($data['acknowledged_by'] ?? '') . "</div>
                Acknowledged By
            </td>
        </tr>
    </table>

</body>
</html>
";

$filename = ($data['audit_name'] ? preg_replace('/[^a-zA-Z0-9_\-]/', '_', $data['audit_name']) : 'Inventory_Audit');

if ($format === 'word') {
    header("Content-type: application/vnd.ms-word");
    header("Content-Disposition: attachment;Filename={$filename}.doc");
    echo $html;
    exit;
} else {
    // Generate PDF
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    
    $dompdf->stream("{$filename}.pdf", array("Attachment" => true));
}
