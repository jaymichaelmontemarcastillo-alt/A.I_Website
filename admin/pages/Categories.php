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
                <div class="page-header">
                    <div>
                        <h1 class="page-title">Categories</h1>
                        <p class="page-subtitle">Manage product categories</p>
                    </div>
                    <button class="btn btn-primary" id="addCategoryBtn">
                        <i class="fa-solid fa-plus"></i> Add Category
                    </button>
                </div>

                <!-- Filters -->
                <div class="filters-bar">
                    <input
                        type="text"
                        id="searchInput"
                        placeholder="Search categories..."
                        class="search-input">
                    <select id="statusFilter" class="filter-select">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <!-- Categories Table -->
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Category Name</th>
                                <th>Description</th>
                                <th>Products</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="categoriesTableBody">
                            <tr class="loading-row">
                                <td colspan="7" class="text-center">Loading categories...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div id="paginationContainer" class="pagination-container"></div>

                <!-- Empty State -->
                <div id="emptyState" class="empty-state" style="display: none;">
                    <i class="fa-solid fa-inbox"></i>
                    <h3>No Categories Found</h3>
                    <p>Get started by creating your first category</p>
                </div>
            </section>
        </main>
    </div>

    <!-- ADD/EDIT CATEGORY MODAL -->
    <div id="categoryModal" class="category-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Add Category</h3>
                <button class="modal-close" id="closeModalBtn">&times;</button>
            </div>
            <form id="categoryForm">
                <input type="hidden" id="categoryId" value="">

                <div class="form-group">
                    <label for="categoryName">Category Name *</label>
                    <input
                        type="text"
                        id="categoryName"
                        placeholder="e.g., Birthday, Holiday"
                        required>
                    <small id="nameError" class="error-text"></small>
                </div>

                <div class="form-group">
                    <label for="categoryDescription">Description</label>
                    <textarea
                        id="categoryDescription"
                        placeholder="Describe this category..."
                        rows="4"></textarea>
                </div>

                <div class="form-group" id="statusGroup" style="display: none;">
                    <label for="categoryStatus">Status</label>
                    <select id="categoryStatus">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" id="cancelModalBtn">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Category</button>
                </div>
            </form>
        </div>
    </div>

    <!-- DELETE CONFIRMATION MODAL -->
    <div id="deleteModal" class="category-modal">
        <div class="modal-content modal-sm">
            <div class="modal-header">
                <h3>Delete Category</h3>
                <button class="modal-close" id="closeDeleteBtn">&times;</button>
            </div>
            <div id="deleteModalBody">
                <p>Are you sure you want to delete this category?</p>
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" id="cancelDeleteBtn">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>

    <script src="../../assets/js/admin-site-functions/category-functions.js"></script>

</body>

</html>