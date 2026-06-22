<?php

/**
 * Payment Methods Management API
 * Handles: Get, Add, Update, Delete payment methods
 */

header('Content-Type: application/json');
require_once '../../../connect/config.php';

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    getPaymentMethods();
} elseif ($method === 'POST') {
    handlePostRequest();
} elseif ($method === 'PUT') {
    handlePutRequest();
} elseif ($method === 'DELETE') {
    handleDeleteRequest();
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}

/**
 * Get all payment methods
 */
function getPaymentMethods()
{
    try {
        $pdo = getDBConnection();
        $query = "SELECT * FROM payment_methods ORDER BY sort_order ASC";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $methods = $stmt->fetchAll();

        echo json_encode([
            'success' => true,
            'data' => $methods
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error fetching payment methods: ' . $e->getMessage()
        ]);
    }
}

/**
 * Handle POST requests - Add new payment method
 */
function handlePostRequest()
{
    try {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['method_name']) || !isset($input['method_value'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Missing required fields: method_name and method_value'
            ]);
            return;
        }

        $method_name = trim($input['method_name']);
        $method_value = trim($input['method_value']);
        $icon_class = isset($input['icon_class']) ? trim($input['icon_class']) : 'fa-solid fa-credit-card';

        if (strlen($method_name) === 0 || strlen($method_value) === 0) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Method name and value cannot be empty'
            ]);
            return;
        }

        $pdo = getDBConnection();

        // Check if method already exists
        $checkQuery = "SELECT id FROM payment_methods WHERE method_value = ?";
        $checkStmt = $pdo->prepare($checkQuery);
        $checkStmt->execute([$method_value]);

        if ($checkStmt->rowCount() > 0) {
            http_response_code(409);
            echo json_encode([
                'success' => false,
                'message' => 'Payment method already exists'
            ]);
            return;
        }

        // Get next sort order
        $sortQuery = "SELECT MAX(sort_order) as max_order FROM payment_methods";
        $sortStmt = $pdo->prepare($sortQuery);
        $sortStmt->execute();
        $result = $sortStmt->fetch();
        $next_sort_order = ($result['max_order'] ?? 0) + 1;

        // Insert new payment method
        $insertQuery = "INSERT INTO payment_methods (method_name, method_value, icon_class, sort_order, is_active, created_at) VALUES (?, ?, ?, ?, 1, NOW())";
        $insertStmt = $pdo->prepare($insertQuery);
        $insertStmt->execute([$method_name, $method_value, $icon_class, $next_sort_order]);

        echo json_encode([
            'success' => true,
            'message' => 'Payment method added successfully',
            'data' => [
                'id' => $pdo->lastInsertId(),
                'method_name' => $method_name,
                'method_value' => $method_value,
                'icon_class' => $icon_class,
                'sort_order' => $next_sort_order,
                'is_active' => 1
            ]
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error adding payment method: ' . $e->getMessage()
        ]);
    }
}

/**
 * Handle PUT requests - Update payment method
 */
function handlePutRequest()
{
    try {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Missing id field'
            ]);
            return;
        }

        $id = (int)$input['id'];
        $pdo = getDBConnection();

        $updates = [];
        $params = [];

        if (isset($input['method_name'])) {
            $updates[] = "method_name = ?";
            $params[] = trim($input['method_name']);
        }
        if (isset($input['is_active'])) {
            $updates[] = "is_active = ?";
            $params[] = (int)$input['is_active'];
        }
        if (isset($input['icon_class'])) {
            $updates[] = "icon_class = ?";
            $params[] = trim($input['icon_class']);
        }

        if (empty($updates)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'No fields to update'
            ]);
            return;
        }

        $params[] = $id;
        $updateQuery = "UPDATE payment_methods SET " . implode(", ", $updates) . " WHERE id = ?";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->execute($params);

        echo json_encode([
            'success' => true,
            'message' => 'Payment method updated successfully'
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error updating payment method: ' . $e->getMessage()
        ]);
    }
}

/**
 * Handle DELETE requests - Delete payment method
 */
function handleDeleteRequest()
{
    try {
        parse_str(file_get_contents('php://input'), $input);

        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Missing id field'
            ]);
            return;
        }

        $id = (int)$input['id'];
        $pdo = getDBConnection();

        // Check if this is a system payment method (built-in)
        $checkQuery = "SELECT is_system FROM payment_methods WHERE id = ?";
        $checkStmt = $pdo->prepare($checkQuery);
        $checkStmt->execute([$id]);
        $method = $checkStmt->fetch();

        if (!$method) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Payment method not found'
            ]);
            return;
        }

        if ($method['is_system']) {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'message' => 'Cannot delete system payment methods'
            ]);
            return;
        }

        // Delete the payment method
        $deleteQuery = "DELETE FROM payment_methods WHERE id = ?";
        $deleteStmt = $pdo->prepare($deleteQuery);
        $deleteStmt->execute([$id]);

        echo json_encode([
            'success' => true,
            'message' => 'Payment method deleted successfully'
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error deleting payment method: ' . $e->getMessage()
        ]);
    }
}
