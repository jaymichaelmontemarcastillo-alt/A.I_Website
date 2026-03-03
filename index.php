<?php include 'api/products_list.php'; ?>
<?php include 'includes/header.php'; ?>
<link rel="stylesheet" href="assets/css/customer-site/home.css">

<main>

    <!-- ================= HERO ================= -->
    <section class="hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1>
                    Finding the <span>Perfect Gift</span><br>
                    shouldn't be stressfull.
                </h1>

                <p>
                    <span> Let us help you make it effortless and personal.</span>
                    <br>
                    Curated gift collections crafted with love. From birthdays to weddings,
                    we've got something special inside for everyone.
                </p>

                <div class="hero-buttons">
                    <a href="shop.php" style="color:inherit; text-decoration:none;">
                        <button class="btn-primary">
                            Shop Now <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </a>
                    <a href="shop.php" style="color:inherit; text-decoration:none;">
                        <button class="btn-secondary" style="border:0.35px solid rgb(219, 219, 219)">
                            Browse Categories
                        </button>
                    </a>
                </div>
            </div>

            <div class="hero-image">
                <img src="https://images.unsplash.com/photo-1522673607200-164d1b6ce486?w=600">
            </div>
        </div>
    </section>

    <!-- ================= FEATURES ================= -->
    <section class="features-bar">
        <div class="feature-item">
            <i class="fa-solid fa-gift"></i>
            <div>
                <h4>Curated Gifts</h4>
                <p>Hand-picked selections</p>
            </div>
        </div>

        <div class="feature-item">
            <i class="fa-solid fa-truck-fast"></i>
            <div>
                <h4>Fast Delivery</h4>
                <p>Nationwide shipping</p>
            </div>
        </div>

        <div class="feature-item">
            <i class="fa-solid fa-shield-halved"></i>
            <div>
                <h4>Secure Payment</h4>
                <p>Safe transactions</p>
            </div>
        </div>
    </section>

    <!-- ================= CATEGORY ================= -->
    <section class="category-section">
        <div class="section-title">
            <h2>Shop by Category</h2>
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
        <div class="featured-header">
            <h2>Featured Gifts</h2>
            <a href="shop.php">View all →</a>
        </div>

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
    </section>

</main>
<?php include 'includes/footer.php'; ?>