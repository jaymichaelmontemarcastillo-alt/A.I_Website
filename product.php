<?php
session_start();
require_once 'connect/config.php'; // Use database instead of products_list.php

$pdo = getDBConnection();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$category = isset($_GET['category']) ? trim($_GET['category']) : null;

if ($id === 0) {
    $products = getProducts($pdo, $category);
    include 'includes/header.php';
?>

    <link rel="stylesheet" href="assets/css/customer-site/home.css">
    <style>
        .product-page {
            padding: 40px 4%;
            background: #f0f5fb;
            min-height: 100vh;
            max-width: 1300px;
            margin: 0 auto;
        }

        .section-title {
            margin-bottom: 18px;
        }

        .section-title h2 {
            font-size: clamp(2.2rem, 3vw, 3rem);
            margin-bottom: 10px;
        }

        .section-title p {
            max-width: 720px;
            margin: 0 auto;
            color: #546d84;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: #0f3d67;
            font-weight: 700;
            margin-bottom: 35px;
            font-size: 0.98rem;
        }

        .category-buttons {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            gap: 14px;
            margin-top: 18px;
        }

        .category-buttons button {
            padding: 12px 22px;
            border: 1px solid transparent;
            border-radius: 999px;
            background: white;
            color: #0f3d67;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.25s ease, border-color 0.25s ease, background 0.25s ease;
            box-shadow: 0 18px 48px rgba(15, 61, 103, 0.08);
        }

        .category-buttons button:hover,
        .category-buttons button.active {
            transform: translateY(-1px);
            border-color: rgba(246, 195, 74, 0.45);
            background: #fffbeb;
            color: #0f3d67;
        }

        .category-buttons .reset-btn {
            background: #0f3d67;
            color: white;
            border-color: #0f3d67;
        }

        .featured-section {
            padding-top: 28px;
        }

        .featured-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            align-items: stretch;
            margin-top: 30px;
        }

        .gift-card {
            border-radius: 28px;
            overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            border: 1px solid rgba(15, 61, 103, 0.08);
            background: white;
            display: flex;
            flex-direction: column;
        }

        .gift-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 24px 80px rgba(15, 61, 103, 0.12);
        }

        .gift-img {
            min-height: 220px;
            overflow: hidden;
            position: relative;
        }

        .gift-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.35s ease;
        }

        .gift-card:hover .gift-img img {
            transform: scale(1.04);
        }

        .gift-info {
            padding: 22px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            flex: 1;
        }

        .gift-info h4 {
            font-size: 1.1rem;
            margin: 0;
            color: #0f3d67;
        }

        .gift-info p {
            color: #5c6d7d;
            line-height: 1.75;
            margin: 0;
            min-height: 68px;
        }

        .gift-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
        }

        .gift-bottom .price {
            color: #f59e0b;
            font-size: 1.05rem;
            font-weight: 700;
        }

        .gift-card .badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: rgba(246, 195, 74, 0.95);
            color: #0f3d67;
            font-size: 0.82rem;
            padding: 8px 12px;
            z-index: 2;
            border-radius: 200px;
        }

        .gift-card h4,
        .gift-card p {
            cursor: pointer;
        }

        @media (max-width: 920px) {
            .category-buttons {
                justify-content: center;
            }
        }

        @media (max-width: 640px) {
            .category-buttons {
                justify-content: center;
            }

            .back-link {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <main class="product-page">
        <section class="category-section-prod-page">
            <div class="section-title">
                <h2>Custom Merchandise</h2>
                <p>Explore our shop's curated products and discover the perfect gift for every occasion.</p>
            </div>

            <div class="category-buttons">
                <button onclick="filterByCategory('birthday')">Birthday</button>
                <button onclick="filterByCategory('anniversary')">Anniversary</button>
                <button onclick="filterByCategory('holiday')">Holiday</button>
                <button onclick="filterByCategory('thank you')">Thank You</button>
                <button onclick="filterByCategory('baby shower')">Baby Shower</button>
                <button onclick="filterByCategory('wedding')">Wedding</button>
                <button onclick="filterByCategory('graduation')">Graduation</button>
                <button onclick="filterByCategory('valentine')">Valentine</button>
                <button onclick="filterByCategory('corporate')">Corporate</button>
                <button onclick="resetFilters()" class="reset-btn">Show All</button>
            </div>
        </section>

        <section class="featured-section">
            <div class="featured-grid" id="productsGrid">
                <?php foreach ($products as $product): ?>
                    <div class="gift-card"
                        data-name="<?= strtolower($product['name']); ?>"
                        data-category="<?= strtolower($product['category_name'] ?? $product['category'] ?? ''); ?>"
                        data-description="<?= strtolower($product['description']); ?>"
                        data-id="<?= $product['id']; ?>">

                        <div class="gift-img" onclick="viewProduct(<?= $product['id']; ?>)">
                            <span class="badge"><?= htmlspecialchars($product['category_name'] ?? $product['category'] ?? 'Uncategorized'); ?></span>
                            <img src="<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
                        </div>

                        <div class="gift-info">
                            <h4 onclick="viewProduct(<?= $product['id']; ?>)"><?= htmlspecialchars($product['name']); ?></h4>
                            <p onclick="viewProduct(<?= $product['id']; ?>)"><?= htmlspecialchars($product['description']); ?></p>

                            <div class="gift-bottom">
                                <span class="price">₱<?= number_format($product['price']); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <div id="toast" class="toast">
        <i class="fa-solid fa-check-circle"></i>
        <span id="toastMessage">No products found in this category.</span>
    </div>

    <script>
        const products = <?= json_encode(array_values($products)); ?>;

        function showToast(message, isError = false) {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            const toastIcon = toast.querySelector('i');

            toastMessage.textContent = message;

            if (isError) {
                toast.style.backgroundColor = '#ff4444';
                toastIcon.className = 'fa-solid fa-exclamation-circle';
            } else {
                toast.style.backgroundColor = '#4CAF50';
                toastIcon.className = 'fa-solid fa-check-circle';
            }

            toast.classList.add('show');

            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        function viewProduct(productId) {
            window.location.href = 'product.php?id=' + productId;
        }

        function filterByCategory(category) {
            const cards = document.querySelectorAll('.gift-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const cardCategory = card.getAttribute('data-category');
                if (cardCategory.includes(category.toLowerCase())) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (visibleCount === 0) {
                showToast('No products found in this category', true);
            }
        }

        function resetFilters() {
            const cards = document.querySelectorAll('.gift-card');
            cards.forEach(card => {
                card.style.display = 'block';
            });
        }
    </script>

<?php
    include 'includes/footer.php';
    exit;
}

// Get product from database
$product = getProduct($pdo, $id);

if (!$product) {
    // Product not found
    include 'includes/header.php';
?>
    <main class="product-page">
        <div class="error-container" style="text-align: center; padding: 100px 20px;">
            <i class="fa-solid fa-exclamation-circle" style="font-size: 80px; color: #ff6b6b; margin-bottom: 20px;"></i>
            <h2>Product Not Found</h2>
            <p style="color: #666; margin-bottom: 30px;">The product you're looking for doesn't exist or has been removed.</p>
            <a href="product.php" class="back-link" style="display: inline-block; padding: 12px 30px; background: #0f3d67; color: white; text-decoration: none; border-radius: 5px;">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Custom Merchandise
            </a>
        </div>
    </main>
<?php
    include 'includes/footer.php';
    exit;
}

include 'includes/header.php';
?>

<link rel="stylesheet" href="assets/css/customer-site/product.css">
<style>
    /* Additional styles for product page */
    .toast {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background-color: #4CAF50;
        color: white;
        padding: 15px 25px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        display: none;
        align-items: center;
        gap: 10px;
        z-index: 1000;
        animation: slideIn 0.3s ease;
    }

    .toast i {
        font-size: 20px;
    }

    .toast.show {
        display: flex;
    }

    .toast.error {
        background-color: #ff4444;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .error-container {
        text-align: center;
        padding: 100px 20px;
    }

    .fa-spinner {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .quantity-wrapper {
        margin: 20px 0;
    }

    .quantity-wrapper label {
        display: block;
        margin-bottom: 8px;
        color: #555;
        font-weight: 500;
    }

    .quantity-wrapper input {
        width: 100px;
        padding: 10px;
        border: 2px solid #e0e0e0;
        border-radius: 5px;
        font-size: 16px;
    }

    .quantity-wrapper input:focus {
        outline: none;
        border-color: #0f3d67;
    }

    .inquiry-section {
        margin-top: 30px;
    }

    .inquiry-section .btn-primary {
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 15px;
        text-decoration: none;
    }

    .stock {
        margin: 15px 0;
        padding: 10px;
        background: #f0f8ff;
        border-radius: 5px;
        color: #0f3d67;
    }

    .stock.low-stock {
        background: #fff3cd;
        color: #856404;
    }

    .stock.out-of-stock {
        background: #f8d7da;
        color: #721c24;
    }

    .product-category {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    .product-category a {
        color: #0f3d67;
        text-decoration: none;
    }

    .product-category a:hover {
        text-decoration: underline;
    }
</style>

<!-- Toast notification -->
<div id="toast" class="toast">
    <i class="fa-solid fa-check-circle"></i>
    <span id="toastMessage">Item added to cart!</span>
</div>

<main class="product-page">

    <a href="product.php" class="back-link">
        <i class="fa-solid fa-arrow-left"></i>
        Back to Custom Merchandise
    </a>

    <div class="product-container">

        <!-- LEFT: IMAGE -->
        <div class="product-image">
            <img src="<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
        </div>

        <!-- RIGHT: DETAILS -->
        <div class="product-details">

            <span class="badge"><?= htmlspecialchars($product['category_name'] ?? $product['category'] ?? 'Uncategorized'); ?></span>

            <h1>Custom Merchandise: <?= htmlspecialchars($product['name']); ?></h1>

            <p class="description">
                <?= htmlspecialchars($product['description']); ?>
            </p>

            <div class="price">
                ₱<?= number_format($product['price'], 2); ?>
            </div>

            <?php
            $stockValue = isset($product['stock']) ? (int)$product['stock'] : 0;
            $stockStatus = '';
            $stockClass = '';

            if ($stockValue <= 0) {
                $stockStatus = 'Out of Stock';
                $stockClass = 'out-of-stock';
            } elseif ($stockValue < 5) {
                $stockStatus = 'Low Stock - Only ' . $stockValue . ' left!';
                $stockClass = 'low-stock';
            } else {
                $stockStatus = $stockValue . ' in stock';
                $stockClass = '';
            }
            ?>

            <div class="stock <?= $stockClass ?>">
                <i class="fa-solid fa-box"></i>
                <?= $stockStatus ?>
            </div>

            <div class="inquiry-section">
                <a href="https://docs.google.com/forms/d/e/1FAIpQLSdBK8Cvyfb8qpRG1aCjTbtV9dILsi4U3xxe6lBrlSVxKggumg/viewform?embedded=true" class="btn-primary form-modal-trigger" target="_blank" rel="noopener noreferrer">
                    <i class="fa-solid fa-envelope"></i>
                    Inquire about this item
                </a>
            </div>

            <!-- Product Category Link -->
            <div class="product-category">
                <p>
                    <i class="fa-solid fa-tag"></i>
                    Category:
                    <a href="product.php?category=<?= urlencode(strtolower($product['category_name'] ?? $product['category'] ?? '')) ?>">
                        <?= htmlspecialchars($product['category_name'] ?? $product['category'] ?? 'Uncategorized') ?>
                    </a>
                </p>
            </div>

        </div>
    </div>
</main>

<script>
    // Toast function
    function showToast(message, isError = false) {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toastMessage');
        const toastIcon = toast.querySelector('i');

        toastMessage.textContent = message;

        if (isError) {
            toast.style.backgroundColor = '#ff4444';
            toastIcon.className = 'fa-solid fa-exclamation-circle';
        } else {
            toast.style.backgroundColor = '#4CAF50';
            toastIcon.className = 'fa-solid fa-check-circle';
        }

        toast.classList.add('show');

        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    // Cart and wishlist functionality has been removed from the product page.
</script>

<?php include 'includes/footer.php'; ?>