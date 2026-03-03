<?php include 'api/products_list.php'; ?>
<?php include 'includes/header.php'; ?>


<link rel="stylesheet" href="assets/css/customer-site/home.css">
<main>

    <!-- ================= CATEGORY ================= -->
    <section class="category-section-prod-page">
        <div class="section-title">
            <h2>Our Products</h2>
            <p>Browse our collection of thoughtfully curated gifts for every special moment.</p>
        </div>

        <div class="category-buttons">
            <button>Birthday</button>
            <button>Anniversary</button>
            <button>Holiday</button>
            <button>Thank You</button>
            <button>Baby Shower</button>
            <button>Wedding</button>
        </div>
    </section>

    <!-- ================= FEATURED GIFTS ================= -->
    <section class="featured-section">

        <div class="featured-grid">

            <?php foreach ($products as $product): ?>

                <div class="gift-card"
                    onclick="window.location.href='product.php?id=<?= $product['id']; ?>'">

                    <div class="gift-img">
                        <span class="badge"><?= $product['category']; ?></span>
                        <img src="<?= $product['image']; ?>">
                    </div>

                    <div class="gift-info">
                        <h4><?= $product['name']; ?></h4>
                        <p><?= $product['description']; ?></p>

                        <div class="gift-bottom">
                            <span class="price">₱<?= number_format($product['price']); ?></span>

                            <div class="card-icons">

                                <!-- Wishlist -->
                                <button class="icon-btn wishlist"
                                    onclick="event.stopPropagation(); window.location.href='wishlist.php?id=<?= $product['id']; ?>'">
                                    <i class="fa-regular fa-heart"></i>
                                </button>

                                <!-- Add to Cart -->
                                <button class="icon-btn cart"
                                    onclick="event.stopPropagation(); window.location.href='add_to_cart.php?id=<?= $product['id']; ?>&quantity=1'">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                </button>

                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>

        </div>

        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>