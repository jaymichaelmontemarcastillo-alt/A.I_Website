<?php
$baseDir = isset($baseUrl) ? $baseUrl : '';
$currentPage = basename($_SERVER['PHP_SELF']);
$showSearch = in_array($currentPage, ['product.php', 'Bundles.php']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anything Inside</title>

    <link href="https://fonts.googleapis.com/css2?family=Glacial+Indifference:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= $baseDir ?>assets/css/customer-site/styles.css">
    <link rel="stylesheet" href="<?= $baseDir ?>assets/css/customer-site/notifications.css">

    <?php if ($currentPage === 'homepage.php'): ?>
    <link rel="stylesheet" href="<?= $baseDir ?>assets/css/customer-site/homepage.css">
    <?php endif; ?>

    <!-- OVERRIDE STYLES: Force nav always visible as a single horizontal row -->
    <style>
        /* Hide the toggle button completely */
        .menu-toggle {
            display: none !important;
        }

        /* Make #nav_menu always visible as a single horizontal row (no wrapping) */
        #nav_menu {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: nowrap !important;   /* <-- changed from wrap to nowrap */
            gap: 0.5rem;
            align-items: center;
            position: static !important;
            background: transparent !important;
            opacity: 1 !important;
            transform: none !important;
            pointer-events: auto !important;
            padding: 0 !important;
            border: none !important;
            margin: 0 !important;
            min-width: auto !important;
            right: auto !important;
            top: auto !important;
            /* If the row overflows on very small screens, add scroll */
            overflow-x: auto;
            overflow-y: visible;
            /* Optional: keep scrollbar clean */
            scrollbar-width: thin;
        }
        /* Hide scrollbar for WebKit browsers (optional) */
        #nav_menu::-webkit-scrollbar {
            height: 4px;
        }
        #nav_menu::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 10px;
        }

        /* Remove the hover effect that shows the dropdown */
        .nav-container:hover #nav_menu {
            display: flex !important;
            opacity: 1 !important;
            transform: none !important;
            pointer-events: auto !important;
        }

        /* Override mobile styles – keep nav always visible and nowrap */
        @media (max-width: 768px) {
            #nav_menu {
                position: static !important;
                background: transparent !important;
                flex-direction: row !important;
                flex-wrap: nowrap !important;   /* still no wrap */
                display: flex !important;
                border: none !important;
                padding: 5px 0 !important;
                min-width: auto !important;
                overflow-x: auto;               /* allow horizontal scroll if needed */
            }
            #nav_menu a {
                padding: 8px 12px !important;
                width: auto !important;
                text-align: center !important;
                border-radius: 6px;
                flex-shrink: 0;                 /* prevent links from shrinking */
            }
            #nav_menu.show {
                display: flex !important;
            }
        }

        /* Ensure nav links match the original style and stay inline */
        #nav_menu a {
            white-space: nowrap;
            text-decoration: none;
            color: #e4eef9;
            font-weight: 500;
            font-size: clamp(12px, 1vw, 18px);
            padding: 10px 20px;
            border-radius: 0;
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            flex-shrink: 0;               /* prevent shrinking */
        }
        #nav_menu a:hover {
            background: rgba(255, 255, 255, 0.16);
            color: white;
        }
        #nav_menu a.active {
            background: #ffc107;
            color: #0f3d67;
        }

        /* Style the search link to match nav links */
        .search-link {
            display: inline-flex !important;
            align-items: center;
            gap: 0.8rem;
            text-decoration: none;
            color: #e4eef9;
            font-weight: 500;
            font-size: clamp(12px, 1vw, 18px);
            padding: 10px 20px;
            border-radius: 0;
            transition: all 0.25s ease;
            white-space: nowrap;
            cursor: pointer;
            flex-shrink: 0;               /* prevent shrinking */
        }
        .search-link:hover {
            background: rgba(255, 255, 255, 0.16);
            color: white;
        }
        .search-link i {
            font-size: inherit;
            width: 1.4rem;
            text-align: center;
            color: inherit;
        }
        .Search {
            font-weight: 500;
            font-size: inherit;
            color: inherit;
            margin: 0;
            padding: 0;
        }

        /* Ensure the nav container can accommodate the row without squeezing */
        .nav-container {
            flex-shrink: 0;                /* prevent container from shrinking too much */
            max-width: 100%;               /* allow it to take full width if needed */
        }
    </style>
</head>

<body>

    <header>
        <div class="top-header">
            <div class="brand">
                <img src="<?= $baseDir ?>assets/images/AI_Logo.jpg" alt="Anything Inside Logo" class="logo logo-header">
            </div>

            <div class="nav-container">
                <!-- The menu toggle button has been removed -->
                <nav id="nav_menu">
                    <a href="<?= $baseDir ?>homepage.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>"><i class="fas fa-home"></i> Home</a>
                    <a href="<?= $baseDir ?>Bundles.php"><i class="fas fa-gifts"></i> Gift Bundles</a>
                    <a href="<?= $baseDir ?>product.php"><i class="fas fa-tshirt"></i> Custom Merchandise</a>
                    <a href="<?= $baseDir ?>AboutUs.php"><i class="fas fa-info-circle"></i> About Us</a>
                    <a href="https://docs.google.com/forms/d/e/1FAIpQLSdBK8Cvyfb8qpRG1aCjTbtV9dILsi4U3xxe6lBrlSVxKggumg/viewform?embedded=true" class="form-modal-trigger" target="_blank" rel="noopener noreferrer"><i class="fas fa-paper-plane"></i> Inquire here</a>

                    <?php if ($showSearch): ?>
                    <div id="search_icon" class="search-link">
                        <i class="fas fa-search"></i>
                        <span class="Search">Search</span>
                    </div>
                    <?php endif; ?>
                </nav>
            </div>
        </div>

        <?php if ($showSearch): ?>
        <div class="search-bar" id="searchBar">
            <div class="search-container">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="searchInput" placeholder="Search products...">
            </div>
        </div>
        <?php endif; ?>
    </header>

    <!-- JavaScript files (keep them, but toggle-related JS is removed) -->
    <script src="<?= $baseDir ?>assets/js/customer-site-functions/notifications.js"></script>
    <?php if ($showSearch): ?>
    <script src="<?= $baseDir ?>assets/js/customer-site-functions/search_product.js"></script>
    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // All toggle-related code has been removed – the menu is always visible.
            // The search toggle logic is still handled by search_product.js.
        });
    </script>

</body>
</html>