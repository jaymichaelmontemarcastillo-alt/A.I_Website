<?php
session_start();
require_once __DIR__ . '/../../../connect/config.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

if (ob_get_level()) ob_end_clean();
ob_start();

try {
    $pdo = getDBConnection();

    // Get all products with their types and materials
    $stmt = $pdo->query("
        SELECT 
            pt.name AS product_type,
            p.name AS product_name,
            p.sku,
            p.price,
            p.stock,
            p.unit,
            p.material_type,
            p.description,
            p.status,
            GROUP_CONCAT(
                CONCAT(m.material_name, ' (', COALESCE(pm.quantity, 0), ')')
                SEPARATOR '; '
            ) AS materials
        FROM products p
        LEFT JOIN product_types pt ON pt.id = p.product_type_id
        LEFT JOIN product_materials pm ON pm.product_id = p.id
        LEFT JOIN materials m ON m.id = pm.material_id
        GROUP BY p.id
        ORDER BY pt.name, p.name
    ");

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set headers
    $headers = [
        'Product Type',
        'Product Name',
        'SKU',
        'Price (₱)',
        'Stock',
        'Unit',
        'Material Type',
        'Description',
        'Status',
        'Materials Used'
    ];

    // Style headers
    $headerStyle = [
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2C3E50']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
    ];

    foreach (range('A', 'J') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Write headers
    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col . '1', $header);
        $sheet->getStyle($col . '1')->applyFromArray($headerStyle);
        $col++;
    }

    // Write data
    $row = 2;
    foreach ($products as $product) {
        $sheet->setCellValue('A' . $row, $product['product_type'] ?? 'Uncategorized');
        $sheet->setCellValue('B' . $row, $product['product_name']);
        $sheet->setCellValue('C' . $row, $product['sku'] ?? '');
        $sheet->setCellValue('D' . $row, $product['price'] ?? 0);
        $sheet->setCellValue('E' . $row, $product['stock'] ?? 0);
        $sheet->setCellValue('F' . $row, $product['unit'] ?? 'piece');
        $sheet->setCellValue('G' . $row, $product['material_type'] ?? 'assembled_product');
        $sheet->setCellValue('H' . $row, $product['description'] ?? '');
        $sheet->setCellValue('I' . $row, $product['status'] ?? 'active');
        $sheet->setCellValue('J' . $row, $product['materials'] ?? '');
        $row++;
    }

    // Apply borders to data
    $styleArray = [
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
    ];
    $sheet->getStyle('A2:J' . ($row - 1))->applyFromArray($styleArray);

    // Clear any output buffer
    if (ob_get_level()) ob_end_clean();

    // Send headers ONLY after all processing is complete
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="products_export_' . date('Y-m-d_H-i-s') . '.xlsx"');
    header('Cache-Control: max-age=0, must-revalidate');
    header('Pragma: public');

    // Create writer and output
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;

} catch (Exception $e) {
    if (ob_get_level()) ob_end_clean();
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Export failed: ' . $e->getMessage()
    ]);
    exit;
}
