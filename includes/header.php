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

    <link rel="stylesheet" href="<?= $baseDir ?>assets/css/customer-site/style.css">

    <link rel="stylesheet" href="<?= $baseDir ?>assets/css/customer-site/notifications.css">
</head>

<body>

    <header>
        <div class="top-header">
            <div class="brand">
                <a href="<?= $baseDir ?>index.php">
                    <img src="<?= $baseDir ?>assets/images/AI_Logo.jpg"
                        alt="Anything Inside Logo"
                        class="logo logo-header">
                </a>
            </div>

            <button id="menu_toggle" class="menu-toggle" aria-label="Toggle menu">
                <i class="fas fa-bars"></i>
            </button>

            <nav id="nav_menu">
                <a href="<?= $baseDir ?>index.php"
                    class="<?= $currentPage == 'index.php' ? 'active' : '' ?>">
                    Home
                </a>

                <a href="<?= $baseDir ?>Bundles.php"
                    class="<?= $currentPage == 'Bundles.php' ? 'active' : '' ?>">
                    Gift Bundles
                </a>

                <a href="<?= $baseDir ?>product.php"
                    class="<?= $currentPage == 'product.php' ? 'active' : '' ?>">
                    Custom Merchandise
                </a>

                <a href="<?= $baseDir ?>index.php#about-us">
                    About Us
                </a>

                <a href="https://docs.google.com/forms/d/e/1FAIpQLSdBK8Cvyfb8qpRG1aCjTbtV9dILsi4U3xxe6lBrlSVxKggumg/viewform?embedded=true"
                    class="form-modal-trigger"
                    target="_blank"
                    rel="noopener noreferrer">
                    Inquire Here
                </a>

                <?php if ($showSearch): ?>
                    <div class="header-icons">
                        <i id="search_icon" class="fas fa-search"></i>
                    </div>
                <?php endif; ?>
            </nav>
        </div>

        <?php if ($showSearch): ?>
            <div class="search-bar" id="searchBar">
                <div class="search-container">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text"
                        id="searchInput"
                        placeholder="Search products...">
                </div>
            </div>
        <?php endif; ?>
    </header>

    <!-- JavaScript files -->
    <script src="<?= $baseDir ?>assets/js/customer-site-functions/notifications.js"></script>

    <?php if ($showSearch): ?>
        <script src="<?= $baseDir ?>assets/js/customer-site-functions/search_product.js"></script>
    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const menuToggle = document.getElementById('menu_toggle');
            const navMenu = document.getElementById('nav_menu');

            if (menuToggle && navMenu) {
                menuToggle.addEventListener('click', function() {
                    navMenu.classList.toggle('show');
                });
            }

            // Close mobile menu when a nav link is clicked
            document.querySelectorAll('#nav_menu a').forEach(link => {
                link.addEventListener('click', () => {
                    navMenu.classList.remove('show');
                });
            });

            // Search bar toggle
            const searchIcon = document.getElementById('search_icon');
            const searchBar = document.getElementById('searchBar');

            if (searchIcon && searchBar) {
                searchIcon.addEventListener('click', function() {
                    searchBar.classList.toggle('active');
                });
            }
        });
    </script>