<?php
session_start();
require_once '../../connect/config.php';
$pdo = getDBConnection();

// Get search query
$search = $_GET['search'] ?? '';

try {
    if ($search) {
        $stmt = $pdo->prepare(
            "SELECT * FROM products 
             WHERE LOWER(name) LIKE LOWER(?) OR LOWER(category) LIKE LOWER(?) 
             ORDER BY created_at DESC"
        );
        $stmt->execute(["%$search%", "%$search%"]);
    } else {
        $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
    }
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching products: " . $e->getMessage());
}
include '../includes/header.php';

// Get all categories for the select dropdown
$categories = [];
try {
    $stmt = $pdo->query("SELECT DISTINCT category FROM products WHERE category IS NOT NULL AND category != '' ORDER BY category");
    $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    // Silently fail
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Management - Admin</title>

    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../assets/css/admin-site/products.css">
</head>

<body>
    <div class="admin-wrapper">
        <?php $current_page = 'Products';
        include 'admin_sidebar.php'; ?>

        <main class="main-content">
            <?php include 'admin_page_header.php'; ?>

            <section class="content-body">

                <!-- Header Section -->
                <div class="products-header">
                    <div class="header-left">
                        <h1 class="page-title">Products</h1>
                        <p class="product-count"><?= count($products); ?> product<?= count($products) !== 1 ? 's' : ''; ?></p>
                    </div>

                </div>
                <div class="inputs-options">
                    <form method="get" class="search-form">
                        <input type="text" name="search" placeholder="Search by name or category..."
                            value="<?= htmlspecialchars($search); ?>" autocomplete="off">
                        <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>

                    </form>
                    <button class="add-btn" type="button" onclick="openProductModal()">
                        <i class="fa-solid fa-plus"></i> Add Product
                    </button>
                </div>
                <!-- Search Form -->

                <!-- Products Table -->
                <div class="table-container">
                    <table class="product-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="productTableBody">
                            <?php if (count($products) > 0): ?>
                                <?php foreach ($products as $product): ?>
                                    <tr data-product-id="<?= $product['id']; ?>">
                                        <td><img src="../../<?= htmlspecialchars($product['image']); ?>" class="table-img"
                                                alt="<?= htmlspecialchars($product['name']); ?>"></td>
                                        <td><?= htmlspecialchars($product['name']); ?></td>
                                        <td><span class="category"><?= htmlspecialchars($product['category']); ?></span></td>
                                        <td><span class="price">₱<?= number_format($product['price'], 2); ?></span></td>
                                        <td><span class="stock"><?= (int)$product['stock']; ?></span></td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="action-btn edit" type="button" title="Edit product"
                                                    onclick='openProductEditModal(<?= json_encode($product); ?>)'>
                                                    <i class="fa-solid fa-pen"></i> Edit
                                                </button>
                                                <button class="action-btn delete" type="button" title="Delete product" data-product-id="<?= $product['id']; ?>">
                                                    <i class="fa-solid fa-trash"></i> Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="empty-row">
                                        <div class="empty-state">
                                            <i class="fa-regular fa-box-open fa-2x"></i>
                                            <p>No products found</p>
                                            <p>
                                                <?= $search ? 'Try a different search. <a href="?" class="clear-search">Clear search</a>' : 'Click "Add Product" to create your first product.' ?>
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <!-- Notification Container -->
    <div id="notification" class="notification"></div>

    <!-- Add/Edit Product Modal -->
    <div id="productModal" class="product-modal">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h2 id="modalTitle"><i class="fa-solid fa-box"></i> Add Product</h2>
                <button class="close" type="button" onclick="closeProductModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <form id="productForm" enctype="multipart/form-data" novalidate>
                <div class="modal-body split">
                    <!-- Left: Image Upload -->
                    <div class="modal-left">
                        <label for="imagePreviewBox" class="image-label">
                            <div class="image-preview-box" id="imagePreviewBox">
                                <img id="previewImg"
                                    src="https://via.placeholder.com/300?text=No+Image"
                                    alt="Product preview">
                            </div>
                        </label>
                        <label class="upload-btn" for="productImage"><i class="fa-solid fa-upload"></i> Upload Image</label>
                        <input type="file" id="productImage" name="image"
                            accept="image/jpeg,image/png,image/webp,image/avif" hidden>
                        <p class="help-text">
                            📁 JPEG, PNG, WEBP, AVIF<br>
                            💾 Max 5MB
                        </p>
                    </div>

                    <!-- Right: Product Fields -->
                    <div class="modal-right">
                        <input type="hidden" id="productId" name="id">
                        <div class="form-group">
                            <label for="productName">Product Name *</label>
                            <input type="text" id="productName" name="name" required minlength="3"
                                maxlength="255" placeholder="Enter product name">
                            <span class="form-error" id="nameError"></span>
                        </div>

                        <div class="form-group">
                            <label for="productCategory">Category *</label>
                            <input type="text" id="productCategory" name="category" required minlength="2"
                                maxlength="100" list="categoryList" placeholder="Enter or select category">
                            <datalist id="categoryList">
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= htmlspecialchars($cat); ?>"></option>
                                <?php endforeach; ?>
                            </datalist>
                            <span class="form-error" id="categoryError"></span>
                        </div>

                        <div class="form-group">
                            <label for="productPrice">Price *</label>
                            <input type="number" id="productPrice" name="price" step="0.01" min="0"
                                required placeholder="0.00">
                            <span class="form-error" id="priceError"></span>
                        </div>

                        <div class="form-group">
                            <label for="productStock">Stock Quantity *</label>
                            <input type="number" id="productStock" name="stock" min="0" required placeholder="0">
                            <span class="form-error" id="stockError"></span>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn cancel-btn" onclick="closeProductModal()">Cancel</button>
                    <button type="submit" class="btn save-btn" id="submitBtn"><i
                            class="fa-solid fa-floppy-disk"></i> Save Product</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirmModal" class="product-delete-modal">
        <div class="modal-content modal-sm">
            <div class="modal-header">
                <h2><i class="fa-solid fa-triangle-exclamation" style="color: #dc3545;"></i> Delete Product</h2>
                <button class="close" type="button" onclick="closeProductDeleteModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <p id="deleteMessage" style="margin: 20px 0; font-size: 15px; color: #555;">Are you sure you want to delete this product?</p>
                <p style="margin: 10px 0; font-size: 13px; color: #999;"><i class="fa-solid fa-exclamation-circle"></i> This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn cancel-btn" onclick="closeProductDeleteModal()">Cancel</button>
                <button type="button" class="btn delete-confirm-btn" id="deleteConfirmBtn" style="background: #dc3545; color: white;">Delete Product</button>
            </div>
        </div>
    </div>

    <script src="../../assets/js/admin-site-functions/admin_products.js"></script>
</body>

</html>