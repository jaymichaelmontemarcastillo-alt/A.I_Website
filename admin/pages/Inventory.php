<?php
//materials_inventory.php
include 'auth_check.php';
include '../includes/header.php';

$isAdminRole = isset($_SESSION['role']) && strtolower($_SESSION['role']) === 'admin';
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
                <div class="top-btn" style="display: flex; gap: 12px;">
                    <button class="btn-import" onclick="matOpenImportModal()">
                        <i class="fa-solid fa-upload"></i> Import
                    </button>
                    <button class="btn-export" onclick="matExportData()">
                        <i class="fa-solid fa-download"></i> Export
                    </button>
                    <?php if ($isAdminRole): ?>
                    <button class="btn-delete-all" onclick="matOpenDeleteAllModal()" style="background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; padding: 8px 16px; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600; font-family: 'Inter', sans-serif; transition: all 0.2s ease;" onmouseover="this.style.background='#fecaca';" onmouseout="this.style.background='#fee2e2';">
                        <i class="fa-solid fa-trash" style="font-size: 12px;"></i> Delete All
                    </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Import Materials Modal (CSV & XLSX supported) -->
            <div id="matImportModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.55); z-index:99999; align-items:center; justify-content:center;">
                <div style="background:#fff; border-radius:12px; max-width:540px; width:90%; padding:24px; box-shadow: 0 20px 60px rgba(0,0,0,0.25);">

                    <!-- Header -->
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                        <h3 style="margin:0; font-size:1.15rem; font-weight:700; color:#111827; display:flex; align-items:center; gap:8px;">
                            <i class="fa-solid fa-file-import" style="color:#2563eb;"></i> Import Materials &amp; Stock
                        </h3>
                        <button onclick="matCloseImportModal()" style="background:none; border:none; color:#9ca3af; cursor:pointer; font-size:1.1rem;"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <p style="margin:0 0 16px 0; color:#4b5563; font-size:0.875rem;">
                        Upload a CSV or Excel file containing your inventory items and stock levels.
                    </p>

                    <!-- Supported formats notice -->
                    <div style="display:flex; gap:10px; margin-bottom:16px;">
                        <!-- CSV badge -->
                        <div style="flex:1; padding:10px 12px; background:#f0f9ff; border:1px solid #bae6fd; border-radius:8px; display:flex; align-items:center; gap:8px;">
                            <i class="fa-solid fa-file-csv" style="color:#0ea5e9; font-size:1.2rem;"></i>
                            <div>
                                <div style="font-weight:600; font-size:0.85rem; color:#0369a1;">.CSV</div>
                                <div style="font-size:0.75rem; color:#6b7280;">Comma/Semicolon separated</div>
                            </div>
                        </div>
                        <!-- XLSX badge -->
                        <div style="flex:1; padding:10px 12px; background:#f0fdf4; border:1px solid #86efac; border-radius:8px; display:flex; align-items:center; gap:8px;">
                            <i class="fa-solid fa-file-excel" style="color:#16a34a; font-size:1.2rem;"></i>
                            <div>
                                <div style="font-weight:600; font-size:0.85rem; color:#15803d;">.XLSX</div>
                                <div style="font-size:0.75rem; color:#6b7280;">Excel spreadsheet</div>
                            </div>
                        </div>
                    </div>

                    <!-- Drag & drop dropzone container -->
                    <div id="matDropzone" style="border:2px dashed #cbd5e1; border-radius:10px; padding:20px; text-align:center; background:#f8fafc; cursor:pointer; transition:all 0.2s ease; margin-bottom:12px;" onclick="document.getElementById('importCSVFile').click();">
                        <i class="fa-solid fa-cloud-arrow-up" style="font-size:2rem; color:#3b82f6; margin-bottom:8px;"></i>
                        <div style="font-weight:600; font-size:0.9rem; color:#1e293b;">Click to browse or drag &amp; drop file here</div>
                        <div style="font-size:0.78rem; color:#64748b; margin-top:4px;">Supports .csv and .xlsx up to 10MB</div>
                        <input
                            type="file"
                            id="importCSVFile"
                            accept=".csv,.xlsx"
                            style="display:none;"
                        >
                    </div>

                    <!-- Live file-type hint shown after user picks a file -->
                    <div id="matImportFileHint" style="margin:0 0 16px; font-size:0.85rem; color:#475569; min-height:20px;"></div>

                    <!-- Sample template download -->
                    <div style="display:flex; justify-content:space-between; align-items:center; padding-top:12px; border-top:1px solid #e2e8f0;">
                        <button onclick="matDownloadSampleCSV()" style="padding:7px 12px; background:#f1f5f9; border:1px solid #cbd5e1; border-radius:6px; cursor:pointer; font-size:0.82rem; color:#334155; display:inline-flex; align-items:center; gap:6px;">
                            <i class="fa-solid fa-download" style="color:#64748b;"></i> Download Sample CSV
                        </button>

                        <div style="display:flex; gap:8px;">
                            <button onclick="matCloseImportModal()" style="padding:8px 16px; border:1px solid #cbd5e1; border-radius:6px; background:#fff; cursor:pointer; font-size:0.875rem; color:#475569;">Cancel</button>
                            <button onclick="matProcessImport()" style="padding:8px 20px; border:none; border-radius:6px; background:#2563eb; color:#fff; cursor:pointer; font-size:0.875rem; font-weight:500; display:inline-flex; align-items:center; gap:6px;">
                                <i class="fa-solid fa-upload"></i> Import File
                            </button>
                        </div>
                    </div>

                </div>
            </div>


            <!-- Delete All Materials Modal -->
            <div id="matDeleteAllModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.55); backdrop-filter:blur(4px); z-index:99999; align-items:center; justify-content:center; font-family:'Inter', sans-serif;">
                <div style="background:#fff; border-radius:16px; max-width:440px; width:90%; padding:24px; box-shadow:0 20px 50px rgba(0,0,0,0.18); border:1px solid #f1f5f9; position:relative;">
                    <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
                        <div style="width:44px; height:44px; border-radius:12px; background:#fee2e2; color:#ef4444; display:flex; align-items:center; justify-content:center; font-size:1.2rem; flex-shrink:0;">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <h3 style="margin:0; font-size:1.1rem; font-weight:700; color:#0f172a; font-family:'Inter', sans-serif;">Delete All Materials</h3>
                            <span style="font-size:0.78rem; color:#ef4444; font-weight:600; text-transform:uppercase; letter-spacing:0.04em;">Irreversible Action</span>
                        </div>
                    </div>
                    
                    <p style="margin:0 0 18px 0; color:#64748b; font-size:0.875rem; line-height:1.5; font-family:'Inter', sans-serif;">
                        This will permanently erase every material item and stock record from the inventory. This action cannot be undone.
                    </p>

                    <div style="margin-bottom:18px;">
                        <label for="matDeleteAllPassword" style="display:block; margin-bottom:6px; font-weight:600; font-size:0.85rem; color:#334155; font-family:'Inter', sans-serif;">
                            Admin Password <span style="color:#ef4444;">*</span>
                        </label>
                        <input type="password" id="matDeleteAllPassword" placeholder="Enter your admin password" autocomplete="current-password" 
                            style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:10px; box-sizing:border-box; font-family:'Inter', sans-serif; font-size:0.9rem; color:#1e293b; outline:none; transition:all 0.2s ease; background:#f8fafc;"
                            onfocus="this.style.borderColor='#ef4444'; this.style.backgroundColor='#fff'; this.style.boxShadow='0 0 0 3px rgba(239, 68, 68, 0.15)';"
                            onblur="this.style.borderColor='#cbd5e1'; this.style.backgroundColor='#f8fafc'; this.style.boxShadow='none';">
                    </div>

                    <div id="matDeleteAllFeedback" style="display:none; margin-bottom:16px; padding:10px 14px; border-radius:10px; font-size:0.85rem; font-family:'Inter', sans-serif;"></div>

                    <div style="display:flex; gap:10px; justify-content:flex-end;">
                        <button onclick="matCloseDeleteAllModal()" style="padding:10px 18px; border:1px solid #e2e8f0; border-radius:10px; background:#f1f5f9; color:#475569; cursor:pointer; font-family:'Inter', sans-serif; font-weight:600; font-size:0.875rem; transition:all 0.2s ease;" onmouseover="this.style.background='#e2e8f0';" onmouseout="this.style.background='#f1f5f9';">Cancel</button>
                        <button onclick="matConfirmDeleteAll()" id="matDeleteAllConfirmBtn" style="padding:10px 20px; border:none; border-radius:10px; background:#ef4444; color:#fff; cursor:pointer; font-family:'Inter', sans-serif; font-weight:600; font-size:0.875rem; display:inline-flex; align-items:center; gap:8px; box-shadow:0 4px 12px rgba(239, 68, 68, 0.25); transition:all 0.2s ease;" onmouseover="this.style.background='#dc2626';" onmouseout="this.style.background='#ef4444';"><i class="fa-solid fa-trash"></i> Delete All Materials</button>
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
                            max-width:680px;
                            box-shadow:0 20px 60px rgba(0,0,0,0.25);
                            overflow:hidden;
                            display:flex;
                            flex-direction:column;">

                    <!-- Header -->
                    <div style="display:flex; align-items:center; justify-content:space-between;
                                padding:18px 22px; border-bottom:1px solid #e5e7eb; flex-shrink:0;">
                        <h3 style="margin:0; font-size:1.1rem; font-weight:700; color:#111;">
                            <i class="fa-solid fa-plus-circle" style="color:#10b981;"></i> Add New Material
                        </h3>
                        <button id="matAddItemCloseBtn" type="button"
                            style="background:none; border:none; font-size:1.5rem; cursor:pointer;
                                   color:#6b7280; line-height:1; padding:2px 6px; border-radius:4px;">
                            &times;
                        </button>
                    </div>

                    <!-- Body (2-Column Landscape Grid) -->
                    <div style="padding:22px; flex:1; display:grid; grid-template-columns:1fr 1fr; gap:14px 20px; align-items:start;">

                        <!-- Material Name (Col 1) -->
                        <div>
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

                        <!-- Type - Datalist Input (Col 2) -->
                        <div>
                            <label for="matNewType" style="display:block; font-size:0.78rem; font-weight:600;
                                      margin-bottom:6px; text-transform:uppercase; letter-spacing:0.04em; color:#111;">
                                Type <span style="color:#ef4444;">*</span>
                            </label>
                            <input type="text" id="matNewType" list="matTypeOptions"
                                placeholder="Type or select existing..."
                                style="display:block; width:100%; padding:10px 12px;
                                       border:1px solid #d1d5db; border-radius:8px;
                                       font-size:0.9rem; background:#f9fafb; color:#111;
                                       box-sizing:border-box; outline:none;">
                            <datalist id="matTypeOptions"></datalist>
                            <span style="font-size:0.7rem; color:#6b7280; margin-top:4px; display:block;">
                                <i class="fa-solid fa-info-circle"></i> Start typing to filter existing types
                            </span>
                        </div>

                        <!-- Shop Stock (Col 1) -->
                        <div>
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

                        <!-- PH Stock (Col 2) -->
                        <div>
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

                        <!-- Cost Per Pack (Col 1) -->
                        <div>
                            <label for="matNewCostPerPack" style="display:block; font-size:0.78rem; font-weight:600;
                                      margin-bottom:6px; text-transform:uppercase; letter-spacing:0.04em; color:#111;">
                                Cost/Pack (₱)
                            </label>
                            <input type="number" id="matNewCostPerPack" value="0" min="0" step="0.01" placeholder="0"
                                style="display:block; width:100%; padding:10px 12px;
                                       border:1px solid #d1d5db; border-radius:8px;
                                       font-size:0.9rem; background:#f9fafb; color:#111;
                                       box-sizing:border-box; outline:none;">
                        </div>

                        <!-- Quantity Per Pack (Col 2) -->
                        <div>
                            <label for="matNewPiecesPerPack" style="display:block; font-size:0.78rem; font-weight:600;
                                      margin-bottom:6px; text-transform:uppercase; letter-spacing:0.04em; color:#111;">
                                Qty/Pack
                            </label>
                            <input type="number" id="matNewPiecesPerPack" value="1" min="1" step="1" placeholder="1"
                                style="display:block; width:100%; padding:10px 12px;
                                       border:1px solid #d1d5db; border-radius:8px;
                                       font-size:0.9rem; background:#f9fafb; color:#111;
                                       box-sizing:border-box; outline:none;">
                        </div>

                        <!-- Unit Cost (Col 1) -->
                        <div>
                            <label for="matNewUnitCost" style="display:block; font-size:0.78rem; font-weight:600;
                                      margin-bottom:6px; text-transform:uppercase; letter-spacing:0.04em; color:#111;">
                                Unit Cost (₱)
                            </label>
                            <input type="number" id="matNewUnitCost" value="0" min="0" step="0.0001"
                                style="display:block; width:100%; padding:10px 12px;
                                       border:1px solid #d1d5db; border-radius:8px;
                                       font-size:0.9rem; background:#f9fafb; color:#111;
                                       box-sizing:border-box; outline:none;">
                        </div>

                        <!-- Preview Total Stock (Col 2) -->
                        <div>
                            <label style="display:block; font-size:0.78rem; font-weight:600; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.04em; color:#111;">
                                Total Stock Preview
                            </label>
                            <div id="matNewTotalPreview" style="padding:10px 12px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; text-align:center; height:41px; box-sizing:border-box; display:flex; align-items:center; justify-content:center;">
                                <span style="font-size:0.88rem; color:#166534; font-weight:600;">Total Stock: <strong id="matNewTotalValue">0</strong></span>
                            </div>
                        </div>

                        <!-- Error Message (Full Width) -->
                        <div id="matAddItemError" style="grid-column:1 / -1; color:#dc2626; font-size:0.82rem; min-height:20px;"></div>
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
                        <button class="btn-add-item" onclick="matOpenAddItemModal()">
                            <i class="fa-solid fa-plus"></i> Add New Item
                        </button>
                    </div>
                </div>
                <div class="table-scroll">
                    <table class="mat-table">
                        <thead>
                            <tr>
                                <th class="col-material-name">Material</th>
                                <th class="col-type">Type</th>
                                <th class="col-num">Shop Stock</th>
                                <th class="col-num">PH Stock</th>
                                <th class="col-num">Total Stock</th>
                                <th class="col-num">Cost/Pack</th>
                                <th class="col-num">QTY/Pack</th>
                                <th class="col-num">Unit Cost</th>
                                <th class="col-status">Status</th>
                                <th class="col-action">Action</th>
                            </tr>
                        </thead>
                        <tbody id="materialsTableBody">
                            <tr>
                                <td colspan="10" class="loading-cell">Loading materials…</td>
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
                                <th>Action</th>
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
                max-width:680px;
                box-shadow:0 20px 60px rgba(0,0,0,0.25);
                overflow:hidden;
                display:flex;
                flex-direction:column;">

                <!-- Header -->
                <div style="display:flex; align-items:center; justify-content:space-between;
                    padding:18px 22px; border-bottom:1px solid #e5e7eb; flex-shrink:0;">
                    <h3 style="margin:0; font-size:1.1rem; font-weight:700; color:#111;">
                        <i class="fa-solid fa-pen-to-square" style="color:#2563eb;"></i> Edit Material
                    </h3>
                    <button id="matEditItemCloseBtn" type="button"
                        style="background:none; border:none; font-size:1.5rem; cursor:pointer;
                       color:#6b7280; line-height:1; padding:2px 6px; border-radius:4px;">
                        &times;
                    </button>
                </div>

                <!-- Body (2-Column Landscape Grid) -->
                <div style="padding:22px; flex:1; display:grid; grid-template-columns:1fr 1fr; gap:14px 20px; align-items:start;">

                    <input type="hidden" id="matEditItemId">

                    <!-- Material Name (Col 1) -->
                    <div>
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

                    <!-- Type - Datalist Input (Col 2) -->
                    <div>
                        <label for="matEditType" style="display:block; font-size:0.78rem; font-weight:600;
                          margin-bottom:6px; text-transform:uppercase; letter-spacing:0.04em; color:#111;">
                            Type <span style="color:#ef4444;">*</span>
                        </label>
                        <input type="text" id="matEditType" list="matTypeOptions"
                            placeholder="Type or select existing..."
                            style="display:block; width:100%; padding:10px 12px;
                           border:1px solid #d1d5db; border-radius:8px;
                           font-size:0.9rem; background:#f9fafb; color:#111;
                           box-sizing:border-box; outline:none;">
                        <span style="font-size:0.7rem; color:#6b7280; margin-top:4px; display:block;">
                            <i class="fa-solid fa-info-circle"></i> Start typing to filter existing types
                        </span>
                    </div>

                    <!-- Shop Stock (Col 1) -->
                    <div>
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

                    <!-- PH Stock (Col 2) -->
                    <div>
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

                    <!-- Cost Per Pack (Col 1) -->
                    <div>
                        <label for="matEditCostPerPack" style="display:block; font-size:0.78rem; font-weight:600;
                          margin-bottom:6px; text-transform:uppercase; letter-spacing:0.04em; color:#111;">
                            Cost/Pack (₱)
                        </label>
                        <input type="number" id="matEditCostPerPack" value="0" min="0" step="0.01" placeholder="0"
                            style="display:block; width:100%; padding:10px 12px;
                           border:1px solid #d1d5db; border-radius:8px;
                           font-size:0.9rem; background:#f9fafb; color:#111;
                           box-sizing:border-box; outline:none;">
                    </div>

                    <!-- Quantity Per Pack (Col 2) -->
                    <div>
                        <label for="matEditPiecesPerPack" style="display:block; font-size:0.78rem; font-weight:600;
                          margin-bottom:6px; text-transform:uppercase; letter-spacing:0.04em; color:#111;">
                            Qty/Pack
                        </label>
                        <input type="number" id="matEditPiecesPerPack" value="1" min="1" step="1" placeholder="1"
                            style="display:block; width:100%; padding:10px 12px;
                           border:1px solid #d1d5db; border-radius:8px;
                           font-size:0.9rem; background:#f9fafb; color:#111;
                           box-sizing:border-box; outline:none;">
                    </div>

                    <!-- Unit Cost (Col 1) -->
                    <div>
                        <label for="matEditUnitCost" style="display:block; font-size:0.78rem; font-weight:600;
                          margin-bottom:6px; text-transform:uppercase; letter-spacing:0.04em; color:#111;">
                            Unit Cost (₱)
                        </label>
                        <input type="number" id="matEditUnitCost" value="0" min="0" step="0.0001"
                            style="display:block; width:100%; padding:10px 12px;
                           border:1px solid #d1d5db; border-radius:8px;
                           font-size:0.9rem; background:#f9fafb; color:#111;
                           box-sizing:border-box; outline:none;">
                    </div>

                    <!-- Preview Total Stock (Col 2) -->
                    <div>
                        <label style="display:block; font-size:0.78rem; font-weight:600; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.04em; color:#111;">
                            Total Stock Preview
                        </label>
                        <div id="matEditTotalPreview" style="padding:10px 12px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; text-align:center; height:41px; box-sizing:border-box; display:flex; align-items:center; justify-content:center;">
                            <span style="font-size:0.88rem; color:#166534; font-weight:600;">Total Stock: <strong id="matEditTotalValue">0</strong></span>
                        </div>
                    </div>

                    <!-- Remarks (Full Width) -->
                    <div style="grid-column:1 / -1;">
                        <label for="matEditRemarks" style="display:block; font-size:0.78rem; font-weight:600;
                          margin-bottom:6px; text-transform:uppercase; letter-spacing:0.04em; color:#111;">
                            Remarks
                        </label>
                        <textarea id="matEditRemarks" rows="2" placeholder="Add a note for this material update..."
                            style="display:block; width:100%; padding:10px 12px;
                           border:1px solid #d1d5db; border-radius:8px;
                           font-size:0.9rem; background:#f9fafb; color:#111;
                           box-sizing:border-box; outline:none; resize:vertical;"></textarea>
                    </div>

                    <!-- Error Message (Full Width) -->
                    <div id="matEditItemError" style="grid-column:1 / -1; color:#dc2626; font-size:0.82rem; min-height:20px;"></div>
                </div>

                <!-- Footer -->
                <div style="display:flex; align-items:center; justify-content:space-between;
                    padding:14px 20px; border-top:1px solid #e5e7eb;
                    background:#f9fafb; flex-shrink:0;">
                    <button id="matEditItemDeleteBtn" type="button"
                        style="padding:9px 16px; border:1px solid #fee2e2; border-radius:8px;
                       background:#fef2f2; color:#dc2626; font-size:0.875rem;
                       font-weight:600; cursor:pointer; display:inline-flex;
                       align-items:center; gap:6px;">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>
                    <div style="display:flex; gap:10px;">
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
        </div>
    </main>

    <!-- ═══════════════════════════════════════════════════════════════
         DELETE SINGLE MATERIAL CONFIRMATION MODAL
         ═══════════════════════════════════════════════════════════════ -->
    <div id="matDeleteConfirmModal" style="display:none; position:fixed; inset:0; background:rgba(15, 23, 42, 0.72); z-index:100005; align-items:center; justify-content:center; padding:16px; box-sizing:border-box;">
        <div style="background:#fff; border-radius:12px; width:100%; max-width:400px; box-shadow:0 24px 80px rgba(0,0,0,0.25); overflow:hidden; border:1px solid #e5e7eb; animation: modalPop 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);">
            <div style="padding:24px; text-align:center;">
                <div style="width:48px; height:48px; border-radius:50%; background:#fee2e2; color:#ef4444; display:flex; align-items:center; justify-content:center; font-size:1.5rem; margin:0 auto 16px;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h3 style="margin:0 0 8px; font-size:1.125rem; font-weight:700; color:#111;">Delete Material</h3>
                <p style="margin:0; font-size:0.875rem; color:#4b5563; line-height:1.5;">
                    Are you sure you want to delete <strong id="matDeleteConfirmName" style="color:#111;"></strong>? This action cannot be undone.
                </p>
            </div>
            <div style="display:flex; gap:12px; padding:16px 24px; background:#f8fafc; border-top:1px solid #e5e7eb;">
                <button id="matDeleteCancelBtn" type="button" style="flex:1; padding:10px; border:1px solid #d1d5db; border-radius:8px; background:#fff; color:#374151; font-size:0.875rem; font-weight:600; cursor:pointer;">
                    Cancel
                </button>
                <button id="matDeleteConfirmBtn" type="button" style="flex:1; padding:10px; border:none; border-radius:8px; background:#ef4444; color:#fff; font-size:0.875rem; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px;">
                    <i class="fa-solid fa-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>

    <div id="matLogDetailsModal" style="display:none; position:fixed; inset:0; background:rgba(15, 23, 42, 0.72); z-index:100002; align-items:center; justify-content:center; padding:16px; box-sizing:border-box;">
        <div style="background:#fff; border-radius:16px; width:100%; max-width:560px; box-shadow:0 24px 80px rgba(0,0,0,0.25); overflow:hidden; border:1px solid #e5e7eb;">
            <div style="display:flex; align-items:center; justify-content:space-between; padding:18px 20px; border-bottom:1px solid #e5e7eb; background:linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%);">
                <div>
                    <h3 style="margin:0; font-size:1rem; font-weight:700; color:#111;">Material Log Details</h3>
                    <p style="margin:4px 0 0; font-size:0.8rem; color:#64748b;">Review the recorded stock change and remarks.</p>
                </div>
                <button id="matLogDetailsCloseBtn" type="button" style="background:none; border:none; font-size:1.5rem; cursor:pointer; color:#6b7280; line-height:1; padding:2px 6px; border-radius:4px;">&times;</button>
            </div>
            <div id="matLogDetailsBody" style="padding:20px; line-height:1.6; color:#374151;"></div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         QUOTATION AUDIT MODAL (Created from quotation selection)
         ═══════════════════════════════════════════════════════════════ -->
    <div id="quotationAuditModal" class="quotation-audit-modal" style="display:none;">
        <div class="quotation-audit-container">
            <div class="quotation-audit-header">
                <h3><i class="fa-solid fa-clipboard-list"></i> Create Audit from Quotation</h3>
                <button class="quotation-audit-close" onclick="quotationAuditIntegration.closeAuditModal()">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
            <div class="quotation-audit-body">
                <!-- Loading Overlay -->
                <div class="audit-loading-overlay" id="auditLoadingOverlay" style="display:none;">
                    <div class="audit-loading-spinner">
                        <i class="fa-solid fa-spinner fa-spin"></i> Loading quotation data...
                    </div>
                </div>

                <!-- Item Information Section -->
                <div class="audit-section">
                    <div class="audit-section-title">
                        <i class="fa-solid fa-tag"></i> Item Information
                    </div>
                    <div class="audit-section-body">
                        <input type="text" id="qaAuditItemName" class="qa-input-full" placeholder="Enter item/product name..." style="width:100%; padding:12px; border:1px solid #d1d5db; border-radius:8px;">
                    </div>
                </div>

                <!-- Items Section -->
                <div class="audit-section">
                    <div class="audit-section-title">
                        <i class="fa-solid fa-list-ul"></i> Items
                    </div>
                    <div class="audit-section-body">
                        <div class="qa-table-header">
                            <span style="flex:2;">Item Name</span>
                            <span style="flex:0.8; text-align: center;">Quantity</span>
                            <span style="flex:1; text-align: center;">Unit Price</span>
                            <span style="flex:1; text-align: center;">Total Amount</span>
                            <span style="flex:0.3; text-align: center;"></span>
                        </div>
                        <div id="qaItemsContainer" class="qa-dynamic-container"></div>
                        <button type="button" class="qa-add-row-btn" onclick="quotationAuditIntegration.addItemRow()">
                            <i class="fa-solid fa-plus"></i> Add Item
                        </button>
                    </div>
                </div>

                <!-- Material Costs Section -->
                <div class="audit-section">
                    <div class="audit-section-title">
                        <i class="fa-solid fa-cubes"></i> Material Costs
                    </div>
                    <div class="audit-section-body">
                        <div class="qa-table-header">
                            <span style="flex:2;">Material</span>
                            <span style="flex:0.8; text-align: center;">Quantity</span>
                            <span style="flex:1; text-align: center;">Cost Per Unit</span>
                            <span style="flex:1; text-align: center;">Total</span>
                            <span style="flex:0.3; text-align: center;"></span>
                        </div>
                        <div id="qaMaterialCostsContainer" class="qa-dynamic-container"></div>
                        <button type="button" class="qa-add-row-btn" onclick="quotationAuditIntegration.addMaterialRow()">
                            <i class="fa-solid fa-plus"></i> Add Material
                        </button>
                    </div>
                </div>

                <!-- Reject Costs Section -->
                <div class="audit-section">
                    <div class="audit-section-title">
                        <i class="fa-solid fa-trash-alt"></i> Reject Costs
                    </div>
                    <div class="audit-section-body">
                        <div class="reject-note">
                            <i class="fa-solid fa-info-circle"></i> Type a custom reject item or choose from the list and edit the unit cost and total directly.
                        </div>
                        <div class="qa-table-header">
                            <span style="flex:2;">Material</span>
                            <span style="flex:0.8; text-align: center;">Quantity</span>
                            <span style="flex:1; text-align: center;">Cost Per Unit</span>
                            <span style="flex:1; text-align: center;">Total</span>
                            <span style="flex:0.3; text-align: center;"></span>
                        </div>
                        <div id="qaRejectCostsContainer" class="qa-dynamic-container"></div>
                        <button type="button" class="qa-add-row-btn" onclick="quotationAuditIntegration.addRejectRow()">
                            <i class="fa-solid fa-plus"></i> Add Reject Material
                        </button>
                    </div>
                </div>

                <!-- Overhead & Electricity Section -->
                <div class="audit-overhead-section">
                    <div class="audit-section-title">
                        <i class="fa-solid fa-bolt"></i> Electricity & Overhead Costs
                    </div>
                    <div class="audit-section-body">
                        <div class="overhead-grid">
                            <div class="overhead-field">
                                <label><i class="fa-solid fa-building"></i> Shop Rent</label>
                                <input type="number" id="overhead_shop_rent" class="overhead-input" step="0.01" value="0" placeholder="₱0.00" oninput="calculateOverheadTotals()">
                            </div>
                            <div class="overhead-field">
                                <label><i class="fa-solid fa-users"></i> Fixed Salaries (Admin/Finance)</label>
                                <input type="number" id="overhead_fixed_salaries" class="overhead-input" step="0.01" value="0" placeholder="₱0.00" oninput="calculateOverheadTotals()">
                            </div>
                            <div class="overhead-field">
                                <label><i class="fa-solid fa-wifi"></i> Shop Utilities (Internet + Base Power)</label>
                                <input type="number" id="overhead_shop_utilities" class="overhead-input" step="0.01" value="0" placeholder="₱0.00" oninput="calculateOverheadTotals()">
                            </div>
                            <div class="overhead-field">
                                <label><i class="fa-solid fa-newspaper"></i> Subscriptions</label>
                                <input type="number" id="overhead_subscriptions" class="overhead-input" step="0.01" value="0" placeholder="₱0.00" oninput="calculateOverheadTotals()">
                            </div>
                            <div class="overhead-field">
                                <label><i class="fa-solid fa-microchip"></i> Machine Depreciation (Fixed)</label>
                                <input type="number" id="overhead_machine_depreciation" class="overhead-input" step="0.01" value="0" placeholder="₱0.00" oninput="calculateOverheadTotals()">
                            </div>
                            <div class="overhead-field">
                                <label><i class="fa-solid fa-wrench"></i> Maintenance & Repair Fund Allocation</label>
                                <input type="number" id="overhead_maintenance_repair" class="overhead-input" step="0.01" value="0" placeholder="₱0.00" oninput="calculateOverheadTotals()">
                            </div>
                            <div class="overhead-field">
                                <label><i class="fa-solid fa-chart-line"></i> Marketing & Promo Materials</label>
                                <input type="number" id="overhead_marketing" class="overhead-input" step="0.01" value="0" placeholder="₱0.00" oninput="calculateOverheadTotals()">
                            </div>
                            <div class="overhead-field">
                                <label><i class="fa-solid fa-bolt"></i> Electricity</label>
                                <input type="number" id="overhead_electricity" class="overhead-input" step="0.01" value="0" placeholder="₱0.00" oninput="calculateOverheadTotals()">
                            </div>
                        </div>

                        <div class="overhead-total-row">
                            <div class="overhead-total-item">
                                <span>Total Monthly Overhead</span>
                                <span id="total_overhead_display">₱0.00</span>
                            </div>
                        </div>

                        <div class="production-hours-row">
                            <div class="production-hours-field">
                                <label><i class="fa-regular fa-clock"></i> Production Hours (for this project)</label>
                                <input type="number" id="production_hours" step="0.5" value="0" placeholder="Hours" oninput="calculateOverheadTotals()">
                            </div>
                            <div class="overhead-per-hour-display" id="overhead_per_hour_display">
                                <strong>₱0.00</strong> per hour
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Totals Section -->
                <div class="qa-totals-grid">
                    <div class="qa-total-card">
                        <label><i class="fa-solid fa-cubes"></i> Total Material Cost</label>
                        <div class="qa-total-value" id="qaTotalMaterialCost">₱0.00</div>
                    </div>
                    <div class="qa-total-divider"></div>
                    <div class="qa-total-card">
                        <label><i class="fa-solid fa-trash-alt"></i> Total Reject Cost</label>
                        <div class="qa-total-value" id="qaTotalRejectCost">₱0.00</div>
                    </div>
                    <div class="qa-total-divider"></div>
                    <div class="qa-total-card">
                        <label><i class="fa-solid fa-bolt"></i> Total Overhead</label>
                        <div class="qa-total-value" id="qaTotalOverheadCost">₱0.00</div>
                    </div>
                    <div class="qa-total-divider"></div>
                    <div class="qa-total-card">
                        <label><i class="fa-solid fa-calculator"></i> Total Cost (w/ Overhead)</label>
                        <div class="qa-total-value" id="qaTotalCostWithOverhead">₱0.00</div>
                    </div>
                    <div class="qa-total-divider"></div>
                    <div class="qa-total-card">
                        <label><i class="fa-solid fa-receipt"></i> Total Amount Due</label>
                        <div class="qa-total-value" id="qaTotalAmountDue">₱0.00</div>
                    </div>
                    <div class="qa-total-divider"></div>
                    <div class="qa-total-card">
                        <label><i class="fa-solid fa-chart-line"></i> Profit</label>
                        <div class="qa-total-value profit-value" id="qaProfit">₱0.00</div>
                    </div>
                </div>

                <!-- Signatures Section -->
                <div class="qa-signatures-grid">
                    <div class="qa-signature-field">
                        <label><i class="fa-regular fa-user"></i> Created By</label>
                        <input type="text" id="qaCreatedBy" placeholder="Enter name...">
                    </div>
                    <div class="qa-signature-field">
                        <label><i class="fa-regular fa-user-check"></i> Audited By</label>
                        <input type="text" id="qaAuditedBy" placeholder="Enter name...">
                    </div>
                    <div class="qa-signature-field">
                        <label><i class="fa-regular fa-hand-peace"></i> Acknowledged By</label>
                        <input type="text" id="qaAcknowledgedBy" placeholder="Enter name...">
                    </div>
                </div>

                <!-- Actions -->
                <div class="qa-modal-actions">
                    <button class="qa-btn-cancel" onclick="quotationAuditIntegration.closeAuditModal()">
                        <i class="fa-solid fa-times"></i> Cancel
                    </button>
                    <button class="qa-btn-submit" onclick="quotationAuditIntegration.submitAudit()">
                        <i class="fa-solid fa-save"></i> Create Audit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- PENDING AUDIT QUOTATIONS MODAL -->
    <div id="pendingAuditModal" class="pending-audit-modal" style="display:none;">
        <div class="pending-audit-container">
            <div class="pending-audit-header">
                <h3><i class="fa-solid fa-clipboard-list"></i> Pending Audit Quotations</h3>
                <button class="pending-audit-close" onclick="closePendingAuditModal()">&times;</button>
            </div>
            <div class="pending-audit-body">
                <p class="pending-audit-desc">Select a quotation to create an inventory audit for material consumption tracking.</p>
                <div class="pending-audit-search">
                    <i class="fa-solid fa-search"></i>
                    <input type="text" id="pendingAuditSearch" placeholder="Search by quote number or customer..." onkeyup="filterPendingAuditList()">
                </div>
                <div class="pending-audit-list" id="pendingAuditList">
                    <div class="loading-spinner"><i class="fa-solid fa-spinner fa-spin"></i> Loading pending quotations...</div>
                </div>
            </div>
            <div class="pending-audit-footer">
                <button class="pending-audit-btn cancel" onclick="closePendingAuditModal()">
                    <i class="fa-solid fa-times"></i> Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Materials JS loaded LAST — all DOM elements guaranteed to exist -->
<script src="../../assets/js/admin-site-functions/admin_materials.js?v=<?php echo time(); ?>"></script>
<script src="../../assets/js/admin-site-functions/inventory_item_manager.js?v=<?php echo time(); ?>"></script>
<script src="../../assets/js/admin-site-functions/update_inventory_materials.js?v=<?php echo time(); ?>"></script>
<script src="../../assets/js/admin-site-functions/pending_audit_manager.js?v=<?php echo time(); ?>"></script>
<script src="../../assets/js/admin-site-functions/overhead_audit_helper.js?v=<?php echo time(); ?>"></script>