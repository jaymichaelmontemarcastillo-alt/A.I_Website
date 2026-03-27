<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anything Inside Admin</title>
    <script>
        (function() {
            const isCollapsed = localStorage.getItem("sidebar-collapsed") === "true";

            if (isCollapsed) {
                document.addEventListener("DOMContentLoaded", function() {
                    const isCollapsed = localStorage.getItem("sidebar-collapsed") === "true";

                    const wrapper = document.querySelector(".admin-wrapper");

                    if (isCollapsed) {
                        wrapper.classList.add("collapsed");
                    }
                });
            }
        })();
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/admin-site/admin_customers.css">
    <link rel="stylesheet" href="../../assets/css/admin-site/admin_users.css">
    <link rel="stylesheet" href="../../assets/css/admin-site/admin_inventory.css">
    <link rel="stylesheet" href="../../assets/css/admin-site/admin_payment.css">
    <link rel="stylesheet" href="../../assets/css/admin-site/admin_category.css">
    <link rel="stylesheet" href="../../assets/css/admin-site/orders_styles.css">

    <link rel="stylesheet" href="../../assets/css/admin-site/activity_logs.css">
    <link rel="stylesheet" href="../../assets/css/admin-site/products.css">
    <link rel="stylesheet" href="../../assets/css/admin-site/admin_dashboard.css">
    <link rel="stylesheet" href="../../assets/css/admin-site/admin_sidebar.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>