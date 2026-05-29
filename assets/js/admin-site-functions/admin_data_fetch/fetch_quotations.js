/* assets/js/admin-site-functions/admin_data_fetch/fetch_quotations.js
 *
 * Complete Quotation Management System with:
 *  - Dashboard cards with statistics
 *  - Create new quotation functionality
 *  - Edit existing quotations
 *  - Status update with approval modal
 *  - Delivery Receipt generation from edit modal when status is accepted
 *  - Address field for delivery
 *  - Enhanced rich text formatting (Bold, Italic, Underline, Lists, Indent, Outdent)
 *  - Product search suggestion for description
 */

// Inject styles
(function injectStyles() {
  if (document.getElementById("qm-extra-styles")) return;
  const style = document.createElement("style");
  style.id = "qm-extra-styles";
  style.textContent = `
    .qm-confirm-overlay {
      position: fixed; inset: 0; z-index: 100000;
      background: rgba(0,0,0,.45);
      display: flex; align-items: center; justify-content: center;
      animation: qmFadeIn .18s ease;
    }
    .qm-confirm-box {
      background: #fff; border-radius: 12px;
      padding: 32px 28px 24px;
      width: 380px; max-width: 92vw;
      box-shadow: 0 20px 60px rgba(0,0,0,.25);
      text-align: center;
    }
    .qm-confirm-icon {
      width: 52px; height: 52px; border-radius: 50%;
      background: #fff0f0; display: flex; align-items: center;
      justify-content: center; margin: 0 auto 16px;
      font-size: 22px; color: #e53935;
    }
    .qm-confirm-title {
      font-size: 17px; font-weight: 700; color: #111; margin-bottom: 8px;
    }
    .qm-confirm-msg {
      font-size: 13.5px; color: #555; line-height: 1.55; margin-bottom: 24px;
    }
    .qm-confirm-msg strong { color: #111; }
    .qm-confirm-actions {
      display: flex; gap: 10px; justify-content: center;
    }
    .qm-confirm-cancel {
      flex: 1; padding: 10px 0; border-radius: 7px; border: 1.5px solid #ddd;
      background: #fff; font-size: 14px; font-weight: 600; color: #444;
      cursor: pointer;
    }
    .qm-confirm-cancel:hover { background: #f5f5f5; }
    .qm-confirm-delete {
      flex: 1; padding: 10px 0; border-radius: 7px; border: none;
      background: #e53935; font-size: 14px; font-weight: 600; color: #fff;
      cursor: pointer;
    }
    .qm-confirm-delete:hover { background: #c62828; }

    /* Enhanced Toolbar Styles */
    .qe-editor-toolbar {
      display: flex;
      gap: 4px;
      flex-wrap: wrap;
      padding: 6px 8px;
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-bottom: none;
      border-radius: 6px 6px 0 0;
    }
    .qe-toolbar-btn {
      padding: 4px 9px;
      border-radius: 4px;
      border: 1px solid #e2e8f0;
      background: #fff;
      font-size: 12px;
      color: #374151;
      cursor: pointer;
      transition: all 0.2s;
    }
    .qe-toolbar-btn:hover {
      background: #e8f0fe;
      border-color: #93c5fd;
    }
    .qe-toolbar-btn.active {
      background: #2563eb;
      border-color: #2563eb;
      color: #fff;
    }
    .qe-editor-content {
      min-height: 80px;
      max-height: 200px;
      overflow-y: auto;
      border: 1px solid #e2e8f0;
      border-radius: 0 0 6px 6px;
      padding: 10px 12px;
      font-size: 14px;
      background: #fff;
      outline: none;
      line-height: 1.65;
    }
    .qe-editor-content:focus {
      border-color: #1a56db;
      box-shadow: 0 0 0 2px rgba(26,86,219,0.1);
    }
    
    /* Product suggestion dropdown */
    .qe-suggestion-dropdown {
      position: absolute;
      background: #fff;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      max-height: 200px;
      overflow-y: auto;
      z-index: 100050;
      min-width: 250px;
    }
    .qe-suggestion-item {
      padding: 8px 12px;
      cursor: pointer;
      border-bottom: 1px solid #f0f0f0;
      transition: background 0.2s;
    }
    .qe-suggestion-item:hover {
      background: #f0fdf4;
    }
    .qe-suggestion-name {
      font-weight: 600;
      color: #111;
      font-size: 13px;
    }
    .qe-suggestion-price {
      font-size: 11px;
      color: #10b981;
      margin-top: 2px;
    }
    /* Product Suggestion Dropdown Styles */
.qe-suggestion-dropdown {
    position: fixed;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    z-index: 100050;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    max-height: 400px;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.qe-suggestion-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}

.qe-suggestion-search-wrapper {
    flex: 1;
    position: relative;
    display: flex;
    align-items: center;
}

.qe-suggestion-search-wrapper i {
    position: absolute;
    left: 12px;
    color: #94a3b8;
    font-size: 14px;
}

.qe-suggestion-search {
    width: 100%;
    padding: 8px 12px 8px 34px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 13px;
    outline: none;
    transition: all 0.2s;
}

.qe-suggestion-search:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 2px rgba(37,99,235,0.1);
}

.qe-suggestion-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #94a3b8;
    padding: 0 8px;
    line-height: 1;
    border-radius: 6px;
    transition: all 0.2s;
}

.qe-suggestion-close:hover {
    background: #e2e8f0;
    color: #475569;
}

.qe-suggestion-list {
    flex: 1;
    overflow-y: auto;
    max-height: 350px;
}

.qe-suggestion-loading,
.qe-suggestion-empty {
    padding: 40px 20px;
    text-align: center;
    color: #94a3b8;
    font-size: 13px;
}

.qe-suggestion-loading i,
.qe-suggestion-empty i {
    margin-right: 8px;
}

.qe-suggestion-item {
    padding: 12px 16px;
    cursor: pointer;
    border-bottom: 1px solid #f1f5f9;
    transition: all 0.2s;
}

.qe-suggestion-item:hover {
    background: #f0fdf4;
    padding-left: 20px;
}

.qe-suggestion-name {
    font-weight: 600;
    color: #0f172a;
    font-size: 14px;
    margin-bottom: 6px;
}

.qe-suggestion-details {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    font-size: 12px;
}

.qe-suggestion-type {
    color: #64748b;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.qe-suggestion-price {
    color: #10b981;
    font-weight: 600;
}

.qe-suggestion-stock {
    color: #3b82f6;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.qe-suggestion-type i,
.qe-suggestion-stock i {
    font-size: 11px;
}
    @keyframes qmFadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes qmSlideIn { from { opacity:0; transform:translateX(30px); } to { opacity:1; transform:none; } }
    @keyframes qmSlideOut { from { opacity:1; transform:none; } to { opacity:0; transform:translateX(30px); } }
  `;
  document.head.appendChild(style);
})();

