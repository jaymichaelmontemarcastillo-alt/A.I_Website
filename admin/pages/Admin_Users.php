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

                    <div class="page-header-actions">
                        <button class="btn-secondary" id="pendingRequestsBtn" type="button">
                            <i class="fa-solid fa-clock"></i>
                            Pending Requests
                            <span class="request-badge" id="pendingRequestsCount">0</span>
                        </button>
                        <button class="btn-add" type="button" onclick="window.location.href='../admin_register.php'">
                            <i class="fa-solid fa-plus"></i> Add Admin
                        </button>
                    </div>
                </div>

                <!-- Admin List Table -->
                <div class="admin-table-wrapper">
                    <table class="admin-table" id="adminList">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="adminTableBody">
                            <tr class="loading-row">
                                <td colspan="5">
                                    <div class="admin-list-loading">Loading admin accounts...</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-backdrop" id="deleteConfirmModal">
                    <div class="modal-panel modern">

                        <div class="modal-header">
                            <div class="modal-icon danger">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                            <div>
                                <h2>Delete Admin</h2>
                                <p class="modal-subtitle">This action cannot be undone</p>
                            </div>
                            <button class="modal-close" onclick="closeDeleteModal()">&times;</button>
                        </div>

                        <div class="modal-body">
                            <p id="deleteMessage" class="delete-message"></p>
                        </div>

                        <div class="modal-actions">
                            <button class="btn-secondary" onclick="closeDeleteModal()">Cancel</button>
                            <button class="btn-danger" id="confirmDeleteBtn">
                                <span class="btn-text">Delete</span>
                                <span class="btn-loading hidden"></span>
                            </button>
                        </div>

                    </div>
                </div>
                <div class="modal-backdrop" id="pendingRequestsModal" aria-hidden="true">
                    <div class="modal-panel">
                        <div class="modal-header">
                            <div>
                                <h2>Admin Requests</h2>
                                <p class="modal-subtitle">Review and approve or reject pending admin requests.</p>
                            </div>
                            <button type="button" class="modal-close" onclick="closePendingRequestsModal()" aria-label="Close modal">&times;</button>
                        </div>

                        <div class="modal-body">
                            <div class="modal-toolbar">
                                <span id="pendingRequestsCountLabel" class="request-summary">0 pending requests</span>
                                <button class="btn-secondary" type="button" onclick="fetchPendingRequests()">Refresh</button>
                            </div>

                            <div id="pendingRequestsError" class="modal-error"></div>

                            <div class="table-responsive">
                                <table class="request-table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Submitted</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="pendingRequestsList"></tbody>
                                </table>
                            </div>

                            <div class="request-empty hidden" id="pendingRequestsEmpty">
                                No pending admin requests found.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="toast-alert" id="toastAlert" role="status" aria-live="polite"></div>

            </section>
        </main>
    </div>

    <script src="../../assets/js/admin-site-functions/admin_users.js"></script>

</body>


</html>