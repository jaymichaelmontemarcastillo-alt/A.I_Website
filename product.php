<?php
session_start();
include 'api/products_list.php';

$id = $_GET['id'] ?? null;

if (!$id || !isset($products[$id])) {
    echo "Product not found.";
    exit;
}

$product = $products[$id];
?>

<?php include 'includes/header.php'; ?>

<link rel="stylesheet" href="assets/css/customer-site/product.css">

<main class="product-page">

    <a href="shop.php" class="back-link">
        <i class="fa-solid fa-arrow-left"></i>
        Back to Products
    </a>
    <div class="product-container">

        <!-- LEFT: IMAGE -->
        <div class="product-image">
            <img src="<?= $product['image']; ?>" alt="<?= $product['name']; ?>">
        </div>

        <!-- RIGHT: DETAILS -->
        <div class="product-details">

            <span class="badge"><?= $product['category']; ?></span>

            <h1><?= $product['name']; ?></h1>

            <p class="description">
                <?= $product['description']; ?>
            </p>

            <div class="price">
                ₱<?= number_format($product['price']); ?>
            </div>

            <div class="stock">
                <i class="fa-solid fa-box"></i>
                25 in stock
            </div>
            <form method="POST" action="api/cart_manager.php">

                <input type="hidden" name="id" value="<?= $product['id']; ?>">

                <div class="quantity-wrapper">
                    <label>
                        <i class="fa-solid fa-layer-group"></i>
                        Quantity
                    </label>

                    <input type="number"
                        name="quantity"
                        value="1"
                        min="1">
                </div>

                <button type="submit"
                    name="add_cart"
                    class="btn-cart">

                    <i class="fa-solid fa-cart-shopping"></i>
                    Add to Cart
                </button>




            </form>
        </div>
    </div>

</main>

<?php include 'includes/footer.php'; ?>