// QuotationManager Class
class QuotationManager {
  constructor(config = {}) {
    this.config = {
      tableBodyId: "quotationsTableBody",
      apiUrl: "../../api/admin_site/get_quotations.php",
      statusApiUrl: "../../api/admin_site/update_quotation_status.php",
      pdfApiUrl: "../../api/admin_site/generate_quotation_pdf.php",
      deliveryReceiptApiUrl:
        "../../api/admin_site/generate_delivery_receipt.php",
      getSingleApiUrl: "../../api/admin_site/get_single_quotation.php",
      updateApiUrl: "../../api/admin_site/update_quotation.php",
      createApiUrl: "../../api/admin_site/create_quotation.php",
      deleteApiUrl: "../../api/admin_site/delete_quotation.php",
      statsApiUrl: "../../api/admin_site/get_quotation_stats.php",
      productsApiUrl: "../../api/admin_site/get_products_for_quotation.php",
      limit: 10,
      ...config,
    };

    this.currentPage = 1;
    this.currentFilter = "all";
    this.currentSearch = "";
    this._editingId = null;
    this._isCreateMode = false;
    this._currentQuotationStatus = null;
    this._rowCounter = 0;
    this._isSaving = false;
    this._pendingApprovalId = null;
    this._productsCache = null;

    this.init();
  }
  debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func(...args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }

  async init() {
    this._attachEventListeners();
    await this.fetchQuotations();
    await this.fetchStats();
    await this._loadProductsCache();
  }

  async _loadProductsCache() {
    try {
      const response = await fetch(this.config.productsApiUrl);
      const result = await this._parseJSON(response);
      if (result.success) {
        this._productsCache = result.products;
      }
    } catch (err) {
      console.error("Failed to load products cache:", err);
    }
  }

  _attachEventListeners() {
    const searchInput = document.getElementById("quotationSearch");
    if (searchInput) this._addDebounceListener(searchInput);

    const statusFilter = document.getElementById("statusFilter");
    if (statusFilter) {
      statusFilter.addEventListener("change", (e) => {
        this.currentFilter = e.target.value;
        this.currentPage = 1;
        this.fetchQuotations();
      });
    }

    const prevBtn = document.getElementById("prevPage");
    const nextBtn = document.getElementById("nextPage");
    if (prevBtn) prevBtn.addEventListener("click", () => this.prevPage());
    if (nextBtn) nextBtn.addEventListener("click", () => this.nextPage());

    const overlay = document.getElementById("QuotationEditModal");
    if (overlay) {
      overlay.addEventListener("click", (e) => {
        if (e.target === overlay) this.closeEditModal();
      });
    }
  }

  _addDebounceListener(input) {
    let timer;
    input.addEventListener("input", (e) => {
      clearTimeout(timer);
      timer = setTimeout(() => {
        this.currentSearch = e.target.value.trim();
        this.currentPage = 1;
        this.fetchQuotations();
      }, 500);
    });
  }

  // ── DASHBOARD STATS ──────────────────────────────────────────────────────
  async fetchStats() {
    try {
      const response = await fetch(this.config.statsApiUrl);
      const result = await this._parseJSON(response);

      if (result.success) {
        const stats = result.data;
        document.getElementById("totalQuotes").textContent = stats.total || 0;
        document.getElementById("pendingQuotes").textContent = stats.draft || 0;
        document.getElementById("approvedQuotes").textContent =
          stats.accepted || 0;
        document.getElementById("declinedQuotes").textContent =
          stats.expired || 0;
        document.getElementById("deliveredQuotes").textContent =
          stats.converted || 0;
      }
    } catch (err) {
      console.error("fetchStats error:", err);
    }
  }

  filterByStatus(status) {
    const filterSelect = document.getElementById("statusFilter");
    let filterValue = "all";

    if (status === "draft") filterValue = "draft";
    else if (status === "accepted") filterValue = "accepted";
    else if (status === "expired") filterValue = "expired";
    else if (status === "converted") filterValue = "converted";

    if (filterSelect) {
      filterSelect.value = filterValue;
      this.currentFilter = filterValue;
      this.currentPage = 1;
      this.fetchQuotations();
    }
  }

  // ── CREATE MODAL ─────────────────────────────────────────────────────────
  openCreateModal() {
    this._isCreateMode = true;
    this._editingId = null;
    this._currentQuotationStatus = null;
    this._rowCounter = 0;

    const overlay = document.getElementById("QuotationEditModal");
    if (!overlay) {
      this.showNotification("Error: Modal element not found", "error");
      return;
    }

    const titleEl = document.getElementById("qeModalTitle");
    const subtitleEl = document.getElementById("qeQuoteNumber");
    if (titleEl) titleEl.textContent = "Create New Quotation";
    if (subtitleEl) subtitleEl.textContent = "New Quotation";

    // Hide delivery receipt button for new quotations
    this._toggleDeliveryReceiptButton(false);

    overlay.style.display = "flex";
    document.body.style.overflow = "hidden";

    this._clearModalFields();

    const tbody = document.getElementById("qeItemsBody");
    if (tbody) tbody.innerHTML = "";
    this._appendItemRow();
    this.recalcTotals();
  }

