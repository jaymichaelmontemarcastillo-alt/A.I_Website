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
                <div class="admin-list" id="adminList">
                    <div class="admin-list-loading">Loading admin accounts...</div>
                </div>

            </section>
        </main>
    </div>
</body>
<script src="../../assets/js/admin-site-functions/admin_sidebar.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const adminListEl = document.getElementById('adminList');

        function formatDateTime(dateTimeString) {
            const date = new Date(dateTimeString);
            if (isNaN(date.getTime())) {
                return dateTimeString;
            }
            return date.toLocaleString();
        }

        function renderAdminCard(admin) {
            const card = document.createElement('div');
            card.className = 'admin-card';

            card.innerHTML = `
                <div class="admin-left">
                    <div class="avatar ${admin.Role === 'Super Admin' ? 'gold' : 'gray'}">
                        <i class="fa-solid fa-user-gear"></i>
                    </div>
                    <div>
                        <h3>${admin.FullName}</h3>
                        <p>${admin.Email}</p>
                    </div>
                </div>
                <div class="admin-right">
                    <span class="badge role ${admin.Role === 'Super Admin' ? 'gold' : ''}">${admin.Role}</span>
                    <span class="badge status ${admin.AccountStatus && admin.AccountStatus.toLowerCase() === 'active' ? 'active' : 'inactive'}">${admin.AccountStatus || 'Unknown'}</span>
                    <span class="last-login">Created: ${formatDateTime(admin.CreatedAt)}</span>
                </div>
            `;

            return card;
        }

        function showError(message) {
            adminListEl.innerHTML = `<div class="admin-list-error">${message}</div>`;
        }

        fetch('../../api/admin_site/fetch_admin_list.php', {
                credentials: 'include',
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                adminListEl.innerHTML = '';

                if (data.status !== 'success' || !Array.isArray(data.data)) {
                    showError(data.message || 'Unable to load admin list.');
                    return;
                }

                if (data.data.length === 0) {
                    adminListEl.innerHTML = '<div class="admin-list-empty">No admin accounts found.</div>';
                    return;
                }

                data.data.forEach(admin => {
                    adminListEl.appendChild(renderAdminCard(admin));
                });
            })
            .catch(error => {
                showError('Failed to load admin list.');
                console.error('Admin list fetch error:', error);
            });
    });
</script>

</html>