<!--admin_sidebar.php-->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <img src="../../assets/images/AI_Logo.jpg" alt="Admin Logo" class="logo-icon">
        <span class="logo-text">Admin Panel</span>
        <button id="toggle-btn" aria-label="Toggle Sidebar">
            <i class="fa-solid fa-chevron-left toggle-arrow"></i>
        </button>
    </div>
    <nav class="sidebar-nav">
        <a href="Dashboard.php" class="nav-item <?php echo ($current_page == 'Dashboard') ? 'active' : ''; ?>">
            <i class="fa-solid fa-table-columns"></i> <span>Dashboard</span>
        </a>
        <a href="Products.php" class="nav-item <?php echo ($current_page == 'Products') ? 'active' : ''; ?>">
            <i class="fa-solid fa-cube"></i> <span>Products</span>
        </a>
        <a href="Categories.php" class="nav-item <?php echo ($current_page == 'Categories') ? 'active' : ''; ?>">
            <i class="fa-solid fa-tags"></i> <span>Categories</span>
        </a>
        <a href="Orders.php" class="nav-item <?php echo ($current_page == 'Orders') ? 'active' : ''; ?>">
            <i class="fa-solid fa-cart-shopping"></i> <span>Orders</span>
        </a>
        <a href="Payments.php" class="nav-item <?php echo ($current_page == 'Payments') ? 'active' : ''; ?>">
            <i class="fa-solid fa-credit-card"></i> <span>Payments</span>
        </a>
        <a href="Customers.php" class="nav-item <?php echo ($current_page == 'Customers') ? 'active' : ''; ?>">
            <i class="fa-solid fa-users"></i> <span>Customers</span>
        </a>
        <a href="Admin_Users.php" class="nav-item <?php echo ($current_page == 'Admin_Users') ? 'active' : ''; ?>">
            <i class="fa-solid fa-user-gear"></i> <span>Admin Users</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-info">
            <strong>Admin User</strong>
            <small>Super Admin</small>
        </div>
        <a href="Logout.php" class="logout-link"><i class="fa-solid fa-right-from-bracket"></i> <span>Logout</span></a>
    </div>
</aside>