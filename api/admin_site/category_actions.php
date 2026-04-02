<?php

/**
 * Category Actions API
 * Handles: List, Create, Update, Delete, Get Single
 */

header('Content-Type: application/json');

include '../../connect/config.php';

try {
    $pdo = getDBConnection();
    $action = $_GET['action'] ?? $_POST['action'] ?? null;

    if (!$action) {
        throw new Exception('No action specified');
    }

    // ============================================
    // GET ALL CATEGORIES (with pagination)
    // ============================================
    if ($action === 'getcategories') {
        $page = (int)($_GET['page'] ?? 1);
        $limit = (int)($_GET['limit'] ?? 10);
        $search = trim($_GET['search'] ?? '');
        $status = trim($_GET['status'] ?? '');

        // Validate pagination
        if ($page < 1) $page = 1;
        if ($limit < 1 || $limit > 100) $limit = 10;

        $offset = ($page - 1) * $limit;

        // Build query
        $where = [];
        $params = [];

        if ($search) {
            $where[] = "(c.name LIKE ? OR c.description LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        if ($status && in_array($status, ['active', 'inactive'])) {
            $where[] = "c.status = ?";
            $params[] = $status;
        }

        $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM categories c {$whereClause}";
        $countStmt = $pdo->prepare($countSql);
        $countStmt->execute($params);
        $total = $countStmt->fetch()['total'];

        // Get paginated results
        $sql = "
            SELECT 
                c.id,
                c.name,
                c.slug,
                c.description,
                c.status,
                c.created_at,
                COUNT(p.id) as product_count
            FROM categories c
            LEFT JOIN products p ON LOWER(CONVERT(p.category USING utf8mb4)) = LOWER(c.name)
            {$whereClause}
            GROUP BY c.id, c.name, c.slug, c.description, c.status, c.created_at
            ORDER BY c.created_at DESC
            LIMIT ? OFFSET ?
        ";

        $stmt = $pdo->prepare($sql);
        array_push($params, $limit, $offset);
        $stmt->execute($params);
        $categories = $stmt->fetchAll();

        echo json_encode([
            'success' => true,
            'data' => $categories,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => (int)$total,
                'pages' => ceil($total / $limit)
            ]
        ]);
        exit;
    }

    // ============================================
    // GET SINGLE CATEGORY
    // ============================================
    if ($action === 'getcategory') {
        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            throw new Exception('Invalid category ID');
        }

        $stmt = $pdo->prepare("
            SELECT 
                c.id,
                c.name,
                c.slug,
                c.description,
                c.status,
                c.created_at,
                c.updated_at,
                COUNT(p.id) as product_count
            FROM categories c
            LEFT JOIN products p ON LOWER(CONVERT(p.category USING utf8mb4)) = LOWER(c.name)
            WHERE c.id = ?
            GROUP BY c.id, c.name, c.slug, c.description, c.status, c.created_at, c.updated_at
        ");
        $stmt->execute([$id]);
        $category = $stmt->fetch();

        if (!$category) {
            throw new Exception('Category not found');
        }

        echo json_encode([
            'success' => true,
            'data' => $category
        ]);
        exit;
    }

    // ============================================
    // CREATE CATEGORY
    // ============================================
    if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        // Validate
        if (strlen($name) < 2 || strlen($name) > 255) {
            throw new Exception('Category name must be 2-255 characters');
        }

        // Generate slug
        $slug = strtolower(trim(preg_replace('/[^a-z0-9-]+/', '-', $name), '-'));

        try {
            $stmt = $pdo->prepare("
                INSERT INTO categories (name, slug, description, status)
                VALUES (?, ?, ?, 'active')
            ");
            $stmt->execute([$name, $slug, $description]);

            $categoryId = $pdo->lastInsertId();

            echo json_encode([
                'success' => true,
                'message' => 'Category created successfully',
                'id' => $categoryId
            ]);
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                throw new Exception('Category name already exists');
            }
            throw $e;
        }
        exit;
    }

    // ============================================
    // UPDATE CATEGORY
    // ============================================
    if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $status = trim($_POST['status'] ?? 'active');

        // Validate
        if ($id <= 0) {
            throw new Exception('Invalid category ID');
        }

        if (strlen($name) < 2 || strlen($name) > 255) {
            throw new Exception('Category name must be 2-255 characters');
        }

        if (!in_array($status, ['active', 'inactive'])) {
            throw new Exception('Invalid status');
        }

        // Check if category exists
        $checkStmt = $pdo->prepare("SELECT id FROM categories WHERE id = ?");
        $checkStmt->execute([$id]);
        if (!$checkStmt->fetch()) {
            throw new Exception('Category not found');
        }

        // Generate slug
        $slug = strtolower(trim(preg_replace('/[^a-z0-9-]+/', '-', $name), '-'));

        try {
            $stmt = $pdo->prepare("
                UPDATE categories 
                SET name = ?, slug = ?, description = ?, status = ?
                WHERE id = ?
            ");
            $stmt->execute([$name, $slug, $description, $status, $id]);

            echo json_encode([
                'success' => true,
                'message' => 'Category updated successfully'
            ]);
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                throw new Exception('Category name already exists');
            }
            throw $e;
        }
        exit;
    }

    // ============================================
    // DELETE CATEGORY
    // ============================================
    if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int)($_POST['id'] ?? 0);
        $force = (bool)($_POST['force'] ?? false);

        if ($id <= 0) {
            throw new Exception('Invalid category ID');
        }

        // Check if category exists and has products
        $stmt = $pdo->prepare("
            SELECT c.name, COUNT(p.id) as product_count
            FROM categories c
            LEFT JOIN products p ON LOWER(p.category) COLLATE utf8mb4_unicode_ci = LOWER(c.name) COLLATE utf8mb4_unicode_ci
            WHERE c.id = ?
            GROUP BY c.name
        ");
        $stmt->execute([$id]);
        $category = $stmt->fetch();

        if (!$category) {
            throw new Exception('Category not found');
        }

        if ($category['product_count'] > 0 && !$force) {
            echo json_encode([
                'success' => false,
                'message' => 'Category has products',
                'productCount' => $category['product_count'],
                'requiresConfirmation' => true
            ]);
            exit;
        }

        // Delete category
        $deleteStmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $deleteStmt->execute([$id]);

        echo json_encode([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
        exit;
    }

    // ============================================
    // GET PRODUCTS FOR CATEGORY
    // ============================================
    if ($action === 'getcategoryproducts') {
        $categoryId = (int)($_GET['category_id'] ?? 0);
        $page = (int)($_GET['page'] ?? 1);
        $limit = (int)($_GET['limit'] ?? 10);
        $search = trim($_GET['search'] ?? '');

        if ($categoryId <= 0) {
            throw new Exception('Invalid category ID');
        }

        // Validate pagination
        if ($page < 1) $page = 1;
        if ($limit < 1 || $limit > 100) $limit = 10;

        $offset = ($page - 1) * $limit;

        // Get category name
        $catStmt = $pdo->prepare("SELECT name FROM categories WHERE id = ?");
        $catStmt->execute([$categoryId]);
        $category = $catStmt->fetch();

        if (!$category) {
            throw new Exception('Category not found');
        }

        // Build query
        $where = "LOWER(CONVERT(category USING utf8mb4)) = LOWER(?)";
        $params = [$category['name']];

        if ($search) {
            $where .= " AND (name LIKE ? OR description LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM products WHERE {$where}";
        $countStmt = $pdo->prepare($countSql);
        $countStmt->execute(array_slice($params, 0, 1));
        $total = $countStmt->fetch()['total'];

        // Get products
        $sql = "
            SELECT id, name, category, price, stock, description
            FROM products
            WHERE {$where}
            ORDER BY created_at DESC
            LIMIT ? OFFSET ?
        ";

        $stmt = $pdo->prepare($sql);
        array_push($params, $limit, $offset);
        $stmt->execute($params);
        $products = $stmt->fetchAll();

        echo json_encode([
            'success' => true,
            'data' => $products,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => (int)$total,
                'pages' => ceil($total / $limit)
            ]
        ]);
        exit;
    }

    // ============================================
    // GET UNASSIGNED PRODUCTS
    // ============================================
    if ($action === 'getunassignedproducts') {
        $categoryId = (int)($_GET['category_id'] ?? 0);
        $search = trim($_GET['search'] ?? '');

        if ($categoryId <= 0) {
            throw new Exception('Invalid category ID');
        }

        // Get category name
        $catStmt = $pdo->prepare("SELECT name FROM categories WHERE id = ?");
        $catStmt->execute([$categoryId]);
        $category = $catStmt->fetch();

        if (!$category) {
            throw new Exception('Category not found');
        }

        // Build query
        $where = "LOWER(CONVERT(category USING utf8mb4)) != LOWER(?)";
        $params = [$category['name']];

        if ($search) {
            $where .= " AND name LIKE ?";
            $params[] = "%{$search}%";
        }

        $sql = "
            SELECT id, name, price, stock
            FROM products
            WHERE {$where}
            ORDER BY name
            LIMIT 100
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll();

        echo json_encode([
            'success' => true,
            'data' => $products
        ]);
        exit;
    }

    // ============================================
    // ASSIGN PRODUCT TO CATEGORY
    // ============================================
    if ($action === 'assignproduct' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $productId = (int)($_POST['product_id'] ?? 0);

        if ($categoryId <= 0 || $productId <= 0) {
            throw new Exception('Invalid category or product ID');
        }

        // Get category name
        $catStmt = $pdo->prepare("SELECT name FROM categories WHERE id = ?");
        $catStmt->execute([$categoryId]);
        $category = $catStmt->fetch();

        if (!$category) {
            throw new Exception('Category not found');
        }

        // Update product
        $stmt = $pdo->prepare("UPDATE products SET category = ? WHERE id = ?");
        $stmt->execute([$category['name'], $productId]);

        echo json_encode([
            'success' => true,
            'message' => 'Product assigned to category'
        ]);
        exit;
    }

    throw new Exception('Invalid action');
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
