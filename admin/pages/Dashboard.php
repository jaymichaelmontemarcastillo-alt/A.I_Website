<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anything Inside Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/admin-site/admin_dashboard.css">
    <script src="../../assets/js/admin-site-functions/admin_sidebar.js"></script>
</head>

<body>

    <div class="admin-wrapper">
        <?php
        // Dashboard.php
        $current_page = 'Dashboard';
        include 'admin_sidebar.php';
        ?>

        <main class="main-content">
            <header class="top-nav">
                <span>Anything Inside Admin</span>
            </header>

            <section class="content-body">
                <h1 class="page-title">Dashboard</h1>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon sales"><i class="fa-solid fa-dollar-sign"></i></div>
                        <div class="stat-info">
                            <p>Total Sales</p>
                            <h3>₱11,892</h3>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon orders"><i class="fa-solid fa-cart-plus"></i></div>
                        <div class="stat-info">
                            <p>Total Orders</p>
                            <h3>4</h3>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon customers"><i class="fa-solid fa-users-viewfinder"></i></div>
                        <div class="stat-info">
                            <p>Customers</p>
                            <h3>3</h3>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon stock"><i class="fa-solid fa-triangle-exclamation"></i></div>
                        <div class="stat-info">
                            <p>Low Stock Items</p>
                            <h3>2</h3>
                        </div>
                    </div>
                </div>

                <div class="charts-grid">
                    <div class="chart-card">
                        <h3><i class="fa-solid fa-chart-line"></i> Daily Sales</h3>
                        <div class="chart-placeholder">
                        </div>
                    </div>
                    <div class="chart-card">
                        <h3><i class="fa-solid fa-chart-area"></i> Monthly Sales</h3>
                        <div class="chart-placeholder"></div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="script.js"></script>
</body>

</html>