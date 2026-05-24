<?php
//materials_inventory.php
include 'auth_check.php';
include '../includes/header.php';
?>

<link rel="stylesheet" href="../../assets/css/admin-site/admin_materials_inventory.css">

<div class="admin-wrapper">
    <?php
    $current_page = 'Materials Inventory';
    include 'admin_sidebar.php';
    ?>

    <main class="main-content">
        <?php include 'admin_page_header.php'; ?>

        <section class="content-body">

            <div class="mat-page-header">
                <div>
                    <h1 class="page-title">Materials Inventory</h1>
                    <p class="page-subtitle">Stock levels, locations, and history</p>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue"><i class="fa-solid fa-box"></i></div>
                    <div>
                        <p class="stat-label">Total Stock</p>
                        <h3 class="stat-val" id="matTotalStockValue">—</h3>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green"><i class="fa-solid fa-circle-check"></i></div>
                    <div>
                        <p class="stat-label">In Stock</p>
                        <h3 class="stat-val" id="matInStockValue">—</h3>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon yellow"><i class="fa-solid fa-arrow-trend-down"></i></div>
                    <div>
                        <p class="stat-label">Low Stock</p>
                        <h3 class="stat-val" id="matLowStockValue">—</h3>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon red"><i class="fa-solid fa-triangle-exclamation"></i></div>
                    <div>
                        <p class="stat-label">Out of Stock</p>
                        <h3 class="stat-val" id="matOutOfStockValue">—</h3>
                    </div>
                </div>
            </div>

            <div class="alerts-box" id="matAlertsBox" style="display:none;">
                <h3><i class="fa-solid fa-triangle-exclamation"></i> Stock Alerts</h3>
                <div id="matAlertsContainer"></div>
            </div>

            <div class="table-container">
                <div class="table-header">
                    <h3>All Materials</h3>
                    <div class="table-controls">
                        <div class="search-wrap">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" id="matSearchInput" placeholder="Search material…" oninput="matDebouncedReload()">
                        </div>
                        <select id="matStatusFilter" onchange="matReloadMaterials()" class="filter-select">
                            <option value="">All Status</option>
                            <option value="in_stock">In Stock</option>
                            <option value="low_stock">Low Stock</option>
                            <option value="out_of_stock">Out of Stock</option>
                        </select>
                        <select id="matSortSelect" onchange="matReloadMaterials()" class="filter-select">
                            <option value="stock_asc">Stock ↑</option>
                            <option value="stock_desc">Stock ↓</option>
                            <option value="name_asc">Name A–Z</option>
                            <option value="name_desc">Name Z–A</option>
                        </select>
                    </div>
                </div>
                <div class="table-scroll">
                    <table class="mat-table">
                        <thead>
                            <tr>
                                <th>Material</th>
                                <th>Type</th>
                                <th>Shop Stock</th>
                                <th>PH Stock</th>
                                <th>Total Stock</th>
                                <th>Unit Cost</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="materialsTableBody">
                            <tr>
                                <td colspan="8" class="loading-cell">Loading materials…</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                    <span class="page-info" id="matPageInfo"></span>
                    <div class="pager" id="matPager"></div>
                </div>
            </div>

            <div class="table-container" style="margin-top:24px;">
                <div class="table-header">
                    <h3>Materials Log</h3>
                    <div class="table-controls">
                        <select id="matLogTypeFilter" onchange="matReloadLogs()" class="filter-select">
                            <option value="">All Types</option>
                            <option value="add">Add</option>
                            <option value="subtract">Subtract</option>
                            <option value="order">Order</option>
                            <option value="return">Return</option>
                            <option value="adjust">Adjust</option>
                        </select>
                        <select id="matLogLocationFilter" onchange="matReloadLogs()" class="filter-select">
                            <option value="">All Locations</option>
                            <option value="shop_stock">Shop Stock</option>
                            <option value="ph_stock">PH Stock</option>
                            <option value="total_stock">Total Stock</option>
                        </select>
                        <input type="date" id="matLogDateFrom" onchange="matReloadLogs()" class="filter-select" style="padding:8px 10px;">
                        <input type="date" id="matLogDateTo" onchange="matReloadLogs()" class="filter-select" style="padding:8px 10px;">
                    </div>
                </div>
                <div class="table-scroll">
                    <table class="mat-table">
                        <thead>
                            <tr>
                                <th>Material</th>
                                <th>Type</th>
                                <th>Location</th>
                                <th>Qty Δ</th>
                                <th>Before → After</th>
                                <th>Admin</th>
                                <th>Note</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody id="materialsLogsTableBody">
                            <tr>
                                <td colspan="9" class="loading-cell">Loading logs…</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                    <span class="page-info" id="matLogsPageInfo"></span>
                    <div class="pager" id="matLogsPager"></div>
                </div>
            </div>

        </section>
    </main>
</div>

<!-- ══════════════════════════════════════════════════════════
     MATERIAL STOCK MODAL
     - Placed AFTER admin-wrapper (escapes overflow:hidden)
     - All IDs prefixed "mat" — prevents collision with
       global openModal() or invStockModal
     - Inline styles used for robustness
     ══════════════════════════════════════════════════════════ -->
