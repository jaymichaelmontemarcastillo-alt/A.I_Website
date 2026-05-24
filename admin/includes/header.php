<!DOCTYPE html>
<html lang="en">

<body>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
        <title>Anything Inside Admin</title>

        <script>
            (function () {
                try {
                    const THEME_KEY = "theme";
                    const LEGACY_KEY = "theme_preference";
                    const savedTheme = localStorage.getItem(THEME_KEY) || localStorage.getItem(LEGACY_KEY);
                    const isDark = savedTheme === "dark";

                    document.documentElement.classList.toggle("dark-mode", isDark);
                    if (document.body) {
                        document.body.classList.toggle("dark-mode", isDark);
                    }
                } catch (error) {
                    console.error("Theme init error:", error);
                } finally {
                    document.documentElement.setAttribute("data-theme-loaded", "true");
                }
            })();
        </script>
        <style>
            html:not([data-theme-loaded]) body {
                visibility: hidden;
            }
            html[data-theme-loaded] body {
                visibility: visible;
            }
            html:not([data-theme-loaded]) *,
            html:not([data-theme-loaded]) *::before,
            html:not([data-theme-loaded]) *::after {
                transition: none !important;
            }
        </style>
        <noscript>
            <style>
                html:not([data-theme-loaded]) body {
                    visibility: visible;
                }
            </style>
        </noscript>

        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <link rel="stylesheet" href="../../assets/css/admin-site/admin_users.css">
        <link rel="stylesheet" href="../../assets/css/admin-site/admin_payment.css">
        <link rel="stylesheet" href="../../assets/css/admin-site/admin_category.css">

        <link rel="stylesheet" href="../../assets/css/admin-site/admin_header.css">
        <link rel="stylesheet" href="../../assets/css/admin-site/products.css">
        <link rel="stylesheet" href="../../assets/css/admin-site/admin_dashboard.css">
        <link rel="stylesheet" href="../../assets/css/admin-site/dashboard_darkmode.css">
        <link rel="stylesheet" href="../../assets/css/admin-site/admin_sidebar.css">

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script src="../../assets/js/admin-site-functions/admin_data_fetch.js"></script>
        <script src="../../assets/js/admin-site-functions/admin_products.js"></script>

        <!-- <script src="../../assets/js/admin-site-functions/admin_category.js"></script>-->
        <script src="../../assets/js/admin-site-functions/admin_sidebar.js"></script>

    </head>