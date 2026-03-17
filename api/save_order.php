<?php
session_start();
require_once '../connect/config.php'; // Adjust path as needed
header('Content-Type: application/json');

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['success' => false, 'error' => 'Invalid input data']);
    exit;
}

// Validate required fields
if (empty($input['items'])) {
    echo json_encode(['success' => false, 'error' => 'No items in order']);
    exit;
}

if (empty($input['customerName']) || empty($input['customerEmail']) || empty($input['customerPhone'])) {
    echo json_encode(['success' => false, 'error' => 'Customer information is required']);
    exit;
}

try {
    // Start transaction
    $pdo->beginTransaction();
    
    // Generate unique order number
    $orderNumber = 'ORD-' . date('Ymd') . '-' . rand(1000, 9999);
    
    // Insert order
    $stmt = $pdo->prepare("
        INSERT INTO orders (
            order_number, 
            customer_name, 
            customer_email, 
            customer_phone, 
            total_amount, 
            payment_method, 
            payment_status, 
            order_status, 
            created_at
        ) VALUES (?, ?, ?, ?, ?, ?, 'pending', 'pending', NOW())
    ");
    
    $stmt->execute([
        $orderNumber,
        $input['customerName'],
        $input['customerEmail'],
        $input['customerPhone'],
        $input['total'],
        $input['paymentMethod']
    ]);
    
    $orderId = $pdo->lastInsertId();
    
    // Insert order items
    $stmt = $pdo->prepare("
        INSERT INTO order_items (
            order_id, 
            product_id, 
            product_name, 
            quantity, 
            price
        ) VALUES (?, ?, ?, ?, ?)
    ");
    
    foreach ($input['items'] as $item) {
        $stmt->execute([
            $orderId,
            $item['id'],
            $item['name'],
            $item['quantity'],
            $item['price']
        ]);
        
        // Update product stock (optional)
        $updateStock = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?");
        $updateStock->execute([$item['quantity'], $item['id'], $item['quantity']]);
    }
    
    // Commit transaction
    $pdo->commit();
    
    // Clear the cart
    $_SESSION['cart'] = [];
    
    // Send email receipt (optional - implement your email function)
    // sendOrderReceipt($input['customerEmail'], $orderNumber, $input);
    
    echo json_encode([
        'success' => true,
        'order_id' => $orderId,
        'order_number' => $orderNumber,
        'message' => 'Order saved successfully'
    ]);
    
} catch (PDOException $e) {
    // Rollback transaction on error
    $pdo->rollBack();
    
    echo json_encode([
        'success' => false, 
        'error' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    $pdo->rollBack();
    
    echo json_encode([
        'success' => false, 
        'error' => 'Error: ' . $e->getMessage()
    ]);
}
?>