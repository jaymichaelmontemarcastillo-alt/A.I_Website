<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../../../connect/config.php';

try {
    $pdo = getDBConnection();

    $page = max(1, (int)($_GET['page'] ?? 1));
    $limit = (int)($_GET['limit'] ?? 12);
    $search = trim($_GET['search'] ?? '');
    $typeId = (int)($_GET['product_type_id'] ?? 0);
    $stockStatus = trim($_GET['stock_status'] ?? '');

    if ($limit < 1 || $limit > 100) $limit = 12;
    $offset = ($page - 1) * $limit;

    // Build WHERE
    $where = [];
    $params = [];

    if ($search) {
        $where[] = "(p.name LIKE ? OR p.sku LIKE ? OR p.description LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    if ($typeId > 0) {
        $where[] = "p.product_type_id = ?";
        $params[] = $typeId;
    }

    $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

    // Total count
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM products p $whereClause");
    $countStmt->execute($params);
    $total = (int)$countStmt->fetchColumn();

    // Products with type names
    $sql = "
        SELECT
            p.*,
            pt.name AS product_type_name
        FROM products p
        LEFT JOIN product_types pt ON pt.id = p.product_type_id
        $whereClause
        ORDER BY p.created_at DESC
        LIMIT ? OFFSET ?
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([...$params, $limit, $offset]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Attach materials to each product and calculate stock status
    if ($products) {
        $productIds = array_column($products, 'id');
        $placeholders = implode(',', array_fill(0, count($productIds), '?'));

        $matStmt = $pdo->prepare("
            SELECT
                pm.product_id,
                pm.quantity,
                m.id AS material_id,
                m.material_name,
                m.type AS material_type
            FROM product_materials pm
            JOIN materials m ON m.id = pm.material_id
            WHERE pm.product_id IN ($placeholders)
            ORDER BY m.type, m.material_name
        ");
        $matStmt->execute($productIds);
        $allMaterials = $matStmt->fetchAll(PDO::FETCH_ASSOC);

        // Group materials by product_id
        $materialsMap = [];
        foreach ($allMaterials as $mat) {
            $materialsMap[$mat['product_id']][] = $mat;
        }

        foreach ($products as &$product) {
            $stock = (int)($product['stock'] ?? 0);
            $product['stock_status'] = $stock <= 0 ? 'out_of_stock' : ($stock <= 10 ? 'low_stock' : 'in_stock');
            $product['materials'] = $materialsMap[$product['id']] ?? [];
        }
        unset($product);
    }

    // Apply stock status filter after fetch (computed field)
    if ($stockStatus) {
        $products = array_values(array_filter($products, fn($p) => $p['stock_status'] === $stockStatus));
        $total = count($products);
    }

    // Filter dropdowns - only product types
    $types = $pdo->query("SELECT id, name FROM product_types WHERE status = 'active' ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $products,
        'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'pages' => (int)ceil($total / $limit),
        ],
        'filters' => [
            'product_types' => $types,
        ],
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
