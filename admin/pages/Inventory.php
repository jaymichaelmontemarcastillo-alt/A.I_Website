<?php
//materials_inventory.php
include 'auth_check.php';
include '../includes/header.php';
?>

<link rel="stylesheet" href="../../assets/css/admin-site/admin_inventory.css">
<!-- Font Awesome for icons (if not already included) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="admin-wrapper">
    <?php
    $current_page = 'Inventory';
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
                <div style="display: flex; gap: 12px;">
                    <button class="btn-add-item" onclick="matOpenAddItemModal()" style="background: #10b981; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600;">
                        <i class="fa-solid fa-plus"></i> Add New Item
                    </button>
                    <button class="btn-import" onclick="matOpenImportModal()" style="background: var(--surface); border: 1px solid var(--border); padding: 10px 18px; border-radius: 8px; cursor: pointer;">
                        <i class="fa-solid fa-upload"></i> Import
                    </button>
                    <button class="btn-export" onclick="matExportData()" style="background: var(--surface); border: 1px solid var(--border); padding: 10px 18px; border-radius: 8px; cursor: pointer;">
                        <i class="fa-solid fa-download"></i> Export
                    </button>
                    <button class="btn-primary" onclick="matOpenAuditModal()" style="background: #2563eb; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600;">
                        <i class="fa-solid fa-clipboard-list"></i> Create Audit / BOM
                    </button>
                </div>
            </div>

            <!-- Import CSV Modal -->
            <div id="matImportModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.55); z-index:99999; align-items:center; justify-content:center;">
                <div style="background:#fff; border-radius:10px; max-width:500px; width:90%; padding:24px;">
                    <h3 style="margin:0 0 16px 0;"><i class="fa-solid fa-file-csv"></i> Import Materials (CSV)</h3>
                    <p style="margin-bottom:16px; color:#666;">Upload CSV file with columns: Type, Materials, Shop, PH, Total On Hand, Total Cost, Quantity per pack, Unit Cost, Remarks</p>
                    <button onclick="matDownloadSampleCSV()" style="margin-bottom:16px; padding:6px 12px; background:#f3f4f6; border:1px solid #ddd; border-radius:5px; cursor:pointer;">
                        <i class="fa-solid fa-download"></i> Download Sample CSV
                    </button>
                    <input type="file" id="importCSVFile" accept=".csv" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:5px; margin-bottom:16px;">
                    <div style="display:flex; gap:10px; justify-content:flex-end;">
                        <button onclick="matCloseImportModal()" style="padding:8px 16px; border:1px solid #ddd; border-radius:5px; background:#fff; cursor:pointer;">Cancel</button>
                        <button onclick="matProcessImport()" style="padding:8px 20px; border:none; border-radius:5px; background:#2563eb; color:#fff; cursor:pointer;"><i class="fa-solid fa-upload"></i> Import</button>
                    </div>
                </div>
            </div>

            <!-- ═══════════════════════════════════════════════════════════════
                 ADD NEW ITEM MODAL
                 ═══════════════════════════════════════════════════════════════ -->
            <div id="matAddItemModal"
                style="display:none;
                        position:fixed;
                        inset:0;
                        background:rgba(0,0,0,0.55);
                        z-index:100000;
                        align-items:center;
                        justify-content:center;
                        padding:16px;
                        box-sizing:border-box;">

                <div style="background:#fff;
                            border-radius:12px;
                            width:100%;
                            max-width:500px;
                            box-shadow:0 20px 60px rgba(0,0,0,0.25);
                            overflow:hidden;
                            display:flex;
                            flex-direction:column;">

                    <!-- Header -->
                    <div style="display:flex; align-items:center; justify-content:space-between;
                                padding:18px 20px; border-bottom:1px solid #e5e7eb; flex-shrink:0;">
                        <h3 style="margin:0; font-size:1.1rem; font-weight:700; color:#111;">
                            <i class="fa-solid fa-plus-circle" style="color:#10b981;"></i> Add New Material
                        </h3>
                        <button id="matAddItemCloseBtn" type="button"
                            style="background:none; border:none; font-size:1.5rem; cursor:pointer;
                                   color:#6b7280; line-height:1; padding:2px 6px; border-radius:4px;">
                            &times;
                        </button>
                    </div>

                    <!-- Body -->
                    <div style="padding:20px; flex:1;">

                        <!-- Material Name -->
                        <div style="margin-bottom:16px;">
                            <label for="matNewMaterialName" style="display:block; font-size:0.78rem; font-weight:600;
                                      margin-bottom:6px; text-transform:uppercase; letter-spacing:0.04em; color:#111;">
                                Material Name <span style="color:#ef4444;">*</span>
                            </label>
                            <input type="text" id="matNewMaterialName" placeholder="e.g., 8.5 x 11 Bond Paper"
                                style="display:block; width:100%; padding:10px 12px;
                                       border:1px solid #d1d5db; border-radius:8px;
                                       font-size:0.9rem; background:#f9fafb; color:#111;
                                       box-sizing:border-box; outline:none;">
                        </div>

                        <!-- Type -->
                        <div style="margin-bottom:16px;">
                            <label for="matNewType" style="display:block; font-size:0.78rem; font-weight:600;
                                      margin-bottom:6px; text-transform:uppercase; letter-spacing:0.04em; color:#111;">
                                Type <span style="color:#ef4444;">*</span>
                            </label>
                            <select id="matNewType"
                                style="display:block; width:100%; padding:10px 12px;
                                       border:1px solid #d1d5db; border-radius:8px;
                                       font-size:0.9rem; background:#f9fafb; color:#111;
                                       box-sizing:border-box; outline:none;">
                                <option value="">Select Type</option>
                                <option value="Paper">Paper</option>
                                <option value="Ink">Ink</option>
                                <option value="Binding">Binding</option>
                                <option value="Lamination">Lamination</option>
                                <option value="Cutting">Cutting</option>
                                <option value="Packaging">Packaging</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <!-- Shop Stock -->
                        <div style="margin-bottom:16px;">
                            <label for="matNewShopStock" style="display:block; font-size:0.78rem; font-weight:600;
                                      margin-bottom:6px; text-transform:uppercase; letter-spacing:0.04em; color:#111;">
                                Shop Stock
                            </label>
                            <input type="number" id="matNewShopStock" value="0" min="0"
                                style="display:block; width:100%; padding:10px 12px;
                                       border:1px solid #d1d5db; border-radius:8px;
                                       font-size:0.9rem; background:#f9fafb; color:#111;
                                       box-sizing:border-box; outline:none;">
                        </div>

                        <!-- PH Stock -->
                        <div style="margin-bottom:16px;">
                            <label for="matNewPhStock" style="display:block; font-size:0.78rem; font-weight:600;
                                      margin-bottom:6px; text-transform:uppercase; letter-spacing:0.04em; color:#111;">
                                PH Stock
                            </label>
                            <input type="number" id="matNewPhStock" value="0" min="0"
                                style="display:block; width:100%; padding:10px 12px;
                                       border:1px solid #d1d5db; border-radius:8px;
                                       font-size:0.9rem; background:#f9fafb; color:#111;
                                       box-sizing:border-box; outline:none;">
                        </div>

                        <!-- Unit Cost -->
                        <div style="margin-bottom:16px;">
                            <label for="matNewUnitCost" style="display:block; font-size:0.78rem; font-weight:600;
                                      margin-bottom:6px; text-transform:uppercase; letter-spacing:0.04em; color:#111;">
                                Unit Cost (₱)
                            </label>
                            <input type="number" id="matNewUnitCost" value="0" min="0" step="0.01"
                                style="display:block; width:100%; padding:10px 12px;
                                       border:1px solid #d1d5db; border-radius:8px;
                                       font-size:0.9rem; background:#f9fafb; color:#111;
                                       box-sizing:border-box; outline:none;">
                        </div>

                        <!-- Preview Total Stock -->
                        <div id="matNewTotalPreview" style="margin-top:12px; padding:10px; background:#f0fdf4; border-radius:8px; text-align:center;">
                            <span style="font-size:0.85rem; color:#166534;">Total Stock: <strong id="matNewTotalValue">0</strong></span>
                        </div>

                        <!-- Error Message -->
                        <div id="matAddItemError" style="margin-top:12px; color:#dc2626; font-size:0.82rem; min-height:20px;"></div>
                    </div>

                    <!-- Footer -->
                    <div style="display:flex; justify-content:flex-end; gap:10px;
                                padding:14px 20px; border-top:1px solid #e5e7eb;
                                background:#f9fafb; flex-shrink:0;">
                        <button id="matAddItemCancelBtn" type="button"
                            style="padding:9px 18px; border:1px solid #d1d5db; border-radius:8px;
                                   background:#fff; color:#374151; font-size:0.875rem;
                                   font-weight:600; cursor:pointer;">
                            Cancel
                        </button>
                        <button id="matAddItemConfirmBtn" type="button"
                            style="padding:9px 18px; border:none; border-radius:8px;
                                   background:#10b981; color:#fff; font-size:0.875rem;
                                   font-weight:600; cursor:pointer; display:inline-flex;
                                   align-items:center; gap:7px;">
                            <i class="fa-solid fa-save"></i> Save Item
                        </button>
                    </div>

                </div>
            </div>

            <!-- ═══════════════════════════════════════════════════════════════
                 MATERIAL STOCK MODAL (Update existing stock)
                 ═══════════════════════════════════════════════════════════════ -->
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
                                           box-sizing:border-box; outline:none;">
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
                                           box-sizing:border-box; outline:none;">
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
                                          box-sizing:border-box; outline:none;">
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
                                       font-weight:600; cursor:pointer;">
                            Cancel
                        </button>
                        <button id="matConfirmBtn" type="button"
                            style="padding:9px 18px; border:none; border-radius:8px;
                                       background:#2563eb; color:#fff; font-size:0.875rem;
                                       font-weight:600; cursor:pointer; display:inline-flex;
                                       align-items:center; gap:7px;">
                            <i class="fa-solid fa-check"></i> Update
                        </button>
                    </div>

                </div>
            </div>

            <!-- Stats Grid -->
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
            <!--
            <div class="alerts-box" id="matAlertsBox" style="display:none;">
                <h3><i class="fa-solid fa-triangle-exclamation"></i> Stock Alerts</h3>
                <div id="matAlertsContainer"></div>
            </div>
