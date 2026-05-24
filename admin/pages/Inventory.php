<?php
//materials_inventory.php
include 'auth_check.php';
include '../includes/header.php';
?>

<link rel="stylesheet" href="../../assets/css/admin-site/admin_inventory.css">

<div class="admin-wrapper">
    <?php
    $current_page = 'Materials Inventory';
    include 'admin_sidebar.php';
    ?>

    <main class="main-content">
        <?php include 'admin_page_header.php'; ?>

        <section class="content-body">
            <!-- Page Header -->
            <div class="mat-page-header">
                <div class="mat-header-content">
                    <div>
                        <h1 class="mat-title">Materials Inventory</h1>
                        <p class="mat-subtitle">Manage stock levels, locations, and track history</p>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="mat-stats-grid">
                <div class="mat-stat-card mat-stat-total">
                    <div class="mat-stat-icon">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </div>
                    <div class="mat-stat-content">
                        <p class="mat-stat-label">Total Stock</p>
                        <h3 class="mat-stat-value" id="matTotalStockValue">—</h3>
                    </div>
                </div>

                <div class="mat-stat-card mat-stat-in-stock">
                    <div class="mat-stat-icon">
                        <i class="fa-solid fa-check-circle"></i>
                    </div>
                    <div class="mat-stat-content">
                        <p class="mat-stat-label">In Stock</p>
                        <h3 class="mat-stat-value" id="matInStockValue">—</h3>
                    </div>
                </div>

                <div class="mat-stat-card mat-stat-low">
                    <div class="mat-stat-icon">
                        <i class="fa-solid fa-exclamation-triangle"></i>
                    </div>
                    <div class="mat-stat-content">
                        <p class="mat-stat-label">Low Stock</p>
                        <h3 class="mat-stat-value" id="matLowStockValue">—</h3>
                    </div>
                </div>

                <div class="mat-stat-card mat-stat-out">
                    <div class="mat-stat-icon">
                        <i class="fa-solid fa-ban"></i>
                    </div>
                    <div class="mat-stat-content">
                        <p class="mat-stat-label">Out of Stock</p>
                        <h3 class="mat-stat-value" id="matOutOfStockValue">—</h3>
                    </div>
                </div>
            </div>

            <!-- Stock Alerts -->
            <div class="mat-alerts-section" id="matAlertsBox" style="display:none;">
                <div class="mat-alerts-header">
                    <div class="mat-alerts-title">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <h3>Stock Alerts</h3>
                    </div>
                    <button class="mat-alerts-close" onclick="document.getElementById('matAlertsBox').style.display='none';">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                <div class="mat-alerts-list" id="matAlertsContainer"></div>
            </div>

            <!-- Materials Table -->
            <div class="mat-card">
                <div class="mat-card-header">
                    <div>
                        <h2 class="mat-card-title">All Materials</h2>
                        <p class="mat-card-subtitle">View and manage material stock</p>
                    </div>
                    <div class="mat-table-controls">
                        <div class="mat-search-box">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input
                                type="text"
                                id="matSearchInput"
                                placeholder="Search by material name…"
                                oninput="matDebouncedReload()"
                                class="mat-search-input">
                        </div>
                        <select id="matStatusFilter" onchange="matReloadMaterials()" class="mat-filter-select">
                            <option value="">All Status</option>
                            <option value="in_stock">In Stock</option>
                            <option value="low_stock">Low Stock</option>
                            <option value="out_of_stock">Out of Stock</option>
                        </select>
                        <select id="matSortSelect" onchange="matReloadMaterials()" class="mat-filter-select">
                            <option value="name_asc">Sort by Name ↑</option>
                            <option value="name_desc">Sort by Name ↓</option>
                            <option value="stock_asc">Stock Low to High</option>
                            <option value="stock_desc">Stock High to Low</option>
                        </select>
                    </div>
                </div>

                <div class="mat-table-wrapper">
                    <table class="mat-table">
                        <thead>
                            <tr>
                                <th class="mat-col-name">Material Name</th>
                                <th class="mat-col-type">Type</th>
                                <th class="mat-col-num">Shop</th>
                                <th class="mat-col-num">PH</th>
                                <th class="mat-col-num">Total</th>
                                <th class="mat-col-cost">Unit Cost</th>
                                <th class="mat-col-status">Status</th>
                                <th class="mat-col-action">Action</th>
                            </tr>
                        </thead>
                        <tbody id="materialsTableBody">
                            <tr>
                                <td colspan="8" class="mat-loading-cell">Loading materials…</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mat-pagination">
                    <span class="mat-page-info" id="matPageInfo"></span>
                    <div class="mat-pager" id="matPager"></div>
                </div>
            </div>

            <!-- Activity Log Table -->
            <div class="mat-card" style="margin-top: 32px;" style="display: none;">
                <div class="mat-card-header">
                    <div>
                        <h2 class="mat-card-title">Stock Activity</h2>
                        <p class="mat-card-subtitle">Complete history of all stock changes</p>
                    </div>
                    <div class="mat-table-controls">
                        <select id="matLogTypeFilter" onchange="matReloadLogs()" class="mat-filter-select">
                            <option value="">All Actions</option>
                            <option value="add">Add Stock</option>
                            <option value="subtract">Remove Stock</option>
                            <option value="order">Order</option>
                            <option value="return">Return</option>
                            <option value="adjust">Adjust</option>
                        </select>
                        <select id="matLogLocationFilter" onchange="matReloadLogs()" class="mat-filter-select">
                            <option value="">All Locations</option>
                            <option value="shop_stock">Shop Stock</option>
                            <option value="ph_stock">PH Stock</option>
                            <option value="total_stock">Total Stock</option>
                        </select>
                        <input
                            type="date"
                            id="matLogDateFrom"
                            onchange="matReloadLogs()"
                            class="mat-filter-select">
                        <input
                            type="date"
                            id="matLogDateTo"
                            onchange="matReloadLogs()"
                            class="mat-filter-select">
                    </div>
                </div>

                <div class="mat-table-wrapper">
                    <table class="mat-table">
                        <thead>
                            <tr>
                                <th class="mat-col-name">Material</th>
                                <th class="mat-col-type">Action</th>
                                <th class="mat-col-type">Location</th>
                                <th class="mat-col-num">Change</th>
                                <th class="mat-col-range">Stock Range</th>
                                <th class="mat-col-admin">Admin</th>
                                <th class="mat-col-note">Note</th>
                                <th class="mat-col-date">Date</th>
                            </tr>
                        </thead>
                        <tbody id="materialsLogsTableBody">
                            <tr>
                                <td colspan="8" class="mat-loading-cell">Loading activity…</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mat-pagination">
                    <span class="mat-page-info" id="matLogsPageInfo"></span>
                    <div class="mat-pager" id="matLogsPager"></div>
                </div>
            </div>

        </section>
    </main>
