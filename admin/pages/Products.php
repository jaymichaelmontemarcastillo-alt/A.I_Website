<?php
session_start();
require_once '../../connect/config.php';


// Fetch products
$search = $_GET['search'] ?? '';

if ($search) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE ? OR category LIKE ? ORDER BY created_at DESC");
    $stmt->execute(["%$search%", "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
}

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
include '../includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Products</title>
    <link rel="stylesheet" href="../../assets/css/products.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>

<body>

    <div class="admin-wrapper">
        <?php
        $current_page = 'Products';
        include 'admin_sidebar.php';
        ?>

        <main class="main-content">
            <header class="top-nav">
                <button id="toggle-btn">
                    <i class="fa-solid fa-chevron-left toggle-arrow"></i>
                </button>
            </header>

            <section class="content-body">
                <div class="products-header">
                    <h1 class="page-title">Products</h1>
                    <p class="product-count"><?php echo count($products); ?> products</p>
                    <button class="add-btn" onclick="openModal()">+ Add Product</button>
                </div>

                <form method="get" class="search-form">
                    <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
                </form>

                <div class="products-grid">
                    <?php foreach ($products as $product): ?>
                        <div class="product-card" onclick='openEditModal(<?php echo json_encode($product); ?>)'>
                            <div class="product-image">
                                <img src="../../<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image">
                            </div>
                            <div class="product-info">
                                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                <span class="category"><?php echo htmlspecialchars($product['category']); ?></span>
                                <div class="card-footer">
                                    <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
                                    <span class="stock"><?php echo $product['stock'] ?? 0; ?> in stock</span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </main>
    </div>

    <!-- MODAL -->
    <div id="productModal" class="modal">
        <div class="modal-content modal-lg">
            <form id="productForm">
                <div class="modal-header">
                    <span id="modalTitle">Add Product</span>
                    <span class="close" onclick="closeModal()">&times;</span>
                </div>

                <div class="modal-body split">
                    <!-- LEFT: IMAGE -->
                    <div class="modal-left">
                        <div class="image-preview-box">
                            <img id="previewImg" src="https://via.placeholder.com/300">
                        </div>
                        <label class="upload-btn">
                            Upload Image
                            <input type="file" id="productImage" name="image" hidden>
                        </label>
                    </div>

                    <!-- RIGHT: INPUTS -->
                    <div class="modal-right">
                        <input type="hidden" id="productId" name="id">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" id="productName" name="name">
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <input type="text" id="productCategory" name="category">
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" id="productPrice" name="price" step="0.01">
                        </div>
                        <div class="form-group">
                            <label>Stock</label>
                            <input type="number" id="productStock" name="stock">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="cancel-btn" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="save-btn">Save Product</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../../assets/js/admin-site-functions/admin_products.js"></script>
    <script src="../../assets/js/admin-site-functions/admin_sidebar.js"></script>
</body>

</html>