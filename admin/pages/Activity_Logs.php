<?php
include '../includes/header.php';
?>

<body>
    <div class="admin-wrapper">
        <?php
        // Highlight current page in sidebar
        $current_page = 'Activity_Logs';
        include 'admin_sidebar.php';
        ?>

        <main class="main-content">
            <?php
            include 'admin_page_header.php';
            ?>

            <section class="content-body">
                <div class="page-header">
                    <div class="header-text">
                        <h1 class="page-title">System Activity Logs</h1>
                        <p class="subtitle">Monitor real-time user actions and system changes.</p>
                    </div>
                    <!--  <div class="header-actions">
                        <button class="btn-secondary"><i class="fa-solid fa-download"></i> Export CSV</button>
                    </div> -->
                </div>

                <!-- Filter Bar -->
                <div class="filter-wrapper">
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" placeholder="Search by user or action...">
                    </div>
                    <div class="filter-controls">
                        <select class="filter-select">
                            <option>All Actions</option>
                            <option>Logins</option>
                            <option>Product Updates</option>
                        </select>
                        <input type="date" class="filter-date">
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
                            <tr>
                                <td>
                                    <div class="user-info">
                                        <div class="avatar">AD</div>
                                        <span>Admin</span>
                                    </div>
                                </td>
                                <td><span class="action-text">Updated product <strong class="ref">#1203</strong></span></td>
                                <td><span class="timestamp">Mar 19, 2026 • 10:12 AM</span></td>
                                <td><span class="badge success">Success</span></td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="user-info">
                                        <div class="avatar sa">SA</div>
                                        <span>Super Admin</span>
                                    </div>
                                </td>
                                <td><span class="action-text">Deleted category <strong class="ref">#22</strong></span></td>
                                <td><span class="timestamp">Mar 19, 2026 • 09:50 AM</span></td>
                                <td><span class="badge error">Failed</span></td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="user-info">
                                        <div class="avatar">JS</div>
                                        <span>John Smith</span>
                                    </div>
                                </td>
                                <td><span class="action-text">Placed new order <strong class="ref">#ORD-006</strong></span></td>
                                <td><span class="timestamp">Mar 18, 2026 • 06:15 PM</span></td>
                                <td><span class="badge success">Success</span></td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="user-info">
                                        <div class="avatar">MB</div>
                                        <span>Maria Brown</span>
                                    </div>
                                </td>
                                <td><span class="action-text">Updated profile information</span></td>
                                <td><span class="timestamp">Mar 18, 2026 • 05:40 PM</span></td>
                                <td><span class="badge success">Success</span></td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="user-info">
                                        <div class="avatar">DL</div>
                                        <span>David Lee</span>
                                    </div>
                                </td>
                                <td><span class="action-text">Attempted login with wrong password</span></td>
                                <td><span class="timestamp">Mar 18, 2026 • 04:10 PM</span></td>
                                <td><span class="badge error">Failed</span></td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="user-info">
                                        <div class="avatar">AD</div>
                                        <span>Admin</span>
                                    </div>
                                </td>
                                <td><span class="action-text">Changed order status <strong class="ref">#ORD-003</strong> to shipped</span></td>
                                <td><span class="timestamp">Mar 18, 2026 • 03:55 PM</span></td>
                                <td><span class="badge success">Success</span></td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="user-info">
                                        <div class="avatar">KL</div>
                                        <span>Kevin Lim</span>
                                    </div>
                                </td>
                                <td><span class="action-text">Added item to cart</span></td>
                                <td><span class="timestamp">Mar 18, 2026 • 02:30 PM</span></td>
                                <td><span class="badge success">Success</span></td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="user-info">
                                        <div class="avatar sa">SA</div>
                                        <span>Super Admin</span>
                                    </div>
                                </td>
                                <td><span class="action-text">Exported system logs (CSV)</span></td>
                                <td><span class="timestamp">Mar 18, 2026 • 01:05 PM</span></td>
                                <td><span class="badge success">Success</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

        </main>
    </div>


</body>

</html>

<style>

</style>