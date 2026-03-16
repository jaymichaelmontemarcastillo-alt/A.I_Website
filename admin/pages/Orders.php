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
        // Orders.php
        $current_page = 'Orders';
        include 'admin_sidebar.php';
        ?>

        <main class="main-content">
            <header class="top-nav">
                <span>Anything Inside Admin</span>
            </header>

            <section class="content-body">
                <h1 class="page-title">Orders</h1>

            </section>
        </main>
    </div>

    <script src="script.js"></script>
</body>

</html>