  // ── EDIT MODAL ───────────────────────────────────────────────────────────
  async openEditModal(id) {
    this._isCreateMode = false;
    this._editingId = id;
    this._rowCounter = 0;

    const titleEl = document.getElementById("qeModalTitle");
    if (titleEl) titleEl.textContent = "Edit Quotation";

    const overlay = document.getElementById("QuotationEditModal");
    if (!overlay) {
      this.showNotification("Error: Modal element not found", "error");
      return;
    }

    overlay.style.display = "flex";
    document.body.style.overflow = "hidden";

    await new Promise((resolve) => setTimeout(resolve, 50));

    const modal = overlay.querySelector(".qe-modal");
    let loader = this._showLoader(modal);

    try {
      const response = await fetch(`${this.config.getSingleApiUrl}?id=${id}`);
      const result = await this._parseJSON(response);

      if (!result.success) {
        this.showNotification(
          result.message || "Failed to load quotation",
          "error",
        );
        this.closeEditModal();
        return;
      }

      this._currentQuotationStatus = result.data.quotation.status;
      const isAudited = result.data.quotation.audited == 1;
      const isConverted = result.data.quotation.status === "converted";

      // Show delivery receipt button only if status is 'accepted'
      this._toggleDeliveryReceiptButton(
        result.data.quotation.status === "accepted",
      );

      // Show Create Audit button only if status is 'converted' AND not audited
      this._toggleAuditButton(isConverted && !isAudited);

      this._populateEditModal(result.data.quotation, result.data.items || []);
    } catch (err) {
      console.error("openEditModal error:", err);
      this.showNotification("Error loading quotation: " + err.message, "error");
      this.closeEditModal();
    } finally {
      if (loader) loader.remove();
    }
  }
  _toggleDeliveryReceiptButton(show) {
    const btn = document.getElementById("qeDeliveryReceiptBtn");
    if (btn) {
      btn.style.display = show ? "inline-flex" : "none";
    }
  }
  _toggleAuditButton(show) {
    const btn = document.getElementById("qeCreateAuditBtn");
    if (btn) {
      btn.style.display = show ? "inline-flex" : "none";
    }
  }
  _showLoader(modal) {
    const loader = document.createElement("div");
    loader.className = "qe-loading";
    loader.style.cssText =
      "position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,0.85);z-index:10;font-size:14px;gap:8px;";
    loader.innerHTML =
      '<i class="fa-solid fa-spinner fa-spin"></i> Loading quotation...';
    modal.style.position = "relative";
    modal.appendChild(loader);
    return loader;
  }

  closeEditModal() {
    const overlay = document.getElementById("QuotationEditModal");
    if (overlay) overlay.style.display = "none";
    document.body.style.overflow = "";
    this._editingId = null;
    this._isCreateMode = false;
    this._currentQuotationStatus = null;
    this._rowCounter = 0;
    this._toggleDeliveryReceiptButton(false);
    this._toggleAuditButton(false); // Add this line
  }

  _populateEditModal(quotation, items) {
    this._clearModalFields();

    const sub = document.getElementById("qeQuoteNumber");
    if (sub) sub.textContent = quotation.quote_number || "New Quotation";

    this._setVal("qeClientName", quotation.client_name || "");
    this._setVal("qeContactPerson", quotation.contact_person || "");
    this._setVal("qeEmail", quotation.email || "");
    this._setVal("qePhone", quotation.phone || "");
    this._setVal("qeAddress", quotation.address || "");
    this._setVal("qeTax", quotation.tax ?? 0);
    this._setVal("qeDiscount", quotation.discount ?? 0);
    this._setVal("qeNotes", quotation.notes || "");

    const tbody = document.getElementById("qeItemsBody");
    if (tbody) tbody.innerHTML = "";

    if (items && items.length > 0) {
      items.forEach((item) => this._appendItemRow(item));
    } else {
      this._appendItemRow();
    }

    this.recalcTotals();
  }

  _clearModalFields() {
    [
      "qeClientName",
      "qeContactPerson",
      "qeEmail",
      "qePhone",
      "qeAddress",
      "qeTax",
      "qeDiscount",
      "qeNotes",
    ].forEach((id) => {
      const el = document.getElementById(id);
      if (el) el.value = "";
    });
  }

  _setVal(id, value) {
    const el = document.getElementById(id);
    if (el) el.value = value;
  }

  // ── ENHANCED ITEM ROWS with Rich Text Editor and Product Suggestions ────
  addItemRow() {
    this._appendItemRow();
    this.recalcTotals();
  }

  _appendItemRow(item = {}) {
    const tbody = document.getElementById("qeItemsBody");
    if (!tbody) return;

    const rid = ++this._rowCounter;
    const qty = item.quantity ?? "";
    const price = item.unit_price ?? "";
    const total = parseFloat(item.total || 0).toFixed(2);
    const descHtml = this._sanitiseHtml(item.description || "");

    const tr = document.createElement("tr");
    tr.setAttribute("data-row-id", rid);
    tr.innerHTML = `
      <tr>
        <input type="number" class="qe-item-input qe-qty" data-row="${rid}"
          value="${this._esc(String(qty))}" min="0" step="1" placeholder="1"
          oninput="quotationManager._rowCalc(${rid})">
      </td>
      <td class="qe-desc-cell" style="position: relative;">
        <div class="qe-editor-toolbar">
          <button type="button" class="qe-toolbar-btn" title="Bold" data-cmd="bold">
            <b>B</b>
          </button>
          <button type="button" class="qe-toolbar-btn" title="Italic" data-cmd="italic">
            <i>I</i>
          </button>
          <button type="button" class="qe-toolbar-btn" title="Underline" data-cmd="underline">
            <u>U</u>
          </button>
          <span class="toolbar-sep">|</span>
          <button type="button" class="qe-toolbar-btn" title="Bullet List" data-cmd="insertUnorderedList">
            • List
          </button>
          <button type="button" class="qe-toolbar-btn" title="Numbered List" data-cmd="insertOrderedList">
            1. List
          </button>
          <span class="toolbar-sep">|</span>
          <button type="button" class="qe-toolbar-btn" title="Indent" data-cmd="indent">
            → Indent
          </button>
          <button type="button" class="qe-toolbar-btn" title="Outdent" data-cmd="outdent">
            ← Outdent
          </button>
          <span class="toolbar-sep">|</span>
          <button type="button" class="qe-toolbar-btn" title="Clear Formatting" data-cmd="removeFormat">
            Clear
          </button>
          <button type="button" class="qe-toolbar-btn" title="Search Product" data-cmd="searchProduct">
            🔍 Product
          </button>
        </div>
        <div class="qe-editor-content qe-desc" data-row="${rid}"
          contenteditable="true" spellcheck="true"
        >${descHtml}</div>
      </td>
      <td>
        <input type="number" class="qe-item-input qe-price" data-row="${rid}"
          value="${this._esc(String(price))}" min="0" step="0.01" placeholder="0.00"
          oninput="quotationManager._rowCalc(${rid})">
      </td>
      <td>
        <span class="qe-item-total" id="qeRowTotal_${rid}">₱ ${total}</span>
      </td>
      <td>
        <button class="qe-btn-remove" onclick="quotationManager._removeRow(${rid})" title="Remove">
          <i class="fa-solid fa-trash"></i>
        </button>
      </td>
    `;

    tbody.appendChild(tr);

    // Attach toolbar button events
    this._attachToolbarEvents(tr, rid);
  }

