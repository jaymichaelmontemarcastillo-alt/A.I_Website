<?php
include 'auth_check.php';
include '../includes/header.php';
?>

<body>
    <link rel="stylesheet" href="../../assets/css/admin-site/activity_logs.css">
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

                <!-- Stats Row -->
                <div class="stats-row" style="display: none;">
                    <div class="stat-card" style="display: none;">
                        <div class="stat-label">Total Events</div>
                        <div class="stat-value" id="s-total">—</div>
                        <div class="stat-sub">all time</div>
                    </div>
                    <div class="stat-card" style="display: none;">
                        <div class="stat-label">Successful</div>
                        <div class="stat-value success-val" id="s-success">—</div>
                        <div class="stat-sub"><span class="stat-dot success-dot"></span>success rate</div>
                    </div>
                    <div class="stat-card" style="display: none;">
                        <div class="stat-label">Failed</div>
                        <div class="stat-value error-val" id="s-fail">—</div>
                        <div class="stat-sub"><span class="stat-dot error-dot"></span>error rate</div>
                    </div>
                    <div class="stat-card" style="display: none;">
                        <div class="stat-label">Unique Users</div>
                        <div class="stat-value" id="s-users">—</div>
                        <div class="stat-sub">active admins</div>
                    </div>
                </div>

                <!-- Data Retention Controls -->
                <div class="retention-controls">
                    <div class="retention-header">
                        <h3><i class="fa-solid fa-database"></i> Data Retention Management</h3>
                        <span class="retention-status" id="retentionStatus">
                            <i class="fa-solid fa-circle-check"></i> Auto-cleanup: <strong id="retentionPeriod">Off</strong>
                        </span>
                    </div>
                    <div class="retention-body">
                        <div class="retention-toggle">
                            <label class="toggle-switch">
                                <input type="checkbox" id="retentionToggle">
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label">Enable Auto-Cleanup</span>
                        </div>
                        <div class="retention-config" id="retentionConfig" style="display: none;">
                            <div class="config-group">
                                <label for="retentionPeriodSelect">Delete logs older than:</label>
                                <select id="retentionPeriodSelect" class="filter-select">
                                    <option value="1">1 Week</option>
                                    <option value="2">2 Weeks</option>
                                    <option value="3">3 Weeks</option>
                                    <option value="1_month">1 Month</option>
                                    <option value="2_month" selected>2 Months</option>
                                    <option value="3_month">3 Months</option>
                                    <option value="6_month">6 Months</option>
                                </select>
                            </div>
                            <button class="btn-apply-retention" id="applyRetentionBtn">
                                <i class="fa-solid fa-trash-can"></i> Apply & Clean Now
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Filters & Search -->
                <div class="controls">
                    <div class="search-wrap">
                        <i class="fa-solid fa-magnifying-glass search-icon"></i>
                        <input
                            class="search-input"
                            id="searchInput"
                            type="text"
                            placeholder="Search user, action, reference ID…"
                            oninput="applyFilters()">
                    </div>

                    <select class="filter-select" id="userFilter" onchange="applyFilters()">
                        <option value="">All Users</option>
                    </select>

                    <select class="filter-select" id="actionFilter" onchange="applyFilters()">
                        <option value="">All Actions</option>
                    </select>

                    <select class="filter-select" id="statusFilter" onchange="applyFilters()">
                        <option value="">All Status</option>
                        <option value="Success">Success</option>
                        <option value="Failed">Failed</option>
                    </select>

                    <button class="btn-export" onclick="exportCSV()">
                        <i class="fa-solid fa-download"></i> Export CSV
                    </button>
                </div>

                <!-- Table -->
                <div class="activity-table-wrapper">
                    <div class="table-meta">
                        <span class="table-count" id="tableCount">Loading…</span>
                    </div>

                    <div class="table-scroll">
                        <table class="activity-table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Action Details</th>
                                    <th>Date &amp; Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <!-- rows injected by JS -->
                            </tbody>
                        </table>
                    </div>

                    <div class="pagination" id="paginationBar">
                        <span class="page-info" id="pageInfo"></span>
                        <div class="pager" id="pager"></div>
                    </div>
                </div>

            </section>
        </main>
    </div>

    <!-- Activity Logs Confirmation Modal - Specific class names to prevent conflicts -->
    <!-- IMPORTANT: Display none is set inline and via CSS to ensure it's hidden -->
    <div class="activity-log-modal-overlay" id="retentionModal" style="display: none;">
        <div class="activity-log-modal-container">
            <div class="activity-log-modal-header">
                <h3><i class="fa-solid fa-triangle-exclamation"></i> Confirm Data Cleanup</h3>
                <button class="activity-log-modal-close" onclick="closeRetentionModal()">&times;</button>
            </div>
            <div class="activity-log-modal-body">
                <p>You are about to delete all activity logs older than <strong id="modalPeriodText">2 months</strong>.</p>
                <p class="activity-log-modal-warning"><i class="fa-solid fa-circle-exclamation"></i> This action cannot be undone!</p>
                <div class="activity-log-password-group">
                    <label for="retentionPassword">Enter your admin password to confirm:</label>
                    <input type="password" id="retentionPassword" class="activity-log-password-input" placeholder="Enter your password...">
                    <div class="activity-log-modal-error" id="retentionError" style="display: none;"></div>
                </div>
            </div>
            <div class="activity-log-modal-footer">
                <button class="activity-log-btn-cancel" onclick="closeRetentionModal()">Cancel</button>
                <button class="activity-log-btn-confirm" id="confirmRetentionBtn">
                    <i class="fa-solid fa-trash-can"></i> Delete Logs
                </button>
            </div>
        </div>
    </div>

    <script src="../../assets/js/admin-site-functions/admin_data_fetch/activity_logs.js"></script>
    <script src="../../assets/js/admin-site-functions/activity_logs_retention.js"></script>
</body>

</html>