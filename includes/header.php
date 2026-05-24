<?php
$baseDir = isset($baseUrl) ? $baseUrl : '';
$currentPage = basename($_SERVER['PHP_SELF']);
$isSearchPage = ($currentPage === 'shop.php');
// header.php is included on every page, so it’s the perfect place to start the session and set up the baseDir variable for API calls and links.
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anything Inside</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="<?= $baseDir ?>assets/css/customer-site/quotation-list.css">
    <link rel="stylesheet" href="<?= $baseDir ?>assets/css/customer-site/quotation.css">
    <link rel="stylesheet" href="<?= $baseDir ?>assets/css/customer-site/style.css">
    <link rel="stylesheet" href="<?= $baseDir ?>assets/css/customer-site/notifications.css">

    <style>
        /* ============================================================
           HEADER ICON LINKS
        ============================================================ */
        .header-icons {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .icon-link {
            position: relative;
            color: #fff;
            font-size: 20px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            transition: background 0.2s ease;
            border-radius: 8px;
            flex-shrink: 0;
        }

        .icon-link:hover {
            background: rgba(255, 255, 255, 0.12);
        }

        /* ============================================================
           COUNTER BADGE  (.counter-badge)
           Red CIRCLE with white number — sits on top-right of icon.
           NEVER used for product category labels (those use .badge).
        ============================================================ */
        .counter-badge {
            position: absolute;
            top: -7px;
            right: -7px;
            /* Perfect circle sizing */
            width: 20px;
            height: 20px;
            min-width: 20px;
            border-radius: 50%;
            background: #ff4444;
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            font-size: 10px;
            line-height: 1;
            display: none;
            /* hidden by default — JS controls visibility */
            align-items: center;
            justify-content: center;
            border: 2px solid #123b5d;
            box-shadow: 0 2px 8px rgba(255, 68, 68, 0.55);
            pointer-events: none;
            z-index: 20;
        }

        /* when JS shows it */
        .counter-badge.visible {
            display: flex;
            animation: badgePop 0.25s cubic-bezier(0.34, 1.56, 0.64, 1) both;
        }

        /* numbers 10-99 need a tiny bit of padding but stay pill-like */
        .counter-badge.two-digit {
            width: auto;
            min-width: 20px;
            padding: 0 4px;
            border-radius: 10px;
        }

        @keyframes badgePop {
            0% {
                transform: scale(0.4);
                opacity: 0;
            }

            65% {
                transform: scale(1.25);
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* ============================================================
           MOBILE MENU BUTTON
        ============================================================ */
        .mobile-menu-btn {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #fff;
            padding: 8px;
            display: none;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            min-width: 44px;
        }

        /* ============================================================
           SIDEBAR LOGO (mobile only)
        ============================================================ */
        .sidebar-logo {
            display: none;
            flex-direction: row;
            align-items: center;
            gap: 10px;
            padding: 3px;
        }

        .sidebar-logo img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ffc107;
        }

        .sidebar-logo h3 {
            color: white;
            font-size: 16px;
            font-weight: 600;
            margin: 0;
            text-align: center;
        }

        .side-close {
            font-size: clamp(0.5rem, 1vw, 0.7rem);
        }

        /* ============================================================
           RESPONSIVE
        ============================================================ */
        @media (max-width: 767px) {
            .header-logo {
                display: none !important;
            }

            .sidebar-logo {
                display: flex !important;
            }

            .mobile-menu-btn {
                display: flex !important;
            }

            .icon-link {
                width: 36px;
                height: 36px;
                font-size: 17px;
            }

            .counter-badge {
                width: 17px;
                height: 17px;
                min-width: 17px;
                font-size: 9px;
                top: -5px;
                right: -5px;
                border-width: 1.5px;
            }

            .counter-badge.two-digit {
                width: auto;
                min-width: 17px;
                padding: 0 3px;
                border-radius: 9px;
            }
        }

        @media (max-width: 480px) {
            .icon-link {
                width: 32px;
                height: 32px;
                font-size: 15px;
            }

            .counter-badge {
                width: 16px;
                height: 16px;
                min-width: 16px;
                font-size: 8px;
                top: -4px;
                right: -4px;
            }
        }
    </style>
</head>

<body>
    <!-- Expose baseDir FIRST so badge manager and all page scripts can resolve API paths -->
    <script>
        window.__baseDir = '<?= addslashes($baseDir) ?>';
    </script>

    <header class="main-header">
        <div class="header-top">

            <!-- Mobile Menu Button (Left) -->
            <button id="mobile_menu_toggle" class="mobile-menu-btn" aria-label="Toggle menu">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Logo — hidden on mobile -->
            <div class="header-logo">
                <img src="<?= $baseDir ?>assets/images/AI_Logo.jpg" alt="Anything Inside Logo" class="logo-img">
            </div>

            <!-- Desktop Navigation -->
            <nav class="header-nav-desktop">
                <a href="<?= $baseDir ?>index.php" class="nav-link <?= $currentPage == 'index.php'      ? 'active' : '' ?>"><i class="fas fa-home"></i> Home</a>
                <a href="<?= $baseDir ?>shop.php?category=gift%20bundles" class="nav-link"><i class="fas fa-gift"></i> Gift Bundles</a>
                <a href="<?= $baseDir ?>shop.php?category=custom%20merchandise" class="nav-link"><i class="fas fa-box"></i> Custom Merchandise</a>
                <a href="<?= $baseDir ?>shop.php" class="nav-link <?= $currentPage == 'shop.php'        ? 'active' : '' ?>"><i class="fas fa-store"></i> All Products</a>
                <a href="#about-us" class="nav-link"><i class="fas fa-info-circle"></i> About Us</a>
                <a href="https://forms.gle/ujiUSwKGQLKgTa5D6" target="_blank" class="nav-link"><i class="fas fa-envelope"></i> Inquire Here</a>
            </nav>

            <!-- Mobile Search (home + shop only) -->
            <?php if ($isSearchPage): ?>
                <div class="header-search mobile-search">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchInput" placeholder="Search products..." class="search-input">
                    </div>
                </div>
            <?php endif; ?>

            <!-- Right Icon Links with Counter Badges -->
            <div class="header-icons">
                <a href="<?= $baseDir ?>wishlist.php" class="icon-link" aria-label="Wishlist">
                    <i class="far fa-heart"></i>
                    <span class="counter-badge" id="wishlistBadge"></span>
                </a>
                <a href="<?= $baseDir ?>cart.php" class="icon-link" aria-label="Shopping Cart">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="counter-badge" id="cartBadge"></span>
                </a>
            </div>
        </div>

        <!-- Desktop Search Row (home + shop only) -->
        <?php if ($isSearchPage): ?>
            <div class="header-search-row desktop-search">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInputDesktop" placeholder="Search products..." class="search-input">
                </div>
            </div>
        <?php endif; ?>

        <!-- Mobile Sidebar -->
        <nav id="mobile_sidebar" class="mobile-sidebar">

            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <img src="<?= $baseDir ?>assets/images/AI_Logo.jpg" alt="Anything Inside Logo">
                    <h3>Anything Inside</h3>
                </div>
                <button id="mobile_sidebar_close" class="sidebar-close"><i class="fas fa-times"></i></button>
            </div>
            <div class="sidebar-content">
                <a href="<?= $baseDir ?>index.php" class="sidebar-link <?= $currentPage == 'index.php'      ? 'active' : '' ?>"><i class="fas fa-home"></i> Home</a>
                <a href="<?= $baseDir ?>shop.php?category=gift%20bundles" class="sidebar-link"><i class="fas fa-gift"></i> Gift Bundles</a>
                <a href="<?= $baseDir ?>shop.php?category=custom%20merchandise" class="sidebar-link"><i class="fas fa-box"></i> Custom Merchandise</a>
                <a href="<?= $baseDir ?>shop.php" class="sidebar-link <?= $currentPage == 'shop.php'        ? 'active' : '' ?>"><i class="fas fa-store"></i> All Products</a>
                <a href="#about-us" class="sidebar-link"><i class="fas fa-info-circle"></i> About Us</a>
                <a href="https://forms.gle/ujiUSwKGQLKgTa5D6" target="_blank" class="sidebar-link"><i class="fas fa-envelope"></i> Inquire Here</a>
                <hr style="margin: 15px 0; border: none; border-top: 1px solid rgba(255,255,255,0.1);">
                <a href="<?= $baseDir ?>wishlist.php" class="sidebar-link <?= $currentPage == 'wishlist.php'    ? 'active' : '' ?>"><i class="far fa-heart"></i> Wishlist</a>
                <a href="<?= $baseDir ?>cart.php" class="sidebar-link <?= $currentPage == 'cart.php'        ? 'active' : '' ?>"><i class="fas fa-shopping-cart"></i> Cart</a>
                <a href="<?= $baseDir ?>orders.php" class="sidebar-link <?= $currentPage == 'orders.php'      ? 'active' : '' ?>"><i class="fas fa-box"></i> Orders</a>
                <a href="<?= $baseDir ?>quotations.php" class="sidebar-link <?= $currentPage == 'quotations.php'  ? 'active' : '' ?>"><i class="fas fa-file-alt"></i> Quotations</a>
            </div>
        </nav>

        <div id="mobile_overlay" class="mobile-overlay"></div>
    </header>

    <script src="<?= $baseDir ?>assets/js/customer-site-functions/notifications.js"></script>
    <script src="<?= $baseDir ?>assets/js/customer-site-functions/search_product.js"></script>

    <script>
        /* ================================================================
       BADGE MANAGER  — inline so it runs on EVERY page immediately.
       Uses .counter-badge class + .visible toggle.
       Always re-fetches from server; never trusts event payloads.
    ================================================================ */
        (function() {
            var base = window.__baseDir || '';

            function setBadge(el, count) {
                if (!el) return;
                count = parseInt(count, 10) || 0;

                if (count > 0) {
                    el.textContent = count > 99 ? '99+' : String(count);

                    // two-digit class for wider pill shape
                    if (count > 9) el.classList.add('two-digit');
                    else el.classList.remove('two-digit');

                    // re-trigger animation by removing then adding .visible
                    el.classList.remove('visible');
                    void el.offsetWidth; // force reflow
                    el.classList.add('visible');
                    el.style.display = 'flex';
                } else {
                    el.textContent = '';
                    el.classList.remove('visible', 'two-digit');
                    el.style.display = 'none';
                }
            }

            function fetchCart() {
                fetch(base + 'api/get_cart_count.php', {
                        cache: 'no-store'
                    })
                    .then(function(r) {
                        return r.json();
                    })
                    .then(function(d) {
                        setBadge(document.getElementById('cartBadge'), d.count);
                    })
                    .catch(function(e) {
                        console.warn('[Badge] cart fetch error', e);
                    });
            }

            function fetchWishlist() {
                fetch(base + 'api/get_wishlist_count.php', {
                        cache: 'no-store'
                    })
                    .then(function(r) {
                        return r.json();
                    })
                    .then(function(d) {
                        setBadge(document.getElementById('wishlistBadge'), d.count);
                    })
                    .catch(function(e) {
                        console.warn('[Badge] wishlist fetch error', e);
                    });
            }

            function init() {
                fetchCart();
                fetchWishlist();

                // Re-fetch on every cart/wishlist action — DO NOT trust event payload count
                document.addEventListener('cartUpdated', fetchCart);
                document.addEventListener('wishlistUpdated', fetchWishlist);

                // Fallback poll every 30s (catches changes from other tabs)
                setInterval(function() {
                    fetchCart();
                    fetchWishlist();
                }, 30000);

                // Expose globally so any page can manually trigger a refresh
                window.refreshCartBadge = fetchCart;
                window.refreshWishlistBadge = fetchWishlist;
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }
        })();
    </script>

    <script>
        /* ================================================================
       MOBILE SIDEBAR TOGGLE
    ================================================================ */
        document.addEventListener('DOMContentLoaded', function() {
            var btn = document.getElementById('mobile_menu_toggle');
            var sidebar = document.getElementById('mobile_sidebar');
            var overlay = document.getElementById('mobile_overlay');
            var closeBtn = document.getElementById('mobile_sidebar_close');

            function closeSidebar() {
                sidebar.classList.remove('open');
                overlay.classList.remove('open');
            }

            if (btn) btn.addEventListener('click', function() {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('open');
            });
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (overlay) overlay.addEventListener('click', closeSidebar);

            sidebar.querySelectorAll('.sidebar-link').forEach(function(l) {
                l.addEventListener('click', closeSidebar);
            });
        });
    </script>
</body>

</html>