  _attachToolbarEvents(tr, rid) {
    const toolbarBtns = tr.querySelectorAll(".qe-toolbar-btn");
    const editor = tr.querySelector(".qe-desc");

    toolbarBtns.forEach((btn) => {
      btn.addEventListener("click", (e) => {
        e.preventDefault();
        const cmd = btn.dataset.cmd;

        if (cmd === "searchProduct") {
          this._showProductSuggestions(editor, rid);
        } else {
          editor.focus();
          document.execCommand(cmd, false, null);
          editor.focus();
        }
      });
    });
  }

  async _showProductSuggestions(editor, rid) {
    // Remove existing dropdown
    const existingDropdown = document.querySelector(".qe-suggestion-dropdown");
    if (existingDropdown) existingDropdown.remove();

    // Get editor position
    const rect = editor.getBoundingClientRect();
    const viewportWidth = window.innerWidth;

    // Determine best position (left or right)
    let leftPos;
    const dropdownWidth = 320;

    // If editor is near the right edge, position dropdown to the left
    if (rect.right + dropdownWidth > viewportWidth) {
      leftPos = rect.left - dropdownWidth + rect.width;
    } else {
      leftPos = rect.right;
    }

    // Create dropdown container
    const dropdown = document.createElement("div");
    dropdown.className = "qe-suggestion-dropdown";
    dropdown.style.cssText = `
        position: fixed;
        top: ${rect.top}px;
        left: ${leftPos}px;
        width: ${dropdownWidth}px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        z-index: 100050;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        max-height: 400px;
    `;

    // Add header with search
    dropdown.innerHTML = `
        <div class="qe-suggestion-header">
            <div class="qe-suggestion-search-wrapper">
                <i class="fa-solid fa-search"></i>
                <input type="text" class="qe-suggestion-search" placeholder="Search materials..." autocomplete="off">
            </div>
            <button class="qe-suggestion-close" title="Close">×</button>
        </div>
        <div class="qe-suggestion-list">
            <div class="qe-suggestion-loading">
                <i class="fa-solid fa-spinner fa-spin"></i> Loading materials...
            </div>
        </div>
    `;

    document.body.appendChild(dropdown);

    const searchInput = dropdown.querySelector(".qe-suggestion-search");
    const listContainer = dropdown.querySelector(".qe-suggestion-list");
    const closeBtn = dropdown.querySelector(".qe-suggestion-close");

    let allMaterials = [];

    // Fetch materials
    try {
      const response = await fetch(
        "../../api/admin_site/get_products_for_quotation.php",
      );
      const result = await response.json();

      if (!result.success || !result.products || result.products.length === 0) {
        listContainer.innerHTML =
          '<div class="qe-suggestion-empty"><i class="fa-solid fa-box-open"></i> No materials available</div>';
        return;
      }

      allMaterials = result.products;
      this.renderMaterialList(allMaterials, listContainer, editor, rid);
    } catch (error) {
      console.error("Error loading materials:", error);
      listContainer.innerHTML =
        '<div class="qe-suggestion-empty"><i class="fa-solid fa-exclamation-triangle"></i> Error loading materials</div>';
    }

    // Search functionality
    searchInput.addEventListener("input", (e) => {
      const searchTerm = e.target.value.toLowerCase();
      const filtered = allMaterials.filter(
        (material) =>
          material.name.toLowerCase().includes(searchTerm) ||
          (material.type && material.type.toLowerCase().includes(searchTerm)),
      );
      this.renderMaterialList(filtered, listContainer, editor, rid);
    });

    // Close dropdown when clicking outside
    const closeDropdown = (e) => {
      if (!dropdown.contains(e.target)) {
        dropdown.remove();
        document.removeEventListener("click", closeDropdown);
        document.removeEventListener("keydown", escapeHandler);
      }
    };

    const escapeHandler = (e) => {
      if (e.key === "Escape") {
        dropdown.remove();
        document.removeEventListener("click", closeDropdown);
        document.removeEventListener("keydown", escapeHandler);
      }
    };

    setTimeout(() => {
      document.addEventListener("click", closeDropdown);
      document.addEventListener("keydown", escapeHandler);
      searchInput.focus();
    }, 100);

    // Close button
    closeBtn.addEventListener("click", () => {
      dropdown.remove();
    });
  }

  renderMaterialList(materials, listContainer, editor, rid) {
    if (!materials || materials.length === 0) {
      listContainer.innerHTML =
        '<div class="qe-suggestion-empty"><i class="fa-solid fa-search"></i> No matching materials found</div>';
      return;
    }

    listContainer.innerHTML = materials
      .map(
        (material) => `
        <div class="qe-suggestion-item" data-id="${material.id}">
            <div class="qe-suggestion-name">${this._esc(material.name)}</div>
            <div class="qe-suggestion-details">
                ${material.type ? `<span class="qe-suggestion-type"><i class="fa-solid fa-tag"></i> ${this._esc(material.type)}</span>` : ""}
                <span class="qe-suggestion-price">₱ ${parseFloat(material.price || 0).toFixed(2)}</span>
                ${material.stock !== undefined ? `<span class="qe-suggestion-stock"><i class="fa-solid fa-box"></i> Stock: ${material.stock}</span>` : ""}
            </div>
        </div>
    `,
      )
      .join("");

    // Add click handlers
    listContainer.querySelectorAll(".qe-suggestion-item").forEach((item) => {
      item.addEventListener("click", () => {
        const materialId = parseInt(item.dataset.id);
        const material = materials.find((m) => m.id === materialId);

        if (material) {
          // Insert material name into editor
          editor.focus();
          document.execCommand("insertText", false, material.name);

          // Set unit price
          const priceInput = document.querySelector(
            `.qe-price[data-row="${rid}"]`,
          );
          if (priceInput && material.price) {
            priceInput.value = parseFloat(material.price).toFixed(2);
            this._rowCalc(rid);
          }

          // Show success indicator
          this.showMaterialSelectedFeedback(editor);

          // Close dropdown
          const dropdown = document.querySelector(".qe-suggestion-dropdown");
          if (dropdown) dropdown.remove();
        }
      });
    });
  }

  showMaterialSelectedFeedback(editor) {
    // Add temporary highlight effect
    const originalBorder = editor.style.border;
    editor.style.transition = "all 0.2s";
    editor.style.border = "2px solid #10b981";
    editor.style.boxShadow = "0 0 0 2px rgba(16,185,129,0.2)";

    setTimeout(() => {
      editor.style.border = originalBorder;
      editor.style.boxShadow = "none";
    }, 1000);
  }

