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
                    <div class="stat-card">
                        <div class="stat-label">Total Events</div>
                        <div class="stat-value" id="s-total">—</div>
                        <div class="stat-sub">all time</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Successful</div>
                        <div class="stat-value success-val" id="s-success">—</div>
                        <div class="stat-sub"><span class="stat-dot success-dot"></span>success rate</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Failed</div>
                        <div class="stat-value error-val" id="s-fail">—</div>
                        <div class="stat-sub"><span class="stat-dot error-dot"></span>error rate</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Unique Users</div>
                        <div class="stat-value" id="s-users">—</div>
                        <div class="stat-sub">active admins</div>
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
                    <!--
                    <button class="btn-export" onclick="exportCSV()">
                        <i class="fa-solid fa-download"></i> Export CSV
                    </button>
                </div>
-->
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

    <script src="../../assets/js/admin-site-functions/admin_data_fetch/activity_logs.js"></script>
</body>

</html>