<?php

include '../includes/header.php';
?>

<body>

    <div class="admin-wrapper">
        <?php
        // Orders.php
        $current_page = 'Orders';
        include 'admin_sidebar.php';
        ?>

        <main class="main-content">
            <header class="top-nav">
                <button id="toggle-btn" aria-label="Toggle Sidebar">
                    <i class="fa-solid fa-chevron-left toggle-arrow"></i>
                </button>
            </header>

            <section class="content-body">
                <h1 class="page-title">Orders</h1>

            </section>
        </main>
    </div>

    <script src="script.js"></script>
</body>

</html>