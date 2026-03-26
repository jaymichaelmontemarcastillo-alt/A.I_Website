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
            <?php
            include 'admin_page_header.php';
            ?>
            <section class="content-body">

                <div class="page-header">
                    <div>
                        <h1 class="page-title">Admin Users</h1>
                        <p class="page-subtitle">Manage admin accounts and roles</p>
                    </div>

                    <button class="btn-add">
                        <i class="fa-solid fa-plus"></i> Add Admin
                    </button>
                </div>

                <!-- Admin List -->
                <div class="admin-list">

                    <!-- Admin Card -->
                    <div class="admin-card">
                        <div class="admin-left">
                            <div class="avatar gold">
                                <i class="fa-solid fa-shield"></i>
                            </div>
                            <div>
                                <h3>Admin Master</h3>
                                <p>admin@anythinginside.com</p>
                            </div>
                        </div>

                        <div class="admin-right">
                            <span class="badge role gold">Super Admin</span>
                            <span class="badge status active">Active</span>
                            <span class="last-login">Last: 2026-03-17 09:00</span>
                        </div>
                    </div>

                    <div class="admin-card">
                        <div class="admin-left">
                            <div class="avatar gray">
                                <i class="fa-solid fa-user-gear"></i>
                            </div>
                            <div>
                                <h3>Sarah Staff</h3>
                                <p>sarah@anythinginside.com</p>
                            </div>
                        </div>

                        <div class="admin-right">
                            <span class="badge role">Staff</span>
                            <span class="badge status active">Active</span>
                            <span class="last-login">Last: 2026-03-16 14:30</span>
                        </div>
                    </div>

                    <div class="admin-card">
                        <div class="admin-left">
                            <div class="avatar gray">
                                <i class="fa-solid fa-user-gear"></i>
                            </div>
                            <div>
                                <h3>Mike Manager</h3>
                                <p>mike@anythinginside.com</p>
                            </div>
                        </div>

                        <div class="admin-right">
                            <span class="badge role">Staff</span>
                            <span class="badge status inactive">Inactive</span>
                            <span class="last-login">Last: 2026-03-10 08:15</span>
                        </div>
                    </div>

                </div>

            </section>
        </main>
    </div>
</body>
<script src="../../assets/js/admin-site-functions/admin_sidebar.js"></script>

</html>