  _cmd(command, value = null) {
    document.execCommand(command, false, value);
  }

  _removeRow(rid) {
    const tbody = document.getElementById("qeItemsBody");
    if (!tbody) return;
    const row = tbody.querySelector(`tr[data-row-id="${rid}"]`);
    if (row) row.remove();
    this.recalcTotals();
  }

  _rowCalc(rid) {
    const tbody = document.getElementById("qeItemsBody");
    if (!tbody) return;

    const tr = tbody.querySelector(`tr[data-row-id="${rid}"]`);
    if (!tr) return;

    const qty = parseFloat(tr.querySelector(".qe-qty")?.value) || 0;
    const price = parseFloat(tr.querySelector(".qe-price")?.value) || 0;
    const total = qty * price;

    const span = document.getElementById(`qeRowTotal_${rid}`);
    if (span) span.textContent = `₱ ${total.toFixed(2)}`;

    this.recalcTotals();
  }

  recalcTotals() {
    let subtotal = 0;

    document.querySelectorAll("#qeItemsBody tr[data-row-id]").forEach((tr) => {
      const rid = tr.getAttribute("data-row-id");
      const qty = parseFloat(tr.querySelector(".qe-qty")?.value) || 0;
      const price = parseFloat(tr.querySelector(".qe-price")?.value) || 0;
      const row = qty * price;
      subtotal += row;

      const span = document.getElementById(`qeRowTotal_${rid}`);
      if (span) span.textContent = `₱ ${row.toFixed(2)}`;
    });

    const taxPct = parseFloat(document.getElementById("qeTax")?.value) || 0;
    const discount =
      parseFloat(document.getElementById("qeDiscount")?.value) || 0;
    const taxAmt = subtotal * (taxPct / 100);
    const grand = Math.max(0, subtotal + taxAmt - discount);

    const subEl = document.getElementById("qeSubtotal");
    const grandEl = document.getElementById("qeGrandTotal");
    if (subEl) subEl.textContent = `₱ ${subtotal.toFixed(2)}`;
    if (grandEl) grandEl.textContent = `₱ ${grand.toFixed(2)}`;
  }

  // ── COLLECT FORM DATA (with address) ─────────────────────────────────────
  _collectFormData() {
    const items = [];

    document.querySelectorAll("#qeItemsBody tr[data-row-id]").forEach((tr) => {
      const qty = parseFloat(tr.querySelector(".qe-qty")?.value) || 0;
      const price = parseFloat(tr.querySelector(".qe-price")?.value) || 0;
      const editor = tr.querySelector(".qe-desc[contenteditable]");
      const desc = editor ? editor.innerHTML.trim() : "";
      const descText = editor
        ? (editor.innerText || editor.textContent || "").trim()
        : "";

      if (descText.length > 0) {
        items.push({
          description: desc,
          quantity: qty,
          unit_price: price,
        });
      }
    });

    return {
      id: this._isCreateMode ? null : this._editingId,
      client_name: (
        document.getElementById("qeClientName")?.value || ""
      ).trim(),
      contact_person: (
        document.getElementById("qeContactPerson")?.value || ""
      ).trim(),
      email: (document.getElementById("qeEmail")?.value || "").trim(),
      phone: (document.getElementById("qePhone")?.value || "").trim(),
      address: (document.getElementById("qeAddress")?.value || "").trim(),
      tax: parseFloat(document.getElementById("qeTax")?.value) || 0,
      discount: parseFloat(document.getElementById("qeDiscount")?.value) || 0,
      notes: (document.getElementById("qeNotes")?.value || "").trim(),
      items,
    };
  }

  _validatePayload(payload) {
    if (!payload.client_name) {
      this.showNotification("Client name is required.", "error");
      return false;
    }
    if (payload.items.length === 0) {
      this.showNotification(
        "At least one item with a description is required.",
        "error",
      );
      return false;
    }
    return true;
  }

  _setButtonsDisabled(disabled) {
    const saveBtn = document.getElementById("qeSaveBtn");
    const savePdfBtn = document.getElementById("qeSavePdfBtn");
    const deliveryBtn = document.getElementById("qeDeliveryReceiptBtn");
    if (saveBtn) saveBtn.disabled = disabled;
    if (savePdfBtn) savePdfBtn.disabled = disabled;
    if (deliveryBtn) deliveryBtn.disabled = disabled;
  }

  // ── SAVE (Create or Update) ──────────────────────────────────────────────
  async saveQuotation() {
    if (this._isSaving) {
      this.showNotification("Save in progress, please wait...", "info");
      return;
    }

    const payload = this._collectFormData();
    if (!this._validatePayload(payload)) return;

    this._isSaving = true;
    this._setButtonsDisabled(true);

    try {
      const apiUrl = this._isCreateMode
        ? this.config.createApiUrl
        : this.config.updateApiUrl;
      const response = await fetch(apiUrl, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload),
      });
      const result = await this._parseJSON(response);

