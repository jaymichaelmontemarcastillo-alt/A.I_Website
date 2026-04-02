<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Anything Inside</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="assets/css/customer-site/style.css">
    <link rel="stylesheet" href="assets/css/customer-site/notifications.css">


</head>

<body>

    <header>
        <div class="top-header">
            <div class="brand">
                <img src="assets/images/AI_Logo.jpg" alt="Anything Inside Logo" class="logo">
                <h1>Anything Inside</h1>
            </div>

            <nav>
                <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">Home</a>
                <a href="shop.php" class="<?= basename($_SERVER['PHP_SELF']) == 'shop.php' ? 'active' : '' ?>">Products</a>
                <a href="wishlist.php" class="<?= basename($_SERVER['PHP_SELF']) == 'wishlist.php' ? 'active' : '' ?>">Wishlist</a>
                <a href="cart.php" class="<?= basename($_SERVER['PHP_SELF']) == 'cart.php' ? 'active' : '' ?>">Cart</a>
                <a href="quotations.php" class="<?= basename($_SERVER['PHP_SELF']) == 'quotations.php' ? 'active' : '' ?>">Quotations</a>

                <div class="header-icons">
                    <i id="search_icon" class="fas fa-search"></i>

                    <a href="wishlist.php" class="wishlist-icon">
                        <i class="far fa-heart"></i>
                    </a>

                    <a href="cart.php" class="cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                </div>
            </nav>
        </div>

        <div class="search-bar" id="searchBar">
            <div class="search-container">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="searchInput" placeholder="Search products...">
            </div>
        </div>
    </header>
    <!-- JavaScript files -->
    <script src="assets/js/customer-site-functions/notifications.js"></script>
    <script src="assets/js/customer-site-functions/search_product.js"></script>

    <script>
        // Toggle search bar
        document.addEventListener('DOMContentLoaded', function() {
            const searchIcon = document.getElementById('search_icon');
            const searchBar = document.getElementById('searchBar');

            if (searchIcon && searchBar) {
                searchIcon.addEventListener('click', function() {
                    searchBar.classList.toggle('show');
                    if (searchBar.classList.contains('show')) {
                        document.getElementById('searchInput').focus();
                    }
                });
            }
        });

        // Optional: Keep cart/wishlist counts in memory if needed for other functionality
        // but they won't be displayed in the header
        let cartCount = 0;
        let wishlistCount = 0;

        // You can still track counts for other features if needed
        document.addEventListener('DOMContentLoaded', function() {
            // Get cart count (silently track for other features)
            fetch('api/get_cart_count.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        cartCount = data.count;
                    }
                })
                .catch(error => console.error('Error getting cart count:', error));

            // Get wishlist count (silently track for other features)
            fetch('api/get_wishlist_count.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        wishlistCount = data.count;
                    }
                })
                .catch(error => console.error('Error getting wishlist count:', error));
        });
    </script>