</div>

<!-- ══════════════════════════════════════════════════════════════
     MATERIAL STOCK MODAL
     - Modern, minimal design
     - All IDs prefixed "mat"
     - Proper accessibility
     ══════════════════════════════════════════════════════════════ -->
<div id="matStockModal" class="mat-modal-overlay" aria-hidden="true">
    <div class="mat-modal-content" role="dialog" aria-labelledby="matModalTitle">

        <!-- Header -->
        <div class="mat-modal-header">
            <div>
                <h3 id="matModalTitle" class="mat-modal-title">Update Material Stock</h3>
                <p id="matModalMaterialName" class="mat-modal-subtitle"></p>
            </div>
            <button
                id="matModalCloseBtn"
                type="button"
                class="mat-modal-close"
                aria-label="Close dialog">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="mat-modal-body">
            <p class="mat-modal-meta">
                Current Stock: <strong id="matModalCurrentStock">—</strong>
            </p>

            <!-- Location Select -->
            <div class="mat-form-group">
                <label for="matLocationSelect" class="mat-form-label">Stock Location</label>
                <select id="matLocationSelect" class="mat-form-select">
                    <option value="shop_stock">Shop Stock</option>
                    <option value="ph_stock">PH Stock</option>
                    <option value="total_stock">Total Stock</option>
                </select>
            </div>

            <!-- Action Select -->
            <div class="mat-form-group">
                <label for="matActionSelect" class="mat-form-label">Action</label>
                <select id="matActionSelect" class="mat-form-select">
                    <option value="add">Add Stock</option>
                    <option value="subtract">Remove Stock</option>
                    <option value="adjust">Set Level</option>
                </select>
            </div>

            <!-- Quantity Input -->
            <div class="mat-form-group">
                <label for="matQtyInput" class="mat-form-label">Quantity</label>
                <input
                    type="number"
                    id="matQtyInput"
                    min="0"
                    placeholder="Enter quantity…"
                    class="mat-form-input">
            </div>

            <!-- Preview -->
            <div id="matPreviewText" class="mat-modal-preview"></div>

            <!-- Error -->
            <div id="matErrorText" class="mat-modal-error"></div>
        </div>

        <!-- Footer -->
        <div class="mat-modal-footer">
            <button id="matCancelBtn" type="button" class="mat-btn mat-btn-secondary">
                Cancel
            </button>
            <button id="matConfirmBtn" type="button" class="mat-btn mat-btn-primary">
                <i class="fa-solid fa-check"></i>
                <span>Update Stock</span>
            </button>
        </div>

    </div>
</div>

<!-- Materials JS loaded LAST — all DOM elements guaranteed to exist -->
<script src="../../assets/js/admin-site-functions/admin_materials.js"></script>