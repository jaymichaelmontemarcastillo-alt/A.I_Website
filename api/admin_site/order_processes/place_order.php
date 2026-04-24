<?php
// place_order.php
// Called when customer places a new order (handles COD + GCash initial creation)
header('Content-Type: application/json');
require_once '../../../connect/config.php';

$data = json_decode(file_get_contents("php://input"), true);

$customer_name   = trim($data['customer_name']   ?? '');
$customer_email  = trim($data['customer_email']  ?? '');
$customer_phone  = trim($data['customer_phone']  ?? '');
$total_amount    = floatval($data['total_amount'] ?? 0);
$payment_method  = trim($data['payment_method']  ?? '');
$items           = $data['items'] ?? [];

$allowed_methods = ['cash', 'gcash', 'card'];

// ── Validation ───────────────────────────────────────────────
if (!$customer_name || !$customer_email || !$customer_phone) {
    echo json_encode(['success' => false, 'message' => 'Customer details are required']);
    exit;
}

if (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

if (!in_array($payment_method, $allowed_methods, true)) {
    echo json_encode(['success' => false, 'message' => 'Invalid payment method']);
    exit;
}

if ($total_amount <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid total amount']);
    exit;
}

if (empty($items)) {
    echo json_encode(['success' => false, 'message' => 'No items in order']);
    exit;
}

// ── Determine initial statuses ───────────────────────────────
// COD:   payment_status = 'unpaid',   order_status = 'processing'
// GCash: payment_status = 'pending',  order_status = 'pending'
// Card:  payment_status = 'pending',  order_status = 'pending'

if ($payment_method === 'cash') {
    $payment_status = 'unpaid';   // maps to payments.payment_status 'unpaid'
    $order_status   = 'processing';
} else {
    $payment_status = 'pending';
    $order_status   = 'pending';
}

// ── Map to orders.payment_status enum ('pending','paid','failed') ──
// For COD we store 'pending' in orders until delivered
$order_payment_status = ($payment_method === 'cash') ? 'pending' : 'pending';

try {
    $pdo = getDBConnection();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Generate unique order number
    $order_number = 'ORD-' . strtoupper(bin2hex(random_bytes(4)));

    // Ensure uniqueness
    do {
        $chk = $pdo->prepare("SELECT id FROM orders WHERE order_number = ?");
        $chk->execute([$order_number]);
        if ($chk->fetch()) {
            $order_number = 'ORD-' . strtoupper(bin2hex(random_bytes(4)));
        } else {
            break;
        }
    } while (true);

    $pdo->beginTransaction();

    // Insert order
    $pdo->prepare("
        INSERT INTO orders 
            (order_number, customer_name, customer_email, customer_phone, total_amount, payment_method, payment_status, order_status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ")->execute([
        $order_number,
        $customer_name,
        $customer_email,
        $customer_phone,
        $total_amount,
        $payment_method,
        $order_payment_status,
        $order_status
    ]);

    $order_id = $pdo->lastInsertId();

    // Insert order items
    $itemStmt = $pdo->prepare("
        INSERT INTO order_items (order_id, product_name, quantity, price, subtotal)
        VALUES (?, ?, ?, ?, ?)
    ");

    foreach ($items as $item) {
        $product_name = trim($item['product_name'] ?? '');
        $quantity     = intval($item['quantity']     ?? 0);
        $price        = floatval($item['price']      ?? 0);
        $subtotal     = floatval($item['subtotal']   ?? ($price * $quantity));

        if (!$product_name || $quantity <= 0 || $price <= 0) continue;

        $itemStmt->execute([$order_id, $product_name, $quantity, $price, $subtotal]);
    }

    // Insert initial payments record
    $pdo->prepare("
        INSERT INTO payments (order_id, payment_method, payment_status)
        VALUES (?, ?, ?)
    ")->execute([$order_id, $payment_method, $payment_status]);

    $pdo->commit();

    echo json_encode([
        'success'      => true,
        'message'      => 'Order placed successfully',
        'order_number' => $order_number
    ]);
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
