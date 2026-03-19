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
            <header class="top-nav">
                <button id="toggle-btn" aria-label="Toggle Sidebar">
                    <i class="fa-solid fa-chevron-left toggle-arrow"></i>
                </button>
            </header>

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

    <script src="../../assets/js/admin-site-functions/admin_sidebar.js"></script>
</body>

</html>

<style>
    /* Activity Logs Page Styles */

    .page-header {
        margin-bottom: 2rem;
    }

    .activity-table td {
        font-size: 0.9rem;
        color: #334155;
    }

    .timestamp {
        font-size: 0.85rem;
        color: #64748b;
    }

    .action-text {
        max-width: 420px;
        color: #1e293b;
    }

    .page-title {
        font-size: 1.95rem;
        color: #102a43;
        font-weight: 700;
        margin-bottom: 0.3rem;
    }

    .subtitle {
        font-size: 0.95rem;
        color: #66788a;
        margin-bottom: 0;
    }

    .header-actions .btn-secondary {
        background: #fff;
        color: #102a43;
        border: 1px solid #d6dde6;
        padding: 0.6rem 1rem;
        border-radius: 5px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s ease;
        display: inline-flex;
        gap: 0.5rem;
        align-items: center;
        box-shadow: 0 2px 8px rgba(16, 42, 67, 0.08);
    }

    .header-actions .btn-secondary:hover {
        border-color: #0d3b56;
        color: #0d3b56;
        transform: translateY(-1px);
    }

    .filter-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .search-box {
        display: flex;
        flex-direction: row;
        width: 40%;
        padding: 10px;
        border-radius: 50px;
        border: 1px solid #d6dde6;
        background: #fff;
        transition: 0.2s ease;
        font-family: Inter, "sans-serif";
    }

    .search-box:focus-within {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
    }

    .search-box i {
        color: #6b7280;
        margin-right: 0.5rem;
    }

    .search-box input {
        width: 100%;
        border: none;
        font-size: 0.92rem;
        outline: none;
        color: #102a43;
        font-family: Inter, "sans-serif";
    }

    .filter-controls {
        display: flex;
        flex: 1 1 280px;
        gap: 0.75rem;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .filter-select {
        border: 1px solid #d6dde6;
        border-radius: 50px;
        padding: 0.55rem 2.2rem 0.55rem 0.9rem;
        font-size: 0.9rem;
        color: #0f172a;
        background: #fff;
        appearance: none;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        font-family: Inter, "sans-serif";
        /* custom arrow */
        background-image: url("data:image/svg+xml;utf8,<svg fill='%2364748b' height='20' viewBox='0 0 20 20' width='20' xmlns='http://www.w3.org/2000/svg'><path d='M5.5 7l4.5 5 4.5-5z'/></svg>");
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 16px;
    }

    .filter-select:hover {
        border-color: #3b82f6;
    }

    .filter-select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }

    .filter-date {
        border: 1px solid #d6dde6;
        border-radius: 50px;
        padding: 0.55rem 0.8rem;
        font-family: Inter, "sans-serif";
        font-size: 0.9rem;
        color: #0f172a;
        background: #fff;
        transition: 0.2s ease;
    }

    .filter-date:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }

    .activity-table-wrapper {
        overflow-x: auto;
        background: #fff;
        border-radius: 5px;
        box-shadow: 0 12px 28px rgba(16, 42, 67, 0.08);
        border: 1px solid #e6ebf0;
    }

    .activity-table th {
        background-color: #f1f5f9;
        color: #0f172a;
        font-weight: 600;
        text-transform: none;
        font-size: 0.85rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .activity-table tr {
        transition: all 0.2s ease;
    }

    .activity-table tr:nth-child(even) {
        background: #f8fafc;
    }

    /* 🔥 FIXED HOVER (SOFT LIKE ORDERS PAGE) */
    .activity-table tbody tr:hover {
        background-color: #eaf2ff;
    }

    .activity-table td {
        border-bottom: 1px solid #edf2f7;
        padding: 1rem;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .avatar {
        min-width: 34px;
        height: 34px;
        border-radius: 50%;
        background-color: #0d3b56;
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .action-text {
        display: inline-block;
        max-width: 320px;
        line-height: 1.3;
        color: #1f2937;
    }

    .timestamp {
        color: #64748b;
    }

    .badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .badge.success {
        background: #dcfce7;
        color: #166534;
    }

    .badge.error {
        background: #fee2e2;
        color: #b91c1c;
    }

    @media (max-width: 1100px) {
        .content-body {
            padding: 1.25rem;
        }

        .activity-table {
            min-width: 650px;
        }

        .filter-controls {
            justify-content: flex-start;
        }
    }

    @media (max-width: 860px) {
        .content-body {
            padding: 1rem;
        }

        .page-header {
            flex-direction: column;
            align-items: stretch;
        }

        .header-actions {
            width: 100%;
            display: flex;
            justify-content: flex-start;
        }

        .activity-table {
            min-width: 600px;
        }
    }

    @media (max-width: 680px) {
        .content-body {
            padding: 0.9rem;
        }

        .search-box {
            max-width: 100%;
        }

        .filter-controls {
            width: 100%;
            justify-content: space-between;
        }

        /* Focus on readability by hiding non-essential columns */
        .activity-table thead th:nth-child(4),
        .activity-table tbody td:nth-child(4),
        .activity-table thead th:nth-child(5),
        .activity-table tbody td:nth-child(5) {
            display: none;
        }

        .activity-table th,
        .activity-table td {
            padding: 0.7rem 0.8rem;
        }

        .activity-table {
            min-width: 0;
        }

        .activity-table-wrapper {
            box-shadow: none;
            border: 1px solid #edf2f7;
        }
    }

    @media (max-width: 460px) {
        .page-title {
            font-size: 1.4rem;
        }

        .subtitle {
            font-size: 0.85rem;
        }

        .activity-table th,
        .activity-table td {
            font-size: 0.8rem;
        }

        .filter-controls {
            flex-direction: column;
            align-items: stretch;
            gap: 8px;
        }
    }
</style>