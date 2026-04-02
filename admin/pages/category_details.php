<?php

include '../includes/header.php';
?>

<body>

    <div class="admin-wrapper">
        <?php
        $current_page = 'Categories';
        include 'admin_sidebar.php';
        ?>

        <main class="main-content">
            <?php
            include 'admin_page_header.php';
            ?>

            <section class="content-body">
                <!-- Back Button -->
                <a href="Categories.php" class="back-link">
                    <i class="fa-solid fa-arrow-left"></i> Back to Categories
                </a>

                <!-- Category Info Card -->
                <div class="category-info-card">
                    <div id="categoryInfoContainer">
                        <p class="text-center" style="padding: 30px;">Loading category information...</p>
                    </div>
                </div>

                <!-- Products Section -->
                <div class="products-section">
                    <div class="section-header">
                        <h2>Products in this Category</h2>
                        <button class="btn btn-primary" id="addProductBtn">
                            <i class="fa-solid fa-plus"></i> Add Product
                        </button>
                    </div>

                    <!-- Search -->
                    <input
                        type="text"
                        id="productSearch"
                        placeholder="Search products..."
                        class="search-input">

                    <!-- Products Table -->
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="productsTableBody">
                                <tr class="loading-row">
                                    <td colspan="5" class="text-center">Loading products...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div id="paginationContainer" class="pagination-container"></div>

                    <!-- Empty State -->
                    <div id="emptyProducts" class="empty-state" style="display: none;">
                        <i class="fa-solid fa-box-open"></i>
                        <h3>No Products</h3>
                        <p>This category doesn't have any products yet</p>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- ADD PRODUCT TO CATEGORY MODAL -->
    <div id="addProductModal" class="category-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Product to Category</h3>
                <button class="modal-close" id="closeAddProductBtn">&times;</button>
            </div>

            <input
                type="text"
                id="productSearchModal"
                placeholder="Search products..."
                class="search-input modal-search">

            <div id="productsListContainer" style="max-height: 400px; overflow-y: auto;">
                <p class="text-center" style="padding: 20px;">Loading products...</p>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-secondary" id="cancelAddProductBtn">Cancel</button>
            </div>
        </div>
    </div>

    <style>
        /* ===== THEME VARIABLES ===== */
        :root {
            --page-bg: #f4f7f9;
            --card-bg: #ffffff;
            --text-primary: #1f2d3d;
            --text-secondary: #6b7c93;
            --border-color: #e5e7eb;
            --table-header-bg: #f9fafb;
            --table-hover-bg: #fafbfc;
            --product-item-hover: #f9fafb;
            --input-focus-shadow: rgba(31, 78, 121, 0.1);
            --btn-secondary-hover: #d1d5db;
            --action-hover-bg: #f0f0f0;
            --danger-color: #ef4444;
            --danger-hover-bg: rgba(239, 68, 68, 0.1);
            --stock-in-bg: #d1fae5;
            --stock-in-text: #065f46;
            --stock-low-bg: #fef3c7;
            --stock-low-text: #92400e;
            --stock-out-bg: #fee2e2;
            --stock-out-text: #991b1b;
            --badge-active-bg: #d1fae5;
            --badge-active-text: #065f46;
            --badge-inactive-bg: #fee2e2;
            --badge-inactive-text: #991b1b;
        }

        body.dark-mode {
            --page-bg: #0f172a;
            --card-bg: #111827;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --border-color: #1f2937;
            --table-header-bg: #1f2937;
            --table-hover-bg: #1e293b;
            --product-item-hover: #1f2937;
            --input-focus-shadow: rgba(59, 130, 246, 0.1);
            --btn-secondary-hover: #374151;
            --action-hover-bg: #1f2937;
            --danger-color: #ef4444;
            --danger-hover-bg: rgba(239, 68, 68, 0.1);
            --stock-in-bg: #115e59;
            --stock-in-text: #a7f3d0;
            --stock-low-bg: #854d0e;
            --stock-low-text: #fef08a;
            --stock-out-bg: #7f1d1d;
            --stock-out-text: #fecaca;
            --badge-active-bg: #115e59;
            --badge-active-text: #a7f3d0;
            --badge-inactive-bg: #7f1d1d;
            --badge-inactive-text: #fecaca;
        }

        /* Back Link */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 20px;
            transition: var(--transition);
        }

        .back-link:hover {
            gap: 12px;
        }

        /* Category Info Card */
        .category-info-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            margin-bottom: 30px;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .category-info {
            padding: 30px;
        }

        .category-header-section {
            display: flex;
            justify-content: space-between;
            align-items: start;
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .category-name {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .category-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
        }

        .detail-item {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            font-size: 12px;
            color: var(--text-secondary);
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .detail-value {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
        }

        /* Products Section */
        .products-section {
            margin-top: 30px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            gap: 15px;
            flex-wrap: wrap;
        }

        .section-header h2 {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        /* Modal Styles */
        .modal-search {
            margin: 15px;
            width: calc(100% - 30px);
        }

        .product-item {
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            transition: var(--transition);
        }

        .product-item:hover {
            background: var(--product-item-hover);
        }

        .product-item-info {
            flex: 1;
        }

        .product-item-name {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 5px;
        }

        .product-item-price {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .add-product-btn {
            padding: 6px 12px;
            background: var(--primary-blue);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            transition: var(--transition);
        }

        .add-product-btn:hover {
            background: var(--hover-blue);
        }

        /* Stock Status */
        .stock-status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        .stock-status.in-stock {
            background: var(--stock-in-bg);
            color: var(--stock-in-text);
        }

        .stock-status.low-stock {
            background: var(--stock-low-bg);
            color: var(--stock-low-text);
        }

        .stock-status.out-of-stock {
            background: var(--stock-out-bg);
            color: var(--stock-out-text);
        }

        /* Status Badge */
        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-active {
            background: var(--badge-active-bg);
            color: var(--badge-active-text);
        }

        .badge-inactive {
            background: var(--badge-inactive-bg);
            color: var(--badge-inactive-text);
        }

        /* Buttons */
        .btn {
            padding: 10px 18px;
            border: none;
            border-radius: var(--radius);
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
        }

        .btn-primary {
            background: var(--primary-blue);
            color: white;
        }

        .btn-primary:hover {
            background: var(--hover-blue);
        }

        .btn-secondary {
            background: var(--border-color);
            color: var(--text-primary);
        }

        .btn-secondary:hover {
            background: var(--btn-secondary-hover);
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            transition: var(--transition);
            background: transparent;
            color: var(--primary-blue);
            font-weight: 500;
        }

        .action-btn:hover {
            background: var(--action-hover-bg);
        }

        .action-btn.danger {
            color: var(--danger-color);
        }

        .action-btn.danger:hover {
            background: var(--danger-hover-bg);
        }

        /* Search */
        .search-input {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            font-size: 14px;
            background: var(--card-bg);
            color: var(--text-primary);
            font-family: inherit;
            margin-bottom: 20px;
            transition: var(--transition);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px var(--input-focus-shadow);
        }

        .search-input::placeholder {
            color: var(--text-secondary);
        }

        /* Table */
        .table-container {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            overflow-x: auto;
            margin-bottom: 25px;
            box-shadow: var(--shadow-sm);
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .data-table thead {
            background: var(--table-header-bg);
            border-bottom: 2px solid var(--border-color);
        }

        .data-table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: var(--text-primary);
        }

        .data-table td {
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        .data-table tbody tr:hover {
            background: var(--table-hover-bg);
        }

        .text-center {
            text-align: center;
            color: var(--text-secondary);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        .empty-state h3 {
            font-size: 20px;
            color: var(--text-primary);
            margin-bottom: 10px;
        }

        /* Modal */
        .category-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        body.dark-mode .category-modal {
            background: rgba(0, 0, 0, 0.7);
        }

        .category-modal.show {
            display: flex;
        }

        .modal-content {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        body.dark-mode .modal-content {
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7);
        }

        .modal-header {
            padding: 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 18px;
            color: var(--text-primary);
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .modal-close:hover {
            color: var(--text-primary);
        }

        #productsListContainer {
            flex: 1;
            overflow-y: auto;
        }

        .form-actions {
            padding: 20px;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            border-top: 1px solid var(--border-color);
        }

        /* Pagination */
        .pagination-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .pagination-btn {
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            background: var(--card-bg);
            color: var(--text-primary);
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            transition: var(--transition);
        }

        .pagination-btn:hover:not(:disabled) {
            background: var(--primary-blue);
            color: white;
        }

        .pagination-btn.active {
            background: var(--primary-blue);
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .category-header-section {
                flex-direction: column;
            }

            .category-details {
                grid-template-columns: 1fr;
            }

            .section-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .modal-content {
                width: 95%;
            }
        }
    </style>

    <script>
        const API_BASE = '../../api/admin_site/category_actions.php';
        const categoryId = new URLSearchParams(window.location.search).get('id');
        let currentPage = 1;
        let currentLimit = 10;

        if (!categoryId) {
            alert('Category ID not found');
            window.location.href = 'Categories.php';
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadCategoryInfo();
            loadProducts();
            setupEventListeners();
        });

        // Event Listeners
        function setupEventListeners() {
            document.getElementById('addProductBtn').addEventListener('click', openAddProductModal);
            document.getElementById('closeAddProductBtn').addEventListener('click', closeAddProductModal);
            document.getElementById('cancelAddProductBtn').addEventListener('click', closeAddProductModal);
            document.getElementById('productSearch').addEventListener('input', () => {
                currentPage = 1;
                loadProducts();
            });
            document.getElementById('productSearchModal').addEventListener('input', loadUnassignedProducts);
        }

        // Load Category Info
        function loadCategoryInfo() {
            fetch(`${API_BASE}?action=getcategory&id=${categoryId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        renderCategoryInfo(data.data);
                    } else {
                        showError(data.message);
                    }
                })
                .catch(err => showError('Failed to load category'));
        }

        // Render Category Info
        function renderCategoryInfo(cat) {
            const container = document.getElementById('categoryInfoContainer');
            const createdDate = new Date(cat.created_at).toLocaleDateString();

            container.innerHTML = `
                <div class="category-info">
                    <div class="category-header-section">
                        <div>
                            <h1 class="category-name">${escapeHtml(cat.name)}</h1>
                            <p style="color: var(--text-secondary); margin: 8px 0 0 0;">
                                ${escapeHtml(cat.description || 'No description')}
                            </p>
                        </div>
                        <span class="badge badge-${cat.status}">
                            ${cat.status.charAt(0).toUpperCase() + cat.status.slice(1)}
                        </span>
                    </div>
                    <div class="category-details">
                        <div class="detail-item">
                            <span class="detail-label">Total Products</span>
                            <span class="detail-value">${cat.product_count}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Category ID</span>
                            <span class="detail-value">#${cat.id}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Slug</span>
                            <span class="detail-value">${cat.slug}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Created</span>
                            <span class="detail-value">${createdDate}</span>
                        </div>
                    </div>
                </div>
            `;
        }

        // Load Products
        function loadProducts() {
            const search = document.getElementById('productSearch').value;

            const params = new URLSearchParams({
                action: 'getcategoryproducts',
                category_id: categoryId,
                page: currentPage,
                limit: currentLimit,
                search: search
            });

            fetch(`${API_BASE}?${params}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        renderProducts(data.data);
                        renderPagination(data.pagination);

                        if (data.data.length === 0 && currentPage === 1) {
                            showEmptyProducts();
                        } else {
                            hideEmptyProducts();
                        }
                    } else {
                        showError(data.message);
                    }
                })
                .catch(err => {
                    console.error(err);
                    showError('Failed to load products');
                });
        }

        // Render Products
        function renderProducts(products) {
            const tbody = document.getElementById('productsTableBody');

            if (products.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center">No products found</td></tr>';
                return;
            }

            tbody.innerHTML = products.map(prod => {
                let stockStatus = 'in-stock';
                if (prod.stock === 0) {
                    stockStatus = 'out-of-stock';
                } else if (prod.stock < 5) {
                    stockStatus = 'low-stock';
                }

                return `
                    <tr>
                        <td><strong>${escapeHtml(prod.name)}</strong></td>
                        <td>₱${parseFloat(prod.price).toFixed(2)}</td>
                        <td>${prod.stock}</td>
                        <td>
                            <span class="stock-status ${stockStatus}">
                                ${prod.stock === 0 ? 'Out of Stock' : prod.stock < 5 ? 'Low Stock' : 'In Stock'}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="products.php?id=${prod.id}" class="action-btn" style="text-decoration: none;">
                                    <i class="fa-solid fa-edit"></i> Edit
                                </a>
                                <button class="action-btn danger" onclick="removeProduct(${prod.id})">
                                    <i class="fa-solid fa-trash"></i> Remove
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // Remove Product
        function removeProduct(productId) {
            if (!confirm('Remove this product from category?')) return;

            // Just navigate to products page for editing
            window.location.href = `products.php?id=${productId}`;
        }

        // Pagination
        function renderPagination(pag) {
            const container = document.getElementById('paginationContainer');
            let html = '';

            if (pag.pages <= 1) {
                container.innerHTML = '';
                return;
            }

            if (currentPage > 1) {
                html += `<button class="pagination-btn" onclick="goToPage(${currentPage - 1})">← Previous</button>`;
            }

            for (let i = 1; i <= pag.pages; i++) {
                if (i === currentPage) {
                    html += `<button class="pagination-btn active">${i}</button>`;
                } else {
                    html += `<button class="pagination-btn" onclick="goToPage(${i})">${i}</button>`;
                }
            }

            if (currentPage < pag.pages) {
                html += `<button class="pagination-btn" onclick="goToPage(${currentPage + 1})">Next →</button>`;
            }

            container.innerHTML = html;
        }

        function goToPage(page) {
            currentPage = page;
            loadProducts();
            window.scrollTo(0, 0);
        }

        // Add Product Modal
        function openAddProductModal() {
            document.getElementById('addProductModal').classList.add('show');
            loadUnassignedProducts();
        }

        function closeAddProductModal() {
            document.getElementById('addProductModal').classList.remove('show');
        }

        function loadUnassignedProducts() {
            const search = document.getElementById('productSearchModal').value;

            const params = new URLSearchParams({
                action: 'getunassignedproducts',
                category_id: categoryId,
                search: search
            });

            fetch(`${API_BASE}?${params}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        renderUnassignedProducts(data.data);
                    }
                })
                .catch(err => console.error(err));
        }

        function renderUnassignedProducts(products) {
            const container = document.getElementById('productsListContainer');

            if (products.length === 0) {
                container.innerHTML = '<p class="text-center" style="padding: 20px;">No products available</p>';
                return;
            }

            container.innerHTML = products.map(prod => `
                <div class="product-item">
                    <div class="product-item-info">
                        <div class="product-item-name">${escapeHtml(prod.name)}</div>
                        <div class="product-item-price">₱${parseFloat(prod.price).toFixed(2)} • Stock: ${prod.stock}</div>
                    </div>
                    <button class="add-product-btn" onclick="assignProduct(${prod.id})">
                        <i class="fa-solid fa-plus"></i> Add
                    </button>
                </div>
            `).join('');
        }

        // Assign Product
        function assignProduct(productId) {
            const formData = new FormData();
            formData.append('action', 'assignproduct');
            formData.append('category_id', categoryId);
            formData.append('product_id', productId);

            fetch(API_BASE, {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        closeAddProductModal();
                        currentPage = 1;
                        loadProducts();
                        loadCategoryInfo();
                    } else {
                        showError(data.message);
                    }
                })
                .catch(err => showError('Failed to assign product'));
        }

        // Helpers
        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, m => map[m]);
        }

        function showEmptyProducts() {
            document.getElementById('emptyProducts').style.display = 'block';
            document.querySelector('.table-container').style.display = 'none';
            document.getElementById('paginationContainer').innerHTML = '';
        }

        function hideEmptyProducts() {
            document.getElementById('emptyProducts').style.display = 'none';
            document.querySelector('.table-container').style.display = 'block';
        }

        function showError(msg) {
            console.error(msg);
            alert(msg);
        }
    </script>

</body>

</html>