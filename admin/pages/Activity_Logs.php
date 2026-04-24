<?php
include '../includes/header.php';
?>

<body>
    <div class="admin-wrapper">
        <?php
        $current_page = 'Activity_Logs';
        include 'admin_sidebar.php';
        ?>

        <main class="main-content">
            <?php include 'admin_page_header.php'; ?>

            <section class="content-body">
                <div class="page-header">
                    <div class="header-text">
                        <h1 class="page-title">System Activity Logs</h1>
                        <p class="subtitle">Monitor real-time user actions and system changes.</p>
                    </div>
                </div>

                <div class="filter-wrapper">
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" placeholder="Search by user or action...">
                    </div>
                </div>

                <div class="activity-table-wrapper">
                    <table class="activity-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Action Details</th>
                                <th>Date & Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- DATA WILL LOAD HERE -->
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <script src="../../assets/js/admin-site-functions/admin_data_fetch/activity_logs.js"></script>
</body>

</html>