<div id="matStockModal"
    style="display:none;
            position:fixed;
            inset:0;
            background:rgba(0,0,0,0.55);
            z-index:99999;
            align-items:center;
            justify-content:center;
            padding:16px;
            box-sizing:border-box;">

    <div style="background:#fff;
                border-radius:12px;
                width:100%;
                max-width:480px;
                box-shadow:0 20px 60px rgba(0,0,0,0.25);
                overflow:hidden;
                display:flex;
                flex-direction:column;">

        <!-- Header -->
        <div style="display:flex; align-items:center; justify-content:space-between;
                    padding:18px 20px; border-bottom:1px solid #e5e7eb; flex-shrink:0;">
            <h3 id="matModalTitle" style="margin:0; font-size:1rem; font-weight:700; color:#111;">Update Material Stock</h3>
            <button id="matModalCloseBtn" type="button"
                style="background:none; border:none; font-size:1.5rem; cursor:pointer;
                           color:#6b7280; line-height:1; padding:2px 6px; border-radius:4px;">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div style="padding:20px; flex:1;">

            <p style="margin:0 0 2px; font-size:0.78rem; color:#6b7280; text-transform:uppercase; letter-spacing:0.05em;">Material</p>
            <p id="matModalMaterialName" style="margin:0 0 14px; font-weight:700; font-size:1rem; color:#111;"></p>

            <p style="margin:0 0 18px; font-size:0.9rem; color:#374151;">
                Current Stock: <strong id="matModalCurrentStock" style="color:#111;"></strong>
            </p>

            <!-- Location -->
            <div style="margin-bottom:14px;">
                <label for="matLocationSelect"
                    style="display:block; font-size:0.78rem; font-weight:600;
                              margin-bottom:6px; text-transform:uppercase; letter-spacing:0.04em; color:#111;">
                    Location
                </label>
                <select id="matLocationSelect"
                    style="display:block; width:100%; padding:10px 12px;
                               border:1px solid #d1d5db; border-radius:8px;
                               font-size:0.9rem; background:#f9fafb; color:#111;
                               box-sizing:border-box; outline:none;
                               margin:0; font-family:inherit;">
                    <option value="shop_stock">Shop Stock</option>
                    <option value="ph_stock">PH Stock</option>
                    <option value="total_stock">Total Stock</option>
                </select>
            </div>

            <!-- Action -->
            <div style="margin-bottom:14px;">
                <label for="matActionSelect"
                    style="display:block; font-size:0.78rem; font-weight:600;
                              margin-bottom:6px; text-transform:uppercase; letter-spacing:0.04em; color:#111;">
                    Action
                </label>
                <select id="matActionSelect"
                    style="display:block; width:100%; padding:10px 12px;
                               border:1px solid #d1d5db; border-radius:8px;
                               font-size:0.9rem; background:#f9fafb; color:#111;
                               box-sizing:border-box; outline:none;
                               margin:0; font-family:inherit;">
                    <option value="add">Add Stock</option>
                    <option value="subtract">Remove Stock</option>
                    <option value="adjust">Set Level</option>
                </select>
            </div>

            <!-- Quantity -->
            <div style="margin-bottom:14px;">
                <label for="matQtyInput"
                    style="display:block; font-size:0.78rem; font-weight:600;
                              margin-bottom:6px; text-transform:uppercase; letter-spacing:0.04em; color:#111;">
                    Quantity
                </label>
                <input type="number" id="matQtyInput" min="0" placeholder="Enter quantity…"
                    style="display:block; width:100%; padding:10px 12px;
                              border:1px solid #d1d5db; border-radius:8px;
                              font-size:0.9rem; background:#f9fafb; color:#111;
                              box-sizing:border-box; outline:none;
                              margin:0; font-family:inherit;">
            </div>

            <!-- Preview -->
            <p id="matPreviewText"
                style="margin:0 0 6px; font-weight:700; color:#1d4ed8;
                      font-size:0.9rem; min-height:20px;"></p>

            <!-- Error -->
            <p id="matErrorText"
                style="margin:0; color:#dc2626; font-size:0.82rem; min-height:18px;"></p>
        </div>

        <!-- Footer -->
        <div style="display:flex; justify-content:flex-end; gap:10px;
                    padding:14px 20px; border-top:1px solid #e5e7eb;
                    background:#f9fafb; flex-shrink:0;">
            <button id="matCancelBtn" type="button"
                style="padding:9px 18px; border:1px solid #d1d5db; border-radius:8px;
                           background:#fff; color:#374151; font-size:0.875rem;
                           font-weight:600; cursor:pointer; font-family:inherit;">
                Cancel
            </button>
            <button id="matConfirmBtn" type="button"
                style="padding:9px 18px; border:none; border-radius:8px;
                           background:#2563eb; color:#fff; font-size:0.875rem;
                           font-weight:600; cursor:pointer; display:inline-flex;
                           align-items:center; gap:7px; font-family:inherit;">
                <i class="fa-solid fa-check"></i> Update
            </button>
        </div>

    </div>
</div>

<!-- Materials JS loaded LAST — all DOM elements guaranteed to exist -->
<script src="../../assets/js/admin-site-functions/admin_materials.js"></script>