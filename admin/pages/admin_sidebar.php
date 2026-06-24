<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$role = strtolower($_SESSION['role'] ?? 'staff');
$current_page = $current_page ?? '';
?>

<aside class="sidebar" id="sidebar">

    <div class="sidebar-header">
        <img src="../../assets/images/AI_Logo.jpg" class="logo-icon">
        <span class="logo-text">Anything Inside Admin</span>
    </div>

    <nav class="sidebar-nav">

        <span class="nav-title">MAIN</span>

        <!-- DASHBOARD (ADMIN + FINANCE) -->
        <?php if (in_array($role, ['admin', 'finance'])): ?>
            <a href="Dashboard.php" class="nav-item <?= ($current_page == 'Dashboard') ? 'active' : '' ?>">
                <i class="fa-solid fa-table-columns"></i> <span>Dashboard</span>
            </a>
        <?php endif; ?>

        <!-- PRODUCTS (ADMIN ONLY) -->
        <?php if ($role === 'admin'): ?>
            <a href="Products.php" class="nav-item <?= ($current_page == 'Products') ? 'active' : '' ?>">
                <i class="fa-solid fa-cube"></i> <span>Products</span>
            </a>
        <?php endif; ?>


        <!-- CATEGORIES (ADMIN ONLY) 
        <?php if ($role === 'admin'): ?>
            <a href="Categories.php" class="nav-item <?= ($current_page == 'Categories') ? 'active' : '' ?>">
                <i class="fa-solid fa-tags"></i> <span>Categories</span>
            </a>
        <?php endif; ?>
-->

        <!-- ORDERS (ALL ROLES) -->
        <?php if (in_array($role, ['admin', 'finance', 'staff'])): ?>
            <a href="Orders.php" class="nav-item <?= ($current_page == 'Orders') ? 'active' : '' ?>">
                <i class="fa-solid fa-cart-shopping"></i> <span>Orders</span>
            </a>
        <?php endif; ?>


        <!-- ACTIVITY LOGS (ADMIN ONLY) -->
        <?php if ($role === 'admin'): ?>
            <a href="Activity_Logs.php" class="nav-item <?= ($current_page == 'Activity_Logs') ? 'active' : '' ?>">
                <i class="fa-solid fa-clock"></i> <span>Activity Logs</span>
            </a>
        <?php endif; ?>


        <span class="margin"></span>
        <span class="nav-title">MANAGEMENT</span>


        <!-- CUSTOMERS (ADMIN + STAFF) -->
        <?php if (in_array($role, ['admin', 'staff'])): ?>
            <a href="Customers.php" class="nav-item <?= ($current_page == 'Customers') ? 'active' : '' ?>">
                <i class="fa-solid fa-users"></i> <span>Customers</span>
            </a>
        <?php endif; ?>


        <!-- QUOTATION (ADMIN + FINANCE) -->
        <?php if (in_array($role, ['admin', 'finance'])): ?>
            <a href="Quotation.php" class="nav-item <?= ($current_page == 'Quotation') ? 'active' : '' ?>">
                <i class="fa-solid fa-file-invoice"></i> <span>Quotations</span>
            </a>
        <?php endif; ?>


        <!-- INVENTORY (ALL ROLES) -->
        <?php if (in_array($role, ['admin', 'finance', 'staff'])): ?>
            <a href="Inventory.php" class="nav-item <?= ($current_page == 'Inventory') ? 'active' : '' ?>">
                <i class="fa-solid fa-warehouse"></i> <span>Inventory</span>
            </a>
        <?php endif; ?>


        <!-- ADMIN USERS (ADMIN ONLY) -->
        <?php if ($role === 'admin'): ?>
            <a href="Admin_Users.php" class="nav-item <?= ($current_page == 'Admin_Users') ? 'active' : '' ?>">
                <i class="fa-solid fa-user-gear"></i> <span>Admin Users</span>
            </a>
        <?php endif; ?>

    </nav>

    <div class="sidebar-footer">
        <a href="logout.php" class="logout-link">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Logout</span>
        </a>
    </div>

</aside>