-->
            <!-- Materials Table -->
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

            <!-- Materials Log Table -->
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
                                <td colspan="8" class="loading-cell">Loading logs…</td>
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
        <!-- ═══════════════════════════════════════════════════════════════
     EDIT ITEM MODAL (Update price, type, stock, etc.)
     ═══════════════════════════════════════════════════════════════ -->
        <div id="matEditItemModal"
            style="display:none;
            position:fixed;
            inset:0;
            background:rgba(0,0,0,0.55);
            z-index:100001;
            align-items:center;
            justify-content:center;
            padding:16px;
            box-sizing:border-box;">

            <div style="background:#fff;
                border-radius:12px;
                width:100%;
                max-width:500px;
                box-shadow:0 20px 60px rgba(0,0,0,0.25);
                overflow:hidden;
                display:flex;
                flex-direction:column;">

                <!-- Header -->
                <div style="display:flex; align-items:center; justify-content:space-between;
                    padding:18px 20px; border-bottom:1px solid #e5e7eb; flex-shrink:0;">
                    <h3 style="margin:0; font-size:1.1rem; font-weight:700; color:#111;">
                        <i class="fa-solid fa-pen-to-square" style="color:#2563eb;"></i> Edit Material
                    </h3>
                    <button id="matEditItemCloseBtn" type="button"
                        style="background:none; border:none; font-size:1.5rem; cursor:pointer;
                       color:#6b7280; line-height:1; padding:2px 6px; border-radius:4px;">
                        &times;
                    </button>
                </div>

                <!-- Body -->
                <div style="padding:20px; flex:1;">

                    <input type="hidden" id="matEditItemId">

                    <!-- Material Name -->
                    <div style="margin-bottom:16px;">
                        <label for="matEditMaterialName" style="display:block; font-size:0.78rem; font-weight:600;
                          margin-bottom:6px; text-transform:uppercase; letter-spacing:0.04em; color:#111;">
                            Material Name <span style="color:#ef4444;">*</span>
                        </label>
                        <input type="text" id="matEditMaterialName" placeholder="e.g., 8.5 x 11 Bond Paper"
                            style="display:block; width:100%; padding:10px 12px;
                           border:1px solid #d1d5db; border-radius:8px;
                           font-size:0.9rem; background:#f9fafb; color:#111;
                           box-sizing:border-box; outline:none;">
                    </div>

                    <!-- Type -->
                    <div style="margin-bottom:16px;">
                        <label for="matEditType" style="display:block; font-size:0.78rem; font-weight:600;
                          margin-bottom:6px; text-transform:uppercase; letter-spacing:0.04em; color:#111;">
                            Type <span style="color:#ef4444;">*</span>
                        </label>
                        <select id="matEditType"
                            style="display:block; width:100%; padding:10px 12px;
                           border:1px solid #d1d5db; border-radius:8px;
                           font-size:0.9rem; background:#f9fafb; color:#111;
                           box-sizing:border-box; outline:none;">
                            <option value="">Select Type</option>
                            <option value="Paper">Paper</option>
                            <option value="Ink">Ink</option>
                            <option value="Binding">Binding</option>
                            <option value="Lamination">Lamination</option>
                            <option value="Cutting">Cutting</option>
                            <option value="Packaging">Packaging</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <!-- Shop Stock -->
                    <div style="margin-bottom:16px;">
                        <label for="matEditShopStock" style="display:block; font-size:0.78rem; font-weight:600;
                          margin-bottom:6px; text-transform:uppercase; letter-spacing:0.04em; color:#111;">
                            Shop Stock
                        </label>
                        <input type="number" id="matEditShopStock" value="0" min="0"
                            style="display:block; width:100%; padding:10px 12px;
                           border:1px solid #d1d5db; border-radius:8px;
                           font-size:0.9rem; background:#f9fafb; color:#111;
                           box-sizing:border-box; outline:none;">
                    </div>

                    <!-- PH Stock -->
                    <div style="margin-bottom:16px;">
                        <label for="matEditPhStock" style="display:block; font-size:0.78rem; font-weight:600;
                          margin-bottom:6px; text-transform:uppercase; letter-spacing:0.04em; color:#111;">
                            PH Stock
                        </label>
                        <input type="number" id="matEditPhStock" value="0" min="0"
                            style="display:block; width:100%; padding:10px 12px;
                           border:1px solid #d1d5db; border-radius:8px;
                           font-size:0.9rem; background:#f9fafb; color:#111;
                           box-sizing:border-box; outline:none;">
                    </div>

                    <!-- Unit Cost -->
                    <div style="margin-bottom:16px;">
                        <label for="matEditUnitCost" style="display:block; font-size:0.78rem; font-weight:600;
                          margin-bottom:6px; text-transform:uppercase; letter-spacing:0.04em; color:#111;">
                            Unit Cost (₱)
                        </label>
                        <input type="number" id="matEditUnitCost" value="0" min="0" step="0.01"
                            style="display:block; width:100%; padding:10px 12px;
                           border:1px solid #d1d5db; border-radius:8px;
                           font-size:0.9rem; background:#f9fafb; color:#111;
                           box-sizing:border-box; outline:none;">
                    </div>

                    <!-- Preview Total Stock -->
                    <div id="matEditTotalPreview" style="margin-top:12px; padding:10px; background:#f0fdf4; border-radius:8px; text-align:center;">
                        <span style="font-size:0.85rem; color:#166534;">Total Stock: <strong id="matEditTotalValue">0</strong></span>
                    </div>

                    <!-- Error Message -->
                    <div id="matEditItemError" style="margin-top:12px; color:#dc2626; font-size:0.82rem; min-height:20px;"></div>
                </div>

                <!-- Footer -->
                <div style="display:flex; justify-content:flex-end; gap:10px;
                    padding:14px 20px; border-top:1px solid #e5e7eb;
                    background:#f9fafb; flex-shrink:0;">
                    <button id="matEditItemCancelBtn" type="button"
                        style="padding:9px 18px; border:1px solid #d1d5db; border-radius:8px;
                       background:#fff; color:#374151; font-size:0.875rem;
                       font-weight:600; cursor:pointer;">
                        Cancel
                    </button>
                    <button id="matEditItemConfirmBtn" type="button"
                        style="padding:9px 18px; border:none; border-radius:8px;
                       background:#2563eb; color:#fff; font-size:0.875rem;
                       font-weight:600; cursor:pointer; display:inline-flex;
                       align-items:center; gap:7px;">
                        <i class="fa-solid fa-save"></i> Save Changes
                    </button>
                </div>

            </div>
        </div>
    </main>
</div>

<!-- Materials JS loaded LAST — all DOM elements guaranteed to exist -->
<script src="../../assets/js/admin-site-functions/admin_materials.js"></script>
<script src="../../assets/js/admin-site-functions/inventory_item_manager.js"></script>