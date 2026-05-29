<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../../../connect/config.php';

try {
    $pdo = getDBConnection();

    $page = (int)($_GET['page'] ?? 1);
    $limit = (int)($_GET['limit'] ?? 10);
    $search = trim($_GET['search'] ?? '');
    $category = trim($_GET['category'] ?? '');
    $type = trim($_GET['type'] ?? '');
    $isProduct = isset($_GET['is_product']) ? (int)$_GET['is_product'] : null; // 0 = materials, 1 = products

    if ($page < 1) $page = 1;
    if ($limit < 1 || $limit > 100) $limit = 10;

    $offset = ($page - 1) * $limit;

    // Build WHERE clause
    $where = [];
    $params = [];

    if ($search) {
        $where[] = "(material_name LIKE ? OR type LIKE ? OR category LIKE ? OR sku LIKE ?)";
        $params[] = "%{$search}%";
        $params[] = "%{$search}%";
        $params[] = "%{$search}%";
        $params[] = "%{$search}%";
    }

    if ($category) {
        $where[] = "category = ?";
        $params[] = $category;
    }

    if ($type) {
        $where[] = "type = ?";
        $params[] = $type;
    }

    if ($isProduct !== null) {
        $where[] = "is_product = ?";
        $params[] = $isProduct;
    }

    $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

    // Get total count
    $countSql = "SELECT COUNT(*) as total FROM materials {$whereClause}";
    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute($params);
    $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Get paginated results
    $sql = "SELECT * FROM materials {$whereClause} ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $stmt = $pdo->prepare($sql);
    array_push($params, $limit, $offset);
    $stmt->execute($params);
    $materials = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate stock status
    foreach ($materials as &$material) {
        $stock = (int)$material['total_stock'];
        $threshold = (int)($material['low_stock_threshold'] ?? 5);
        if ($stock <= 0) {
            $material['stock_status'] = 'out_of_stock';
        } elseif ($stock <= $threshold) {
            $material['stock_status'] = 'low_stock';
        } else {
            $material['stock_status'] = 'in_stock';
        }
    }

    // Get unique categories and types for filters
    $catStmt = $pdo->query("SELECT DISTINCT category FROM materials WHERE category IS NOT NULL AND category != '' ORDER BY category");
    $categories = $catStmt->fetchAll(PDO::FETCH_COLUMN);

    $typeStmt = $pdo->query("SELECT DISTINCT type FROM materials WHERE type IS NOT NULL AND type != '' ORDER BY type");
    $types = $typeStmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode([
        'success' => true,
        'data' => $materials,
        'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'total' => (int)$total,
            'pages' => ceil($total / $limit)
        ],
        'filters' => [
            'categories' => $categories,
            'types' => $types
        ]
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
