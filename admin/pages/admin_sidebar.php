<!--admin_sidebar.php-->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <img src="../../assets/images/AI_Logo.jpg" class="logo-icon">
        <span class="logo-text">Anything Inside Admin</span>
    </div>

    <nav class="sidebar-nav">
        <span class="nav-title">MAIN</span>
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

        <span style="margin-bottom: 30px;"></span>
        <span class="nav-title">MANAGEMENT</span>
        <a href="Customers.php" class="nav-item <?php echo ($current_page == 'Customers') ? 'active' : ''; ?>">
            <i class="fa-solid fa-users"></i> <span>Customers</span>
        </a>
        <a href="Inventory.php" class="nav-item <?php echo ($current_page == 'Inventory') ? 'active' : ''; ?>">
            <i class="fa-solid fa-warehouse"></i> <span>Inventory</span>
        </a>
        <a href="Admin_Users.php" class="nav-item <?php echo ($current_page == 'Admin_Users') ? 'active' : ''; ?>">
            <i class="fa-solid fa-user-gear"></i> <span>Admin Users</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="avatar">A</div>
            <div class="logo-text">
                <span>Super Admin</span>
            </div>
        </div>

        <a href="Logout.php" class="logout-link">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>