      if (result.success) {
        this.showNotification(
          result.message || "Quotation saved successfully!",
          "success",
        );
        this.closeEditModal();
        await this.fetchQuotations();
        await this.fetchStats();
      } else {
        this.showNotification(
          result.message || "Failed to save quotation",
          "error",
        );
      }
    } catch (err) {
      console.error("saveQuotation error:", err);
      this.showNotification("Error saving quotation: " + err.message, "error");
    } finally {
      this._isSaving = false;
      this._setButtonsDisabled(false);
    }
  }

  // ── SAVE + GENERATE PDF ──────────────────────────────────────────────────
  async saveAndGeneratePDF() {
    if (this._isSaving) {
      this.showNotification("Save in progress, please wait...", "info");
      return;
    }

    const payload = this._collectFormData();
    if (!this._validatePayload(payload)) return;

    this._isSaving = true;
    this._setButtonsDisabled(true);

    try {
      const apiUrl = this._isCreateMode
        ? this.config.createApiUrl
        : this.config.updateApiUrl;
      const response = await fetch(apiUrl, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload),
      });
      const result = await this._parseJSON(response);

      if (result.success) {
        const quotationId = result.quotation_id || this._editingId;
        this.showNotification("Saved! Generating PDF...", "success");
        this.closeEditModal();
        await this.fetchQuotations();
        await this.fetchStats();
        await this.generatePDF(quotationId);
      } else {
        this.showNotification(
          result.message || "Failed to save quotation",
          "error",
        );
      }
    } catch (err) {
      console.error("saveAndGeneratePDF error:", err);
      this.showNotification("Error: " + err.message, "error");
    } finally {
      this._isSaving = false;
      this._setButtonsDisabled(false);
    }
  }

  // ── STATUS UPDATE ────────────────────────────────────────────────────────
  async updateStatus(id, status) {
    try {
      const response = await fetch(this.config.statusApiUrl, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id, status }),
      });
      const result = await this._parseJSON(response);

      if (result.success) {
        this.showNotification(`Status updated to "${status}"`, "success");

        // Refresh the table and stats
        await this.fetchQuotations();
        await this.fetchStats();
      } else {
        this.showNotification(
          result.message || "Failed to update status",
          "error",
        );
      }
    } catch (err) {
      console.error("updateStatus error:", err);
      this.showNotification("Error updating status: " + err.message, "error");
    }
  }

  // ── DELIVERY RECEIPT FROM MODAL ──────────────────────────────────────────
  async generateDeliveryReceiptFromModal() {
    const quotationId = this._editingId;

    if (!quotationId) {
      this.showNotification(
        "No quotation selected for delivery receipt.",
        "error",
      );
      return;
    }

    this.showNotification(
      "Generating Delivery Receipt, please wait...",
      "info",
    );

    try {
      const response = await fetch(this.config.deliveryReceiptApiUrl, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id: quotationId }),
      });

      const result = await this._parseJSON(response);

      if (result.success) {
        this.showNotification(
          "Delivery Receipt generated successfully!",
          "success",
        );
        if (result.pdf_url) {
          window.open(result.pdf_url, "_blank");
        }

        // After generating delivery receipt, update status to converted
        const statusResponse = await fetch(this.config.statusApiUrl, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ id: quotationId, status: "converted" }),
        });
        await this._parseJSON(statusResponse);

        // Hide the delivery receipt button since status is now converted
        this._toggleDeliveryReceiptButton(false);

        // Show the Create Audit button since status is now converted
        this._toggleAuditButton(true);

        this.showNotification(
          "Please create an audit to track inventory usage.",
          "info",
        );

        await this.fetchQuotations();
        await this.fetchStats();
      } else {
        this.showNotification(
          result.message || "Failed to generate Delivery Receipt",
          "error",
        );
      }
    } catch (err) {
      console.error("generateDeliveryReceiptFromModal error:", err);
      this.showNotification(
        "Error generating Delivery Receipt: " + err.message,
        "error",
      );
    }
  }

  // Helper to get quote number from modal
  _getQuoteNumber() {
    const quoteNumEl = document.getElementById("qeQuoteNumber");
    return quoteNumEl ? quoteNumEl.textContent : "";
  }
  // ── SHOW AUDIT PROMPT MODAL ──────────────────────────────────────────────
  showAuditPromptModal(quotationId, quotationNumber) {
    // Remove existing modal if any
    const existingModal = document.getElementById("auditPromptModal");
    if (existingModal) existingModal.remove();

    const modalHtml = `
        <div id="auditPromptModal" class="audit-prompt-overlay">
            <div class="audit-prompt-container">
                <div class="audit-prompt-header">
                    <i class="fa-solid fa-clipboard-list"></i>
                    <h3>Stock Audit Required</h3>
                </div>
                <div class="audit-prompt-body">
                    <p>Delivery Receipt has been generated for <strong>${this._esc(quotationNumber)}</strong>.</p>
                    <p>To complete the process, you need to create an inventory audit to track material consumption for this quotation.</p>
                    <div class="audit-prompt-info">
                        <i class="fa-solid fa-info-circle"></i>
                        <span>This will help maintain accurate inventory levels and track material costs.</span>
                    </div>
                </div>
                <div class="audit-prompt-footer">
                    <button class="audit-prompt-btn later" onclick="quotationManager.closeAuditPromptModal()">
                        <i class="fa-solid fa-clock"></i> Do Later
                    </button>
                    <button class="audit-prompt-btn proceed" onclick="quotationManager.proceedToInventoryAudit(${quotationId}, '${this._esc(quotationNumber)}')">
                        <i class="fa-solid fa-clipboard-list"></i> Create Audit Now
                    </button>
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML("beforeend", modalHtml);
    document.body.style.overflow = "hidden";

    // Add styles for the modal
    this.injectAuditPromptStyles();
  }

  injectAuditPromptStyles() {
    if (document.getElementById("auditPromptStyles")) return;

    const styles = `
        <style id="auditPromptStyles">
            .audit-prompt-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.6);
                z-index: 100000;
                display: flex;
                align-items: center;
                justify-content: center;
                animation: fadeIn 0.2s ease;
            }
            .audit-prompt-container {
                background: #fff;
                border-radius: 16px;
                width: 90%;
                max-width: 450px;
                box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                overflow: hidden;
                animation: slideUp 0.3s ease;
            }
            .audit-prompt-header {
                background: linear-gradient(135deg, #2563eb, #1e40af);
                color: white;
                padding: 20px 24px;
                text-align: center;
            }
            .audit-prompt-header i {
                font-size: 48px;
                margin-bottom: 12px;
            }
            .audit-prompt-header h3 {
                margin: 0;
                font-size: 1.3rem;
            }
            .audit-prompt-body {
                padding: 24px;
                text-align: center;
            }
            .audit-prompt-body p {
                margin: 0 0 12px 0;
                color: #374151;
                line-height: 1.5;
            }
            .audit-prompt-info {
                background: #eff6ff;
                border-left: 3px solid #2563eb;
                padding: 12px 16px;
                border-radius: 8px;
                margin-top: 16px;
                text-align: left;
                display: flex;
                gap: 10px;
                align-items: flex-start;
            }
            .audit-prompt-info i {
                color: #2563eb;
                font-size: 18px;
                margin-top: 2px;
            }
            .audit-prompt-info span {
                font-size: 13px;
                color: #1e40af;
            }
            .audit-prompt-footer {
                padding: 16px 24px 24px;
                display: flex;
                gap: 12px;
                justify-content: flex-end;
                border-top: 1px solid #e5e7eb;
            }
            .audit-prompt-btn {
                padding: 10px 20px;
                border-radius: 8px;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.2s;
                border: none;
            }
            .audit-prompt-btn.later {
                background: #f3f4f6;
                color: #374151;
            }
            .audit-prompt-btn.later:hover {
                background: #e5e7eb;
            }
            .audit-prompt-btn.proceed {
                background: #2563eb;
                color: white;
            }
            .audit-prompt-btn.proceed:hover {
                background: #1d4ed8;
                transform: translateY(-1px);
            }
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            @keyframes slideUp {
                from { transform: translateY(30px); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }
        </style>
    `;
    document.head.insertAdjacentHTML("beforeend", styles);
  }

  closeAuditPromptModal() {
    const modal = document.getElementById("auditPromptModal");
    if (modal) modal.remove();
    document.body.style.overflow = "";
  }
  proceedToInventoryAudit(quotationId, quotationNumber) {
    // Store in sessionStorage before redirect
    sessionStorage.setItem("pending_audit_quotation_id", quotationId);
    sessionStorage.setItem("pending_audit_quotation_number", quotationNumber);
    sessionStorage.setItem("pending_audit_timestamp", Date.now().toString());

    console.log("Stored pending audit:", { quotationId, quotationNumber });

    this.closeAuditPromptModal();
    this.closeEditModal();
    this.showNotification(
      "Redirecting to Inventory page to create audit...",
      "info",
    );

    // Redirect to inventory page
    window.location.href = "Inventory.php";
  }
  // ── CREATE AUDIT FROM MODAL ──────────────────────────────────────────
  createAuditFromModal() {
    const quotationId = this._editingId;
    const quoteNumber =
      document.getElementById("qeQuoteNumber")?.textContent || "";

    if (!quotationId) {
      this.showNotification("No quotation selected for audit.", "error");
      return;
    }

    // Store in sessionStorage before redirect
    sessionStorage.setItem("pending_audit_quotation_id", quotationId);
    sessionStorage.setItem("pending_audit_quotation_number", quoteNumber);
    sessionStorage.setItem("pending_audit_timestamp", Date.now().toString());

    this.showNotification(
      "Redirecting to Inventory page to create audit...",
      "info",
    );

    // Close the edit modal
    this.closeEditModal();

    // Redirect to inventory page
    window.location.href = "Inventory.php";
  }
  // ── PDF GENERATION ────────────────────────────────────────────────────────
  async generatePDF(id) {
    if (!id) {
      this.showNotification("Error: No quotation ID provided.", "error");
      return;
    }
    this.showNotification("Generating PDF, please wait...", "info");

    try {
      const response = await fetch(this.config.pdfApiUrl, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id }),
      });
      const result = await this._parseJSON(response);

      if (result.success) {
        this.showNotification("PDF generated successfully!", "success");
        window.open(result.pdf_url, "_blank");
      } else {
        this.showNotification(
          result.message || "Failed to generate PDF",
          "error",
        );
      }
    } catch (err) {
      console.error("generatePDF error:", err);
      this.showNotification("Error generating PDF: " + err.message, "error");
    }
  }

  // ── FETCH & RENDER TABLE ─────────────────────────────────────────────────
  async fetchQuotations() {
    const tbody = document.getElementById(this.config.tableBodyId);
    if (tbody) {
      tbody.innerHTML = `<tr><td colspan="10" class="text-center" style="padding:40px;"><i class="fa-solid fa-spinner fa-spin"></i> Loading quotations...</td></tr>`;
    }

    try {
      const params = new URLSearchParams({
        page: this.currentPage,
        limit: this.config.limit,
        status: this.currentFilter !== "all" ? this.currentFilter : "",
        search: this.currentSearch,
      });

      const response = await fetch(`${this.config.apiUrl}?${params}`);
      const result = await this._parseJSON(response);

      if (result.success) {
        this._renderTable(result.data);
        this._renderPagination(result.pagination);
      } else {
        this.showNotification(
          result.message || "Failed to load quotations",
          "error",
        );
        if (tbody)
          tbody.innerHTML = `<tr><td colspan="9" class="text-center">Failed to load quotations.</td></tr>`;
      }
    } catch (err) {
      console.error("fetchQuotations error:", err);
      this.showNotification(
        "Error fetching quotations: " + err.message,
        "error",
      );
    }
  }

  _renderTable(quotations) {
    const tbody = document.getElementById(this.config.tableBodyId);
    if (!tbody) return;

    if (!quotations || quotations.length === 0) {
      tbody.innerHTML = `<tr><td colspan="9" class="text-center">No quotations found.</td></tr>`;
      return;
    }

    tbody.innerHTML = quotations
      .map(
        (q) => `
        <tr>
            <td class="col-quote-id" style="min-width: 100px;">${this._esc(q.quote_number)}</div></td>
            <td class="col-customer" style="min-width: 120px; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${this._esc(q.client_name)}">${this._esc(q.client_name)}</div></td>
            <td class="col-contact" style="min-width: 100px; max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${this._esc(q.contact_person || "")}">${this._esc(q.contact_person || "—")}</div></div></td>
            <td class="col-total" style="min-width: 90px; text-align: right;">₱ ${parseFloat(q.total || 0).toFixed(2)}</div></div></td>
            <td class="col-date" style="min-width: 90px;">${new Date(q.created_at).toLocaleDateString()}</div></div></td>
            <td class="col-status" style="min-width: 130px;">
                <select class="status-select" data-id="${q.id}"
                    onchange="quotationManager.updateStatus(${q.id}, this.value)"
                    style="padding: 4px 8px; font-size: 11px; border-radius: 4px;">
                    <option value="draft"     ${q.status === "draft" ? "selected" : ""}>Draft</option>
                    <option value="accepted"  ${q.status === "accepted" ? "selected" : ""}>Accepted</option>
                    <option value="expired"   ${q.status === "expired" ? "selected" : ""}>Declined</option>
                    <option value="converted" ${q.status === "converted" ? "selected" : ""}>Delivered</option>
                </select>
            </div></div></td>
            <td class="col-audit" style="min-width: 100px;">
                <span class="audit-badge ${q.audited == 1 ? "audited" : "not-audited"}" style="font-size: 11px; padding: 3px 8px;">
                    ${q.audited == 1 ? '<i class="fa-solid fa-check-circle"></i> Audited' : '<i class="fa-solid fa-clock"></i> Pending'}
                </span>
            </div></div></td>
            <td class="col-actions" style="min-width: 100px;">
                <div class="action-buttons" style="display: flex; gap: 5px; justify-content: flex-start;">
                    <button class="btn-edit" onclick="quotationManager.openEditModal(${q.id})" title="Edit" style="background: none; border: none; cursor: pointer; padding: 4px 8px; border-radius: 4px; color: #2563eb;">
                        <i class="fa-solid fa-edit"></i>
                    </button>
                    <button class="btn-pdf" onclick="quotationManager.generatePDF(${q.id})" title="Generate PDF" style="background: none; border: none; cursor: pointer; padding: 4px 8px; border-radius: 4px; color: #dc2626;">
                        <i class="fa-solid fa-file-pdf"></i>
                    </button>
                    <button class="btn-delete" onclick="quotationManager.deleteQuotation(${q.id}, '${this._esc(q.quote_number)}')" title="Delete" style="background: none; border: none; cursor: pointer; padding: 4px 8px; border-radius: 4px; color: #6b7280;">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div></div></td>
        </tr>
    `,
      )
      .join("");
  }
  // ── DELETE ────────────────────────────────────────────────────────────────
  deleteQuotation(id, quoteNumber = "") {
    document
      .querySelectorAll(".qm-confirm-overlay")
      .forEach((el) => el.remove());

    const overlay = document.createElement("div");
    overlay.className = "qm-confirm-overlay";
    overlay.innerHTML = `
      <div class="qm-confirm-box">
        <div class="qm-confirm-icon">
          <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div class="qm-confirm-title">Delete Quotation?</div>
        <div class="qm-confirm-msg">
          You are about to permanently delete
          <strong>${this._esc(quoteNumber || String(id))}</strong>.<br>
          This action cannot be undone.
        </div>
        <div class="qm-confirm-actions">
          <button class="qm-confirm-cancel" id="qmCancelDelete">Cancel</button>
          <button class="qm-confirm-delete" id="qmConfirmDelete">
            <i class="fa-solid fa-trash"></i> Delete
          </button>
        </div>
      </div>`;

    document.body.appendChild(overlay);

    overlay.addEventListener("click", (e) => {
      if (e.target === overlay) overlay.remove();
    });

    document.getElementById("qmCancelDelete").addEventListener("click", () => {
      overlay.remove();
    });

    document
      .getElementById("qmConfirmDelete")
      .addEventListener("click", async () => {
        const deleteBtn = document.getElementById("qmConfirmDelete");
        const cancelBtn = document.getElementById("qmCancelDelete");
        deleteBtn.disabled = true;
        cancelBtn.disabled = true;
        deleteBtn.innerHTML =
          '<i class="fa-solid fa-spinner fa-spin"></i> Deleting...';

        try {
          const response = await fetch(this.config.deleteApiUrl, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id }),
          });
          const result = await this._parseJSON(response);

          overlay.remove();

          if (result.success) {
            this.showNotification(
              result.message || "Quotation deleted successfully.",
              "success",
            );
            await this.fetchQuotations();
            await this.fetchStats();
          } else {
            this.showNotification(
              result.message || "Failed to delete quotation",
              "error",
            );
          }
        } catch (err) {
          overlay.remove();
          console.error("deleteQuotation error:", err);
          this.showNotification(
            "Error deleting quotation: " + err.message,
            "error",
          );
        }
      });
  }

  // ── PAGINATION ───────────────────────────────────────────────────────────
  _renderPagination(pagination) {
    const pageInfo = document.getElementById("pageInfo");
    const prevBtn = document.getElementById("prevPage");
    const nextBtn = document.getElementById("nextPage");

    if (pageInfo) {
      pageInfo.textContent = `Page ${pagination.page} of ${pagination.totalPages} (Total: ${pagination.total})`;
    }
    if (prevBtn) prevBtn.disabled = pagination.page <= 1;
    if (nextBtn) nextBtn.disabled = pagination.page >= pagination.totalPages;
  }

  nextPage() {
    this.currentPage++;
    this.fetchQuotations();
    this._scrollToTop();
  }

  prevPage() {
    if (this.currentPage > 1) {
      this.currentPage--;
      this.fetchQuotations();
      this._scrollToTop();
    }
  }

  _scrollToTop() {
    const el = document.querySelector(".content-body");
    if (el) el.scrollIntoView({ behavior: "smooth", block: "start" });
  }

  refresh() {
    this.currentPage = 1;
    this.currentFilter = "all";
    this.currentSearch = "";
    const sf = document.getElementById("statusFilter");
    if (sf) sf.value = "all";
    const si = document.getElementById("quotationSearch");
    if (si) si.value = "";
    this.fetchQuotations();
    this.fetchStats();
  }

  // ── UTILITIES ────────────────────────────────────────────────────────────
  _sanitiseHtml(html) {
    const allowed = new Set([
      "B",
      "STRONG",
      "I",
      "EM",
      "U",
      "UL",
      "OL",
      "LI",
      "BR",
      "P",
      "SPAN",
      "DIV",
    ]);
    const tmp = document.createElement("div");
    tmp.innerHTML = html;

    const clean = (node) => {
      [...node.childNodes].forEach((child) => {
        if (child.nodeType === Node.ELEMENT_NODE) {
          if (!allowed.has(child.tagName)) {
            while (child.firstChild)
              child.parentNode.insertBefore(child.firstChild, child);
            child.remove();
          } else {
            [...child.attributes].forEach((attr) =>
              child.removeAttribute(attr.name),
            );
            clean(child);
          }
        }
      });
    };

    clean(tmp);
    return tmp.innerHTML;
  }

  async _parseJSON(response) {
    const raw = await response.text();
    console.log("Raw response:", raw.substring(0, 500));
    try {
      return JSON.parse(raw);
    } catch (e) {
      throw new Error(`Invalid JSON: ${raw.substring(0, 200)}`);
    }
  }

  _esc(text) {
    const map = {
      "&": "&amp;",
      "<": "&lt;",
      ">": "&gt;",
      '"': "&quot;",
      "'": "&#039;",
    };
    return text ? String(text).replace(/[&<>"']/g, (m) => map[m]) : "";
  }

  showNotification(message, type = "info") {
    document.querySelectorAll(".qm-notification").forEach((n) => n.remove());

    const colors = {
      success: "#4caf50",
      error: "#f44336",
      info: "#2196f3",
      warning: "#ff9800",
    };
    const el = document.createElement("div");
    el.className = "qm-notification";
    el.textContent = message;
    el.style.cssText = `
      position:fixed; top:20px; right:20px; z-index:99999;
      padding:14px 20px; border-radius:6px; font-size:14px;
      color:#fff; background:${colors[type] || colors.info};
      box-shadow:0 4px 12px rgba(0,0,0,.2);
      animation:qmSlideIn .25s ease;
      max-width:360px; word-break:break-word;`;
    document.body.appendChild(el);

    setTimeout(() => {
      el.style.animation = "qmSlideOut .25s ease forwards";
      setTimeout(() => el.remove(), 260);
    }, 3500);
  }
}

let quotationManager;
document.addEventListener("DOMContentLoaded", () => {
  quotationManager = new QuotationManager();
});
