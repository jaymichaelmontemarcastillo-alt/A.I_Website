<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../../../connect/config.php';

try {
    $pdo = getDBConnection();

    $page = (int)($_GET['page'] ?? 1);
    $limit = (int)($_GET['limit'] ?? 12);
    $search = trim($_GET['search'] ?? '');
    $categoryId = (int)($_GET['category_id'] ?? 0);
    $productTypeId = (int)($_GET['product_type_id'] ?? 0);
    $stockStatus = trim($_GET['stock_status'] ?? '');

    if ($page < 1) $page = 1;
    if ($limit < 1 || $limit > 100) $limit = 12;

    $offset = ($page - 1) * $limit;

    // Build WHERE clause
    $where = [];
    $params = [];

    if ($search) {
        $where[] = "(p.name LIKE ? OR p.sku LIKE ? OR p.description LIKE ?)";
        $params[] = "%{$search}%";
        $params[] = "%{$search}%";
        $params[] = "%{$search}%";
    }

    if ($categoryId > 0) {
        $where[] = "p.category_id = ?";
        $params[] = $categoryId;
    }

    if ($productTypeId > 0) {
        $where[] = "p.product_type_id = ?";
        $params[] = $productTypeId;
    }

    $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

    // Get total count
    $countSql = "SELECT COUNT(*) as total FROM products p {$whereClause}";
    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute($params);
    $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Get products with category and product type names
    $sql = "SELECT 
                p.*,
                c.name as category_name,
                pt.name as product_type_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN product_types pt ON p.product_type_id = pt.id
            {$whereClause} 
            ORDER BY p.created_at DESC 
            LIMIT ? OFFSET ?";

    $stmt = $pdo->prepare($sql);
    array_push($params, $limit, $offset);
    $stmt->execute($params);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate stock status for each product
    foreach ($products as &$product) {
        $stock = (int)($product['stock'] ?? 0);
        if ($stock <= 0) {
            $product['stock_status'] = 'out_of_stock';
        } elseif ($stock <= 10) {
            $product['stock_status'] = 'low_stock';
        } else {
            $product['stock_status'] = 'in_stock';
        }
        // Add display fields
        $product['category'] = $product['category_name'] ?? '';
        $product['product_type'] = $product['product_type_name'] ?? '';
    }

    // Apply stock status filter if needed
    if ($stockStatus) {
        $products = array_filter($products, function ($product) use ($stockStatus) {
            return $product['stock_status'] === $stockStatus;
        });
        $products = array_values($products);
    }

    // Get categories for filter dropdown
    $catStmt = $pdo->query("SELECT id, name FROM categories WHERE status = 'active' ORDER BY name");
    $categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

    // Get product types for filter dropdown
    $typeStmt = $pdo->query("SELECT id, name FROM product_types WHERE status = 'active' ORDER BY name");
    $productTypes = $typeStmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $products,
        'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'total' => (int)$total,
            'pages' => ceil($total / $limit)
        ],
        'filters' => [
            'categories' => $categories,
            'product_types' => $productTypes
        ]
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
