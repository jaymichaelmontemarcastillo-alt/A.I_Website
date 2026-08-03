<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../../../connect/config.php';

try {
    $pdo = getDBConnection();
    
    $type = $_GET['type'] ?? 'bundle';
    $page = (int)($_GET['page'] ?? 1);
    $limit = (int)($_GET['limit'] ?? 12);
    $search = trim($_GET['search'] ?? '');
    $sort = $_GET['sort'] ?? 'sort_order';
    
    $offset = ($page - 1) * $limit;
    
    // Build query
    $where = "type = :type";
    $params = [':type' => $type];
    
    if ($search) {
        $where .= " AND (name LIKE :search OR description LIKE :search)";
        $params[':search'] = "%$search%";
    }
    
    // Sorting
    $orderBy = match($sort) {
        'name_asc' => 'name ASC',
        'name_desc' => 'name DESC',
        'newest' => 'created_at DESC',
        default => 'sort_order ASC, name ASC'
    };
    
    // Get total count
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM bundles_merchandise WHERE $where");
    $countStmt->execute($params);
    $total = $countStmt->fetchColumn();
    
    // Get items
    $stmt = $pdo->prepare("
        SELECT * FROM bundles_merchandise 
        WHERE $where 
        ORDER BY $orderBy 
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Fix image paths - add full URL
    $baseUrl = '/Anything_Inside_Website/'; // Adjust this to match your project
    foreach ($items as &$item) {
        if ($item['image_path']) {
            // If path doesn't start with http or /, add base URL
            if (strpos($item['image_path'], 'http') !== 0 && strpos($item['image_path'], '/') !== 0) {
                $item['image_path'] = $baseUrl . $item['image_path'];
            }
        }
    }
    
    echo json_encode([
        'success' => true,
        'data' => $items,
        'pagination' => [
            'total' => (int)$total,
            'page' => $page,
            'limit' => $limit,
            'pages' => ceil($total / $limit)
        ]
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>