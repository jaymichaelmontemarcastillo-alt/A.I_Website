<?php
session_start();
require_once '../../connect/config.php'; // make sure path is correct

// Get PDO connection
$pdo = getDBConnection();

// Get search query if any
$search = $_GET['search'] ?? '';

try {
    if ($search) {
        // Use prepared statement for search
        $stmt = $pdo->prepare(
            "SELECT * FROM products 
             WHERE LOWER(name) LIKE LOWER(?) OR LOWER(category) LIKE LOWER(?) 
             ORDER BY created_at DESC"
        );
        $stmt->execute(["%$search%", "%$search%"]);
    } else {
        // Fetch all products
        $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
    }

    $products = $stmt->fetchAll(); // fetch results as associative array

} catch (PDOException $e) {
    die("Error fetching products: " . $e->getMessage());
}

// Include header
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
            <?php
            include 'admin_page_header.php';
            ?>

            <section class="content-body">
                <div class="products-header">
                    <h1 class="page-title">Products</h1>
                    <p class="product-count"><?php echo count($products); ?> products</p>
                    <button class="add-btn" onclick="openModal()">+ Add Product</button>
                </div>

                <form method="get" class="search-form">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
                </form>

                <!-- TABLE -->
                <div class="table-container">
                    <table class="product-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr onclick='openEditModal(<?php echo json_encode($product); ?>)'>
                                    <td>
                                        <img src="../../<?php echo htmlspecialchars($product['image']); ?>" class="table-img">
                                    </td>
                                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                                    <td>
                                        <span class="category"><?php echo htmlspecialchars($product['category']); ?></span>
                                    </td>
                                    <td class="price">$<?php echo number_format($product['price'], 2); ?></td>
                                    <td>
                                        <span class="stock"><?php echo $product['stock'] ?? 0; ?></span>
                                    </td>
                                    <td>
                                        <button class="edit-btn">Edit</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
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