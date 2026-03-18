<?php

include '../includes/header.php';
?>

<body>

    <div class="admin-wrapper">
        <?php
        // Admin_Profile.php
        $current_page = 'Admin_Users';
        include 'admin_sidebar.php';
        ?>

        <main class="main-content">
            <header class="top-nav">
                <button id="toggle-btn" aria-label="Toggle Sidebar">
                    <i class="fa-solid fa-chevron-left toggle-arrow"></i>
                </button>
            </header>
            <section class="content-body">
                <h1 class="page-title">Admin Profile</h1>

            </section>
        </main>
    </div>
</body>
<script src="../../assets/js/admin-site-functions/admin_sidebar.js"></script>

</html>