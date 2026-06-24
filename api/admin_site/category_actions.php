<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../../connect/config.php';

try {
    $pdo    = getDBConnection();
    $action = $_GET['action'] ?? $_POST['action'] ?? null;

    if (!$action) throw new Exception('No action specified');

    // ── GET ALL CATEGORIES ────────────────────────────────────────────────
    if ($action === 'getcategories') {
        $page   = max(1, (int)($_GET['page']  ?? 1));
        $limit  = (int)($_GET['limit']         ?? 10);
        $search = trim($_GET['search']         ?? '');
        $status = trim($_GET['status']         ?? '');

        if ($limit < 1 || $limit > 100) $limit = 10;
        $offset = ($page - 1) * $limit;

        $where  = [];
        $params = [];

        if ($search) {
            $where[]  = "(c.name LIKE ? OR c.description LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        if ($status && in_array($status, ['active', 'inactive'])) {
            $where[]  = "c.status = ?";
            $params[] = $status;
        }

        $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM categories c $whereClause");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        // product_count uses category_id FK — no string matching
        $sql = "
            SELECT
                c.id,
                c.name,
                c.slug,
                c.description,
                c.status,
                c.created_at,
                COUNT(p.id) AS product_count
            FROM categories c
            LEFT JOIN products p ON p.category_id = c.id
            $whereClause
            GROUP BY c.id, c.name, c.slug, c.description, c.status, c.created_at
            ORDER BY c.created_at DESC
            LIMIT ? OFFSET ?
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([...$params, $limit, $offset]);
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'data'    => $categories,
            'pagination' => [
                'page'  => $page,
                'limit' => $limit,
                'total' => $total,
                'pages' => (int)ceil($total / $limit),
            ],
        ]);
        exit;
    }

    // ── GET SINGLE CATEGORY ───────────────────────────────────────────────
    if ($action === 'getcategory') {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) throw new Exception('Invalid category ID');

        $stmt = $pdo->prepare("
            SELECT
                c.id, c.name, c.slug, c.description, c.status,
                c.created_at, c.updated_at,
                COUNT(p.id) AS product_count
            FROM categories c
            LEFT JOIN products p ON p.category_id = c.id
            WHERE c.id = ?
            GROUP BY c.id, c.name, c.slug, c.description, c.status, c.created_at, c.updated_at
        ");
        $stmt->execute([$id]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$category) throw new Exception('Category not found');

        echo json_encode(['success' => true, 'data' => $category]);
        exit;
    }

    // ── CREATE CATEGORY ───────────────────────────────────────────────────
    if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $name        = trim($_POST['name']        ?? '');
        $description = trim($_POST['description'] ?? '');

        if (strlen($name) < 2 || strlen($name) > 255) {
            throw new Exception('Category name must be 2–255 characters');
        }

        $slug = strtolower(trim(preg_replace('/[^a-z0-9-]+/', '-', $name), '-'));

        try {
            $stmt = $pdo->prepare("
                INSERT INTO categories (name, slug, description, status)
                VALUES (?, ?, ?, 'active')
            ");
            $stmt->execute([$name, $slug, $description]);

            echo json_encode(['success' => true, 'message' => 'Category created successfully', 'id' => (int)$pdo->lastInsertId()]);
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                throw new Exception('Category name already exists');
            }
            throw $e;
        }
        exit;
    }

    // ── UPDATE CATEGORY ───────────────────────────────────────────────────
    if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id          = (int)($_POST['id']         ?? 0);
        $name        = trim($_POST['name']        ?? '');
        $description = trim($_POST['description'] ?? '');
        $status      = trim($_POST['status']      ?? 'active');

        if ($id <= 0) throw new Exception('Invalid category ID');
        if (strlen($name) < 2 || strlen($name) > 255) throw new Exception('Category name must be 2–255 characters');
        if (!in_array($status, ['active', 'inactive'])) throw new Exception('Invalid status');

        $check = $pdo->prepare("SELECT id FROM categories WHERE id = ?");
        $check->execute([$id]);
        if (!$check->fetch()) throw new Exception('Category not found');

        $slug = strtolower(trim(preg_replace('/[^a-z0-9-]+/', '-', $name), '-'));

        try {
            $stmt = $pdo->prepare("
                UPDATE categories SET name = ?, slug = ?, description = ?, status = ? WHERE id = ?
            ");
            $stmt->execute([$name, $slug, $description, $status, $id]);

            echo json_encode(['success' => true, 'message' => 'Category updated successfully']);
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                throw new Exception('Category name already exists');
            }
            throw $e;
        }
        exit;
    }

    // ── DELETE CATEGORY ───────────────────────────────────────────────────
    if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id    = (int)($_POST['id']    ?? 0);
        $force = (bool)($_POST['force'] ?? false);

        if ($id <= 0) throw new Exception('Invalid category ID');

        // Check existence and product count
        $stmt = $pdo->prepare("
            SELECT c.name, COUNT(p.id) AS product_count
            FROM categories c
            LEFT JOIN products p ON p.category_id = c.id
            WHERE c.id = ?
            GROUP BY c.name
        ");
        $stmt->execute([$id]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$category) throw new Exception('Category not found');

        if ($category['product_count'] > 0 && !$force) {
            echo json_encode([
                'success'              => false,
                'message'              => 'Category has products',
                'productCount'         => $category['product_count'],
                'requiresConfirmation' => true,
            ]);
            exit;
        }

        $pdo->prepare("DELETE FROM categories WHERE id = ?")->execute([$id]);

        echo json_encode(['success' => true, 'message' => 'Category deleted successfully']);
        exit;
    }

    throw new Exception('Invalid action');
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
