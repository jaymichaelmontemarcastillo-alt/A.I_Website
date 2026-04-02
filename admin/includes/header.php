<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Anything Inside Admin</title>
    <script>
        /**
         * PROBLEM: Sidebar elements (logo, arrow, button) animate when page loads
         * because CSS has transitions and JS applies .collapsed class after page renders.
         * 
         * SOLUTION: Apply collapsed state via CSS attribute BEFORE page renders,
         * disable transitions during load, then enable them after JS executes.
         */

        // Step 1: Check if sidebar should be collapsed (from localStorage)
        const savedCollapsedState = localStorage.getItem("sidebar-collapsed") === "true";

        // Step 2: Apply initial state BEFORE DOM is ready to prevent flicker
        if (savedCollapsedState) {
            document.documentElement.setAttribute("data-sidebar-collapsed", "true");
        }
    </script>

    <style>
        /**
         * FLICKER PREVENTION CSS
         * These rules apply collapsed state BEFORE DOMContentLoaded fires.
         * No transitions occur because these are base styles, not class changes.
         */

        /* When data attribute is set, instantly apply collapsed layout */
        html[data-sidebar-collapsed="true"] .admin-wrapper {
            grid-template-columns: var(--sidebar-collapsed) 1fr !important;
        }

        /* Hide nav text immediately (no transition) */
        html[data-sidebar-collapsed="true"] .nav-item span,
        html[data-sidebar-collapsed="true"] .nav-title,
        html[data-sidebar-collapsed="true"] .sidebar-footer .user-info,
        html[data-sidebar-collapsed="true"] .sidebar-footer span {
            opacity: 0 !important;
            transform: translateX(-10px) !important;
            pointer-events: none !important;
        }

        /* Shrink and reposition logo instantly (no transition) */
        html[data-sidebar-collapsed="true"] .logo-icon {
            transform: scale(0.8) !important;
            margin-left: 10px !important;
        }

        /* Rotate arrow and reposition button instantly (no transition) */
        html[data-sidebar-collapsed="true"] #toggle-btn {
            left: var(--sidebar-collapsed) !important;
        }

        html[data-sidebar-collapsed="true"] #toggle-btn .toggle-arrow {
            transform: rotate(180deg) !important;
        }

        /* Prevent ALL transitions during page load */
        html:not([data-transitions-enabled="true"]) * {
            transition: none !important;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/admin-site/admin_customers.css">
    <link rel="stylesheet" href="../../assets/css/admin-site/admin_users.css">
    <link rel="stylesheet" href="../../assets/css/admin-site/admin_inventory.css">
    <link rel="stylesheet" href="../../assets/css/admin-site/admin_payment.css">
    <link rel="stylesheet" href="../../assets/css/admin-site/admin_category.css">
    <link rel="stylesheet" href="../../assets/css/admin-site/orders_styles.css">
    <link rel="stylesheet" href="../../assets/css/admin-site/admin_header.css">
    <link rel="stylesheet" href="../../assets/css/admin-site/activity_logs.css">
    <link rel="stylesheet" href="../../assets/css/admin-site/products.css">
    <link rel="stylesheet" href="../../assets/css/admin-site/admin_dashboard.css">
    <link rel="stylesheet" href="../../assets/css/admin-site/admin_sidebar.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../../assets/js/admin-site-functions/admin_data_fetch.js"></script>
    <script src="../../assets/js/admin-site-functions/admin_products.js"></script>
    <script src="../../assets/js/admin-site-functions/admin_users.js"></script>
    <!-- <script src="../../assets/js/admin-site-functions/admin_category.js"></script>-->
    <script src="../../assets/js/admin-site-functions/admin_sidebar.js"></script>
    <script src="../../assets/js/admin-site-functions/admin_dashboard.js"></script>
</head>