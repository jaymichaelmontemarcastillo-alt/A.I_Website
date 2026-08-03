// ============================================================
//  admin_materials.js (COMPLETE - WITH ADD ITEM FUNCTIONALITY)
//
//  Manages material inventory UI and API interactions.
//  All function names, element IDs, and globals prefixed "mat"
//  to prevent collision with admin_products.js.
//
//  FEATURES:
//  - Robust JSON parsing with error handling
//  - Searchable material selector dropdown
//  - Audit/BOM creation with dynamic rows
//  - Import/Export functionality
//  - ADD NEW ITEM functionality
// ============================================================

/* ── API Endpoints ─────────────────────────────────────────── */
const MAT_API = {
  materials: "../../api/admin_site/inventory/get_materials.php",
  logs: "../../api/admin_site/inventory/get_materials_logs.php",
  update: "../../api/admin_site/inventory/update_materials_stock.php",
  materialsForAudit:
    "../../api/admin_site/inventory/get_materials_for_audit.php",
  createAudit: "../../api/admin_site/inventory/create_audit.php",
  getAuditDetails: "../../api/admin_site/inventory/get_audit_details.php",
  listAudits: "../../api/admin_site/inventory/list_audits.php",
  import: "../../api/admin_site/inventory/import_materials.php",
  export: "../../api/admin_site/inventory/export_materials.php",
  addItem: "../../api/admin_site/add_inventory_item.php",
  getTypes: "../../api/admin_site/inventory/get_types.php",
  deleteAll: "../../api/admin_site/inventory/delete_all_materials.php",
};

/* ── State ─────────────────────────────────────────────────── */
const matState = {
  materialsPage: 1,
  logsPage: 1,
  searchTimer: null,
  isUpdating: false,
  modal: {
    materialId: null,
    prevStock: 0,
    action: "add",
    location: "total_stock",
  },
};

// Store selector instances
let materialSelectors = [];
let rejectSelectors = [];
let auditMaterialsList = [];
let auditAutoCompute = true;

// Store type options for datalist
let matTypeOptions = [];

// Store pending quotations list
let pendingQuotationsList = [];
let selectedPendingQuotation = null;

// Shared helper for opening the audit modal from Quotation page
window.openQuotationAuditModal = async (
  quotationId,
  quotationNumber = null,
) => {
  if (!quotationId) {
    matShowToast("No quotation selected for audit.", "error");
    return;
  }

  if (quotationNumber) {
    window.currentAuditQuotationNumber = quotationNumber;
  }

  window.currentAuditQuotationId = quotationId;

  await window.matOpenAuditModalForQuotation?.(quotationId, quotationNumber);
};

/* ══════════════════════════════════════════════════════════════
   ENHANCED MATERIAL SELECTOR CLASS
══════════════════════════════════════════════════════════════ */
class MaterialSelector {
  constructor(container, onSelect, selectedMaterialId = null) {
    this.container = container;
    this.onSelect = onSelect;
    this.selectedMaterialId = selectedMaterialId;
    this.materials = [];
    this.filteredMaterials = [];
    this.isOpen = false;
    this.isLoading = false;
    this.init();
  }

  async init() {
    await this.loadMaterials();
    this.render();
    this.attachEvents();
  }

  async loadMaterials() {
    this.isLoading = true;
    if (this.container) {
      this.container.innerHTML =
        '<div class="material-selector-loading">Loading materials...</div>';
    }

    try {
      console.log("Fetching materials from:", MAT_API.materialsForAudit);
      const data = await matFetch(MAT_API.materialsForAudit);

      if (data && data.materials) {
        this.materials = data.materials;
        this.filteredMaterials = [...this.materials];
        console.log(`Loaded ${this.materials.length} materials successfully`);
      } else {
        console.warn("No materials data received:", data);
        this.materials = [];
        this.filteredMaterials = [];
      }
    } catch (err) {
      console.error("Failed to load materials:", err.message);
      this.materials = [];
      this.filteredMaterials = [];
      if (this.container) {
        this.container.innerHTML = `<div class="material-selector-error">
          <i class="fa-solid fa-exclamation-triangle"></i> 
          Error: ${err.message}
          <button onclick="this.closest('.material-selector')?.querySelector('.material-selector-input')?.click()">Retry</button>
        </div>`;
      }
    } finally {
      this.isLoading = false;
    }
  }

  render() {
    if (!this.container) return;

    const selectedMaterial = this.materials.find(
      (m) => m.id == this.selectedMaterialId,
    );

    this.container.innerHTML = `
      <div class="material-selector" data-selected-id="${this.selectedMaterialId || ""}">
        <div class="material-selector-input">
          <span class="selected-text">${selectedMaterial ? this.escapeHtml(selectedMaterial.material_name) : "🔍 Select Material..."}</span>
          <i class="fa-solid fa-chevron-down"></i>
        </div>
        <div class="material-selector-dropdown">
          <div class="material-selector-search">
            <input type="text" placeholder="Search materials by name or type..." class="search-input" autocomplete="off">
          </div>
          <div class="material-selector-list"></div>
        </div>
      </div>
    `;
    this.renderList();
  }

  renderList(searchTerm = "") {
    const listContainer = this.container.querySelector(
      ".material-selector-list",
    );
    if (!listContainer) return;

    let filtered = this.materials;
    if (searchTerm) {
      const term = searchTerm.toLowerCase();
      filtered = this.materials.filter(
        (m) =>
          (m.material_name && m.material_name.toLowerCase().includes(term)) ||
          (m.type && m.type.toLowerCase().includes(term)),
      );
    }
    this.filteredMaterials = filtered;

    if (filtered.length === 0) {
      listContainer.innerHTML = `
        <div class="no-results">
          <i class="fa-solid fa-box-open"></i> 
          ${this.materials.length === 0 ? "No materials available in database" : "No matching materials found"}
        </div>`;
      return;
    }

    listContainer.innerHTML = filtered
      .map(
        (material) => `
      <div class="material-selector-item ${material.id == this.selectedMaterialId ? "selected" : ""}" 
           data-id="${material.id}"
           data-name="${this.escapeHtml(material.material_name)}"
           data-unit-cost="${material.unit_cost || 0}"
           data-stock="${material.total_stock || 0}">
        <div class="material-info">
          <div class="material-name">${this.escapeHtml(material.material_name)}</div>
          <div class="material-details">
            ${material.type ? `<span class="material-type">${this.escapeHtml(material.type)}</span>` : ""}
            <span class="material-cost">💰 ₱${parseFloat(material.unit_cost || 0).toFixed(4)} / unit</span>
          </div>
        </div>
        <div class="material-stock">📦 Stock: ${material.total_stock || 0}</div>
      </div>
    `,
      )
      .join("");

    // Attach click event listeners to items
    listContainer
      .querySelectorAll(".material-selector-item")
      .forEach((item) => {
        item.addEventListener("click", (e) => {
          e.stopPropagation();
          const id = parseInt(item.dataset.id);
          const material = this.materials.find((m) => m.id === id);
          if (material) {
            this.selectedMaterialId = id;
            const selectedText = this.container.querySelector(".selected-text");
            if (selectedText) selectedText.textContent = material.material_name;
            if (this.onSelect && typeof this.onSelect === "function") {
              this.onSelect(material);
            }
            this.close();
            this.renderList(searchTerm);
          }
        });
      });
  }

  attachEvents() {
    const input = this.container.querySelector(".material-selector-input");
    const searchInput = this.container.querySelector(".search-input");

    if (input) {
      input.addEventListener("click", (e) => {
        e.stopPropagation();
        if (!this.isLoading) {
          this.toggle();
          setTimeout(() => {
            const search = this.container.querySelector(".search-input");
            if (search && this.isOpen) search.focus();
          }, 50);
        }
      });
    }

    if (searchInput) {
      searchInput.addEventListener("input", (e) =>
        this.renderList(e.target.value),
      );
      searchInput.addEventListener("click", (e) => e.stopPropagation());
    }

    document.addEventListener("click", (e) => {
      if (this.container && !this.container.contains(e.target)) {
        this.close();
      }
    });
  }

  toggle() {
    this.isOpen ? this.close() : this.open();
  }

  open() {
    const dropdown = this.container.querySelector(
      ".material-selector-dropdown",
    );
    const input = this.container.querySelector(".material-selector-input");
    if (dropdown && !this.isLoading) {
      dropdown.classList.add("show");
      this.isOpen = true;
      if (input) input.classList.add("open");
      const searchInput = this.container.querySelector(".search-input");
      if (searchInput) {
        searchInput.value = "";
        this.renderList("");
      }
    }
  }

  close() {
    const dropdown = this.container.querySelector(
      ".material-selector-dropdown",
    );
    const input = this.container.querySelector(".material-selector-input");
    if (dropdown) {
      dropdown.classList.remove("show");
      this.isOpen = false;
      if (input) input.classList.remove("open");
    }
  }

  getValue() {
    return this.selectedMaterialId;
  }

  getSelectedMaterial() {
    return this.materials.find((m) => m.id == this.selectedMaterialId);
  }

  escapeHtml(str) {
    if (!str) return "";
    return String(str)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#39;");
  }
}

/* ══════════════════════════════════════════════════════════════
   BOOT
══════════════════════════════════════════════════════════════ */
document.addEventListener("DOMContentLoaded", () => {
  console.log("🚀 Materials inventory page loaded");
  matInitModal();
  matInitToast();
  matInitAddItemModal();
  matInitLogDetailsModal();
  matLoadMaterials();
  matLoadLogs();
  matLoadTypes();
  setTimeout(matAddAuditListButton, 500);

  // Check for pending audit from quotation
  initPendingAuditCheck();
});

/* ══════════════════════════════════════════════════════════════
   MATERIALS
══════════════════════════════════════════════════════════════ */
const matLoadMaterials = async () => {
  const search = matGetVal("matSearchInput");
  const status = matGetVal("matStatusFilter");
  const sort = matGetVal("matSortSelect") || "name_asc";

  const params = new URLSearchParams({
    search: search.trim(),
    status,
    sort,
    page: matState.materialsPage,
    per_page: 10,
  });
  const tbody = document.getElementById("materialsTableBody");
  if (!tbody) return;
  tbody.innerHTML = matLoadingRow(8, "Loading materials…");

  try {
    const data = await matFetch(`${MAT_API.materials}?${params}`);
    if (!data.materials) throw new Error("Response missing 'materials' field");
    matRenderStats(data.stats || {});
    matRenderAlerts(data.materials);
    matRenderMaterialRows(data.materials);
    matRenderPager(data.pagination || {}, "matPager", "matPageInfo", (p) => {
      matState.materialsPage = p;
      matLoadMaterials();
    });
  } catch (err) {
    console.error("❌ matLoadMaterials failed:", err);
    tbody.innerHTML = matErrorRow(8, `Error: ${err.message}`);
  }
};

window.matReloadMaterials = (reset = false) => {
  if (reset) matState.materialsPage = 1;
  matLoadMaterials();
};
window.matDebouncedReload = () => {
  clearTimeout(matState.searchTimer);
  matState.searchTimer = setTimeout(() => window.matReloadMaterials(true), 350);
};

const matRenderStats = (s = {}) => {
  matSetText("matTotalStockValue", (s.total_stock || 0).toLocaleString());
  matSetText("matInStockValue", (s.in_stock || 0).toLocaleString());
  matSetText("matLowStockValue", (s.low_stock || 0).toLocaleString());
  matSetText("matOutOfStockValue", (s.out_of_stock || 0).toLocaleString());
};

const matRenderAlerts = (materials = []) => {
  const box = document.getElementById("matAlertsBox");
  const container = document.getElementById("matAlertsContainer");
  if (!box || !container) return;
  const alerts = materials.filter((m) => m.stock_status !== "in_stock");
  if (!alerts.length) {
    box.style.display = "none";
    return;
  }
  box.style.display = "block";
  container.innerHTML = alerts
    .map((m) => {
      const isOut = m.stock_status === "out_of_stock";
      const cls = isOut ? "alert-item danger" : "alert-item warning";
      const icon = isOut ? "fa-ban" : "fa-arrow-trend-down";
      const label = isOut ? "Out of Stock" : `Low Stock (${m.total_stock})`;
      return `<div class="${cls}"><i class="fa-solid ${icon}"></i><span><strong>${matEsc(m.material_name)}</strong> — ${label}</span><button class="alert-update-btn" data-id="${m.id}" data-name="${matEsc(m.material_name)}" data-stock="${m.total_stock}">Update</button></div>`;
    })
    .join("");
  container
    .querySelectorAll(".alert-update-btn")
    .forEach((btn) =>
      btn.addEventListener("click", () =>
        matOpenModal(btn.dataset.id, btn.dataset.name, btn.dataset.stock),
      ),
    );
};

const matRenderMaterialRows = (materials = []) => {
  const tbody = document.getElementById("materialsTableBody");
  if (!tbody) return;
  if (!materials.length) {
    tbody.innerHTML = matLoadingRow(8, "No materials found.");
    return;
  }
  tbody.innerHTML = materials
    .map((m) => {
      const badge = matStockBadge(m.stock_status, m.total_stock);
      const costPerUnit = m.unit_cost
        ? parseFloat(m.unit_cost).toFixed(4)
        : "—";
      return `<tr>
                  <td><div class="mat-cell"><span>${matEsc(m.material_name)}</span></div></td>
                  <td>${matEsc(m.type || "—")}</td>
                  <td><strong>${m.shop_stock}</strong></td>
                  <td><strong>${m.ph_stock}</strong></td>
                  <td><strong>${m.total_stock}</strong></td>
                  <td>${costPerUnit}</td>
                  <td><span class="badge ${badge.cls}">${badge.label}</span></td>
                  <td class="mat_table_action_col">
                    <button class="btn-action mat-edit-item-btn" 
                      data-id="${m.id}" 
                      data-name="${matEsc(m.material_name)}" 
                      data-type="${matEsc(m.type || "")}" 
                      data-shop="${m.shop_stock}" 
                      data-ph="${m.ph_stock}" 
                      data-cost="${m.unit_cost}">
                      <i class="fa-solid fa-pencil"></i>
                    </button>
                   </div>
                </tr>`;
    })
    .join("");

  tbody.querySelectorAll(".mat-edit-item-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      const id = btn.dataset.id;
      const name = btn.dataset.name;
      const type = btn.dataset.type;
      const shopStock = btn.dataset.shop;
      const phStock = btn.dataset.ph;
      const unitCost = btn.dataset.cost;
      matOpenEditItemModal(id, name, type, shopStock, phStock, unitCost);
    });
  });
};

/* ══════════════════════════════════════════════════════════════
   LOGS
══════════════════════════════════════════════════════════════ */
const matLoadLogs = async () => {
  const params = new URLSearchParams({
    change_type: matGetVal("matLogTypeFilter"),
    location: matGetVal("matLogLocationFilter"),
    date_from: matGetVal("matLogDateFrom"),
    date_to: matGetVal("matLogDateTo"),
    page: matState.logsPage,
    per_page: 15,
  });
  const tbody = document.getElementById("materialsLogsTableBody");
  if (!tbody) return;
  tbody.innerHTML = matLoadingRow(8, "Loading logs…");
  try {
    const data = await matFetch(`${MAT_API.logs}?${params}`);
    if (!data.logs) throw new Error("Response missing 'logs' field");
    matRenderLogRows(data.logs);
    matRenderPager(
      data.pagination || {},
      "matLogsPager",
      "matLogsPageInfo",
      (p) => {
        matState.logsPage = p;
        matLoadLogs();
      },
    );
  } catch (err) {
    console.error("❌ matLoadLogs failed:", err);
    tbody.innerHTML = matErrorRow(8, `Error: ${err.message}`);
  }
};

window.matReloadLogs = (reset = false) => {
  if (reset) matState.logsPage = 1;
  matLoadLogs();
};

const matInitLogDetailsModal = () => {
  const overlay = document.getElementById("matLogDetailsModal");
  const closeBtn = document.getElementById("matLogDetailsCloseBtn");
  if (overlay) {
    overlay.addEventListener("click", (e) => {
      if (e.target === overlay) matCloseLogDetailsModal();
    });
  }
  closeBtn?.addEventListener("click", matCloseLogDetailsModal);
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") matCloseLogDetailsModal();
  });
};

window.matOpenLogDetails = async (logId) => {
  if (!logId) return;
  const overlay = document.getElementById("matLogDetailsModal");
  const body = document.getElementById("matLogDetailsBody");
  if (!overlay || !body) return;
  body.innerHTML = '<div class="loading-cell">Loading details…</div>';
  overlay.style.display = "flex";
  document.body.style.overflow = "hidden";

  try {
    const response = await matFetch(
      `${MAT_API.logs}?log_id=${encodeURIComponent(logId)}`,
    );
    const log = response?.log || response?.logs?.[0] || null;
    if (!log) {
      body.innerHTML = '<div class="no-data">No log details found.</div>';
      return;
    }

    const dateStr = new Date(log.created_at).toLocaleString();
    const note = String(
      log.note || log.remarks || log.material_remarks || "",
    ).trim();
    const noteText = note || "No remarks were provided for this change.";
    const details = [
      `<div style="display:grid; gap:10px;">`,
      `<div style="display:flex; justify-content:space-between; align-items:center; padding:12px 14px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px;">`,
      `<div><div style="font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#64748b;">Material</div><div style="font-size:1rem; font-weight:700; color:#111;">${matEsc(log.material_name || "—")}</div></div>`,
      `<span style="padding:6px 10px; border-radius:999px; background:#e0f2fe; color:#075985; font-size:0.78rem; font-weight:700;">${matEsc(log.change_type || "adjust")}</span>`,
      `</div>`,
      `<div style="display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:10px;">`,
      `<div style="padding:10px 12px; background:#fff; border:1px solid #e5e7eb; border-radius:10px;"><div style="font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#64748b;">Location</div><div style="font-weight:700; color:#111; margin-top:2px;">${matEsc(log.location || "total_stock")}</div></div>`,
      `<div style="padding:10px 12px; background:#fff; border:1px solid #e5e7eb; border-radius:10px;"><div style="font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#64748b;">Qty Δ</div><div style="font-weight:700; color:#111; margin-top:2px;">${matEsc(String(log.quantity ?? 0))}</div></div>`,
      `<div style="padding:10px 12px; background:#fff; border:1px solid #e5e7eb; border-radius:10px;"><div style="font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#64748b;">Before → After</div><div style="font-weight:700; color:#111; margin-top:2px;">${matEsc(`${log.previous_stock ?? 0} → ${log.new_stock ?? 0}`)}</div></div>`,
      `<div style="padding:10px 12px; background:#fff; border:1px solid #e5e7eb; border-radius:10px;"><div style="font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#64748b;">Admin</div><div style="font-weight:700; color:#111; margin-top:2px;">${matEsc(log.admin_name || "—")}</div></div>`,
      `</div>`,
      `<div style="padding:12px 14px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px;">`,
      `<div style="font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#64748b;">Remarks / Note</div>`,
      `<div style="margin-top:6px; white-space:pre-wrap; color:#111;">${matEsc(noteText)}</div>`,
      `<div style="margin-top:8px; font-size:0.8rem; color:#64748b;">Recorded on ${matEsc(dateStr)}</div>`,
      `</div>`,
      `</div>`,
    ];
    body.innerHTML = details.join("");
  } catch (err) {
    body.innerHTML = `<div class="no-data">Unable to load log details: ${matEsc(err.message || "Unknown error")}</div>`;
  }
};

const matCloseLogDetailsModal = () => {
  const overlay = document.getElementById("matLogDetailsModal");
  if (overlay) overlay.style.display = "none";
  document.body.style.overflow = "";
};

const matRenderLogRows = (logs = []) => {
  const tbody = document.getElementById("materialsLogsTableBody");
  if (!tbody) return;
  if (!logs.length) {
    tbody.innerHTML = matLoadingRow(8, "No log entries found.");
    return;
  }
  tbody.innerHTML = logs
    .map((l) => {
      const delta = l.new_stock - l.previous_stock;
      const deltaStr = (delta >= 0 ? "+" : "") + delta;
      const deltaCls =
        delta > 0 ? "delta-pos" : delta < 0 ? "delta-neg" : "delta-zero";
      const tb = matLogTypeBadge(l.change_type);
      const locBadge = matLocationBadge(l.location || "total_stock");
      const dateStr = new Date(l.created_at).toLocaleString();
      const noteText =
        String(l.note || l.remarks || l.material_remarks || "").trim() || "—";
      return `<tr>
                  <td><div class="mat-cell"><span>${matEsc(l.material_name)}</span></div></td>
                  <td><span class="badge ${tb.cls}">${tb.label}</span></td>
                  <td><span class="badge ${locBadge.cls}">${locBadge.label}</span></td>
                  <td class="qty-cell"><span class="${deltaCls}">${deltaStr}</span></td>
                  <td class="stock-range">${l.previous_stock} → ${l.new_stock}</td>
                  <td>${matEsc(l.admin_name || "—")}</td>
                  <td class="note-cell" title="${matEsc(noteText)}">${matEsc(noteText)}</td>
                  <td><button class="btn-action" onclick="window.matOpenLogDetails(${l.id})"><i class="fa-solid fa-eye"></i></button></td>
                  <td class="date-cell">${dateStr}</td>
              </tr>`;
    })
    .join("");
};

/* ══════════════════════════════════════════════════════════════
   STOCK UPDATE MODAL
══════════════════════════════════════════════════════════════ */
const matInitModal = () => {
  const overlay = document.getElementById("matStockModal");
  if (overlay && overlay.parentElement !== document.body)
    document.body.appendChild(overlay);
  overlay?.addEventListener("click", (e) => {
    if (e.target === overlay) matCloseModal();
  });
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") matCloseModal();
  });
  document
    .getElementById("matModalCloseBtn")
    ?.addEventListener("click", matCloseModal);
  document
    .getElementById("matCancelBtn")
    ?.addEventListener("click", matCloseModal);
  document
    .getElementById("matConfirmBtn")
    ?.addEventListener("click", matSubmitUpdate);
  document
    .getElementById("matActionSelect")
    ?.addEventListener("change", (e) => {
      matState.modal.action = e.target.value;
      matUpdatePreview();
    });
  document
    .getElementById("matLocationSelect")
    ?.addEventListener("change", (e) => {
      matState.modal.location = e.target.value;
      matUpdatePreview();
    });
  document
    .getElementById("matQtyInput")
    ?.addEventListener("input", matUpdatePreview);
};

const matOpenModal = (materialId, materialName, currentStock) => {
  const m = matState.modal;
  m.materialId = materialId;
  m.prevStock = parseInt(currentStock, 10) || 0;
  m.action = "add";
  m.location = "total_stock";
  matSetText("matModalTitle", "Update Material Stock");
  matSetText("matModalMaterialName", materialName);
  matSetText("matModalCurrentStock", currentStock);
  matSetText("matPreviewText", "");
  matSetText("matErrorText", "");
  const qtyEl = document.getElementById("matQtyInput");
  const actionEl = document.getElementById("matActionSelect");
  const locEl = document.getElementById("matLocationSelect");
  if (qtyEl) qtyEl.value = "";
  if (actionEl) actionEl.value = "add";
  if (locEl) locEl.value = "total_stock";
  const overlay = document.getElementById("matStockModal");
  if (overlay) {
    overlay.style.display = "flex";
    document.body.style.overflow = "hidden";
  }
  setTimeout(() => qtyEl?.focus(), 80);
};

const matCloseModal = () => {
  const overlay = document.getElementById("matStockModal");
  if (overlay) overlay.style.display = "none";
  document.body.style.overflow = "";
  matState.modal.materialId = null;
};

const matUpdatePreview = () => {
  const qtyEl = document.getElementById("matQtyInput");
  const previewEl = document.getElementById("matPreviewText");
  const errorEl = document.getElementById("matErrorText");
  if (!qtyEl) return;
  const raw = qtyEl.value;
  const qty = parseInt(raw, 10);
  if (errorEl) errorEl.textContent = "";
  if (previewEl) previewEl.textContent = "";
  if (raw === "" || isNaN(qty)) return;
  const { action, prevStock } = matState.modal;
  let newStock,
    errorMsg = "";
  if (action === "add") {
    if (qty <= 0) errorMsg = "Quantity must be greater than 0.";
    else newStock = prevStock + qty;
  } else if (action === "subtract") {
    if (qty <= 0) errorMsg = "Quantity must be greater than 0.";
    else if (qty > prevStock)
      errorMsg = `Cannot remove more than current stock (${prevStock}).`;
    else newStock = prevStock - qty;
  } else if (action === "adjust") {
    if (qty < 0) errorMsg = "Stock cannot be negative.";
    else newStock = qty;
  }
  if (errorMsg) {
    if (errorEl) errorEl.textContent = errorMsg;
    return;
  }
  if (previewEl) previewEl.textContent = `${prevStock} → ${newStock}`;
};

const matSubmitUpdate = async () => {
  if (matState.isUpdating) return;
  const qtyEl = document.getElementById("matQtyInput");
  const errorEl = document.getElementById("matErrorText");
  const confirmEl = document.getElementById("matConfirmBtn");
  const qtyRaw = qtyEl?.value ?? "";
  const qty = parseInt(qtyRaw, 10);
  const setErr = (msg) => {
    if (errorEl) errorEl.textContent = msg;
  };
  setErr("");
  if (qtyRaw === "" || isNaN(qty))
    return setErr("Please enter a valid quantity.");
  if (qty < 0) return setErr("Quantity cannot be negative.");
  if (matState.modal.action !== "adjust" && qty === 0)
    return setErr("Quantity must be greater than 0.");
  if (!matState.modal.materialId)
    return setErr("No material selected. Close and try again.");
  matState.isUpdating = true;
  if (confirmEl) {
    confirmEl.disabled = true;
    confirmEl.innerHTML =
      '<i class="fa-solid fa-spinner fa-spin"></i> Updating…';
  }
  try {
    const data = await matFetch(MAT_API.update, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        material_id: matState.modal.materialId,
        location: matState.modal.location,
        action: matState.modal.action,
        quantity: qty,
        note: "",
      }),
    });
    matCloseModal();
    matShowToast(
      `Stock updated: ${data.material_name} is now ${data.new_stock} units.`,
      "success",
    );
    window.matReloadMaterials(true);
    window.matReloadLogs(true);
  } catch (err) {
    setErr(err.message || "Network error. Please try again.");
  } finally {
    matState.isUpdating = false;
    if (confirmEl) {
      confirmEl.disabled = false;
      confirmEl.innerHTML = '<i class="fa-solid fa-check"></i> Update';
    }
  }
};

/* ══════════════════════════════════════════════════════════════
   PAGINATION
══════════════════════════════════════════════════════════════ */
const matRenderPager = (pagination = {}, pagerId, infoId, onPageChange) => {
  const total = pagination.total || 0;
  const perPage = pagination.per_page || 10;
  const currentPage = pagination.current_page || 1;
  const lastPage = pagination.last_page || 1;
  const infoEl = document.getElementById(infoId);
  const pagerEl = document.getElementById(pagerId);
  if (!infoEl || !pagerEl) return;
  const from = total === 0 ? 0 : (currentPage - 1) * perPage + 1;
  const to = Math.min(currentPage * perPage, total);
  infoEl.textContent = total === 0 ? "No results" : `${from}–${to} of ${total}`;
  pagerEl.innerHTML = "";
  if (lastPage <= 1) return;
  const mkBtn = (label, disabled, onClick) => {
    const btn = document.createElement("button");
    btn.className = "page-btn";
    btn.textContent = label;
    btn.disabled = disabled;
    if (!disabled) btn.addEventListener("click", onClick);
    return btn;
  };
  pagerEl.appendChild(
    mkBtn("‹", currentPage <= 1, () => onPageChange(currentPage - 1)),
  );
  matBuildPageRange(currentPage, lastPage).forEach((p) => {
    if (p === "…") {
      const dots = document.createElement("span");
      dots.className = "page-dots";
      dots.textContent = "…";
      pagerEl.appendChild(dots);
    } else {
      const b = mkBtn(p, false, () => onPageChange(p));
      if (p === currentPage) b.classList.add("active");
      pagerEl.appendChild(b);
    }
  });
  pagerEl.appendChild(
    mkBtn("›", currentPage >= lastPage, () => onPageChange(currentPage + 1)),
  );
};

const matBuildPageRange = (current, last) => {
  const range = [];
  const left = Math.max(1, current - 2);
  const right = Math.min(last, left + 4);
  if (left > 1) {
    range.push(1);
    if (left > 2) range.push("…");
  }
  for (let i = left; i <= right; i++) range.push(i);
  if (right < last) {
    if (right < last - 1) range.push("…");
    range.push(last);
  }
  return range;
};

/* ══════════════════════════════════════════════════════════════
   TOAST
══════════════════════════════════════════════════════════════ */
const matInitToast = () => {
  if (document.getElementById("matToastContainer")) return;
  const el = document.createElement("div");
  el.id = "matToastContainer";
  el.className = "toast-container";
  document.body.appendChild(el);
};
const matShowToast = (message, type = "success") => {
  const container = document.getElementById("matToastContainer");
  if (!container) return;
  const icon = type === "success" ? "fa-circle-check" : "fa-circle-exclamation";
  const toast = document.createElement("div");
  toast.className = `toast toast-${type}`;
  toast.innerHTML = `<i class="fa-solid ${icon}"></i><span>${matEsc(message)}</span>`;
  container.appendChild(toast);
  requestAnimationFrame(() => toast.classList.add("show"));
  setTimeout(() => {
    toast.classList.remove("show");
    toast.addEventListener("transitionend", () => toast.remove(), {
      once: true,
    });
  }, 3500);
};

/* ══════════════════════════════════════════════════════════════
   HELPERS
══════════════════════════════════════════════════════════════ */
const matFetch = async (url, options = {}) => {
  console.log("🔍 Fetching:", url);
  try {
    const res = await fetch(url, options);
    if (!res.ok) throw new Error(`HTTP ${res.status}: ${res.statusText}`);
    const contentType = res.headers.get("content-type");
    if (!contentType || !contentType.includes("application/json")) {
      const text = await res.text();
      throw new Error(`Expected JSON, got ${contentType || "unknown"}`);
    }
    let data;
    try {
      data = await res.json();
    } catch (parseErr) {
      throw new Error(`Failed to parse JSON response: ${parseErr.message}`);
    }
    if (!data.success) throw new Error(data.message || "Unknown server error");
    return data;
  } catch (err) {
    console.error("❌ matFetch Error:", err.message);
    throw err;
  }
};
const matStockBadge = (status, stock) => {
  if (status === "out_of_stock")
    return { cls: "badge-danger", label: "Out of Stock" };
  if (status === "low_stock")
    return { cls: "badge-warning", label: `Low (${stock})` };
  return { cls: "badge-success", label: "In Stock" };
};
const matLocationBadge = (location) => {
  const map = {
    shop_stock: { cls: "badge-info", label: "Shop" },
    ph_stock: { cls: "badge-primary", label: "PH" },
    total_stock: { cls: "badge-muted", label: "Total" },
  };
  return map[location] || { cls: "badge-muted", label: location };
};
const matLogTypeBadge = (type) => {
  const map = {
    add: { cls: "badge-success", label: "Add" },
    subtract: { cls: "badge-danger", label: "Remove" },
    order: { cls: "badge-info", label: "Order" },
    return: { cls: "badge-warning", label: "Return" },
    adjust: { cls: "badge-muted", label: "Adjust" },
  };
  return map[type] || { cls: "badge-muted", label: type };
};
const matSetText = (id, value) => {
  const el = document.getElementById(id);
  if (el) el.textContent = value;
};
const matGetVal = (id) => document.getElementById(id)?.value ?? "";
const matLoadingRow = (cols, msg) =>
  `<tr><td colspan="${cols}" class="loading-cell">${msg}</td></tr>`;
const matErrorRow = (cols, msg) =>
  `<tr><td colspan="${cols}" class="loading-cell error-cell">Error: ${matEsc(msg)}</td></tr>`;
const matEsc = (str) =>
  String(str ?? "")
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#39;");

/* ══════════════════════════════════════════════════════════════
   ADD NEW ITEM FUNCTIONALITY
══════════════════════════════════════════════════════════════ */

// Initialize Add Item Modal
const matInitAddItemModal = () => {
  // Close button
  const closeBtn = document.getElementById("matAddItemCloseBtn");
  if (closeBtn) {
    closeBtn.addEventListener("click", matCloseAddItemModal);
  }

  // Cancel button
  const cancelBtn = document.getElementById("matAddItemCancelBtn");
  if (cancelBtn) {
    cancelBtn.addEventListener("click", matCloseAddItemModal);
  }

  // Confirm button
  const confirmBtn = document.getElementById("matAddItemConfirmBtn");
  if (confirmBtn) {
    confirmBtn.addEventListener("click", matAddNewItem);
  }

  // Stock input listeners for total preview
  const shopStockInput = document.getElementById("matNewShopStock");
  const phStockInput = document.getElementById("matNewPhStock");

  if (shopStockInput) {
    shopStockInput.addEventListener("input", matUpdateTotalPreview);
  }
  if (phStockInput) {
    phStockInput.addEventListener("input", matUpdateTotalPreview);
  }

  // Close modal when clicking outside
  const addItemModal = document.getElementById("matAddItemModal");
  if (addItemModal) {
    addItemModal.addEventListener("click", function (e) {
      if (e.target === addItemModal) {
        matCloseAddItemModal();
      }
    });
  }
};

// Open Add Item Modal
window.matOpenAddItemModal = () => {
  // Clear form fields
  const nameInput = document.getElementById("matNewMaterialName");
  const typeInput = document.getElementById("matNewType");
  const shopStockInput = document.getElementById("matNewShopStock");
  const phStockInput = document.getElementById("matNewPhStock");
  const unitCostInput = document.getElementById("matNewUnitCost");
  const errorDiv = document.getElementById("matAddItemError");

  if (nameInput) nameInput.value = "";
  if (typeInput) {
    typeInput.value = "";
    // Populate datalist with latest types
    populateTypeDatalist(matTypeOptions);
  }
  if (shopStockInput) shopStockInput.value = "0";
  if (phStockInput) phStockInput.value = "0";
  if (unitCostInput) unitCostInput.value = "0";
  if (errorDiv) errorDiv.innerHTML = "";

  // Update total preview
  matUpdateTotalPreview();

  // Show modal
  const modal = document.getElementById("matAddItemModal");
  if (modal) {
    modal.style.display = "flex";
    document.body.style.overflow = "hidden";
  }
};

// Close Add Item Modal
const matCloseAddItemModal = () => {
  const modal = document.getElementById("matAddItemModal");
  if (modal) {
    modal.style.display = "none";
    document.body.style.overflow = "";
  }
};

// Update total stock preview
const matUpdateTotalPreview = () => {
  const shopStock =
    parseInt(document.getElementById("matNewShopStock")?.value) || 0;
  const phStock =
    parseInt(document.getElementById("matNewPhStock")?.value) || 0;
  const total = shopStock + phStock;
  const totalSpan = document.getElementById("matNewTotalValue");
  if (totalSpan) totalSpan.innerText = total;
};

// Add new item via API
const matAddNewItem = async () => {
  const materialName =
    document.getElementById("matNewMaterialName")?.value.trim() || "";
  const type = document.getElementById("matNewType")?.value || "";
  const shopStock =
    parseInt(document.getElementById("matNewShopStock")?.value) || 0;
  const phStock =
    parseInt(document.getElementById("matNewPhStock")?.value) || 0;
  const unitCost =
    parseFloat(document.getElementById("matNewUnitCost")?.value) || 0;

  const errorDiv = document.getElementById("matAddItemError");
  const confirmBtn = document.getElementById("matAddItemConfirmBtn");

  // Validation
  if (!materialName) {
    if (errorDiv) errorDiv.innerHTML = "Please enter material name.";
    return;
  }

  if (!type) {
    if (errorDiv) errorDiv.innerHTML = "Please enter a type.";
    return;
  }

  if (shopStock < 0 || phStock < 0) {
    if (errorDiv) errorDiv.innerHTML = "Stock values cannot be negative.";
    return;
  }

  if (unitCost < 0) {
    if (errorDiv) errorDiv.innerHTML = "Unit cost cannot be negative.";
    return;
  }

  if (errorDiv)
    errorDiv.innerHTML =
      '<i class="fa-solid fa-spinner fa-spin"></i> Adding item...';

  if (confirmBtn) {
    confirmBtn.disabled = true;
    confirmBtn.innerHTML =
      '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
  }

  try {
    const response = await fetch(MAT_API.addItem, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        material_name: materialName,
        type: type,
        shop_stock: shopStock,
        ph_stock: phStock,
        unit_cost: unitCost,
      }),
    });

    const result = await response.json();

    if (result.success) {
      if (errorDiv) errorDiv.innerHTML = "";
      matCloseAddItemModal();
      matShowToast("Item added successfully!", "success");
      matReloadMaterials(true);
      matReloadLogs(true);
      // Reload types after adding new item
      matLoadTypes();
    } else {
      if (errorDiv) errorDiv.innerHTML = result.message;
    }
  } catch (error) {
    console.error("Error adding item:", error);
    if (errorDiv) errorDiv.innerHTML = "Network error. Please try again.";
  } finally {
    if (confirmBtn) {
      confirmBtn.disabled = false;
      confirmBtn.innerHTML = '<i class="fa-solid fa-save"></i> Save Item';
    }
  }
};

/* ══════════════════════════════════════════════════════════════
   LOAD TYPES FOR DATALIST
══════════════════════════════════════════════════════════════ */

const matLoadTypes = async () => {
  try {
    const response = await fetch(MAT_API.getTypes);
    const data = await response.json();

    if (data.success && data.types) {
      matTypeOptions = data.types;
      populateTypeDatalist(matTypeOptions);
      populateEditTypeDatalist(matTypeOptions);
    }
  } catch (err) {
    console.error("Failed to load types:", err);
  }
};

const populateTypeDatalist = (types) => {
  const datalist = document.getElementById("matTypeOptions");
  if (!datalist) return;

  datalist.innerHTML = types
    .map((type) => `<option value="${matEsc(type)}">`)
    .join("");
};

const populateEditTypeDatalist = (types) => {
  const datalist = document.getElementById("matEditTypeOptions");
  if (datalist) {
    datalist.innerHTML = types
      .map((type) => `<option value="${matEsc(type)}">`)
      .join("");
  }
};

/* ══════════════════════════════════════════════════════════════
   AUDIT / BOM MODAL
══════════════════════════════════════════════════════════════ */
const matInitAuditModal = () => {
  if (!document.getElementById("auditModal")) {
    const modalHtml = `<div id="auditModal"><div class="audit-modal-container"><div class="audit-modal-header"><h2><i class="fa-solid fa-clipboard-list"></i> Create Audit</h2><button class="audit-modal-close" onclick="matCloseAuditModal()">&times;</button></div><div class="audit-modal-body"><div class="audit-section"><h3 class="audit-section-title">Item Information</h3><input type="text" id="auditItemName" placeholder="Enter item/product name..."></div><div class="audit-section"><h3 class="audit-section-title">Material Costs</h3><div id="materialCostsContainer"></div><button type="button" class="add-row-btn" onclick="matAddMaterialRow()"><i class="fa-solid fa-plus"></i> Add Material</button></div><div class="audit-section"><h3 class="audit-section-title">Reject Costs</h3><div class="reject-note"><i class="fa-solid fa-info-circle"></i> Remove materials from the list if there are no reject materials</div><div id="rejectCostsContainer"></div><button type="button" class="add-row-btn" onclick="matAddRejectRow()"><i class="fa-solid fa-plus"></i> Add Reject Material</button></div>

<!-- ========= OVERHEAD SECTION ========= -->
<div class="audit-overhead-section">
  <div class="audit-section-title"><i class="fa-solid fa-bolt"></i> Electricity & Overhead Costs</div>
  <div class="audit-section-body">
    <div class="overhead-grid">
      <div class="overhead-field"><label><i class="fa-solid fa-building"></i> Shop Rent</label><input type="number" id="overhead_shop_rent" class="overhead-input" step="0.01" value="0" placeholder="₱0.00"></div>
      <div class="overhead-field"><label><i class="fa-solid fa-users"></i> Fixed Salaries (Admin/Finance)</label><input type="number" id="overhead_fixed_salaries" class="overhead-input" step="0.01" value="0" placeholder="₱0.00"></div>
      <div class="overhead-field"><label><i class="fa-solid fa-wifi"></i> Shop Utilities (Internet + Base Power)</label><input type="number" id="overhead_shop_utilities" class="overhead-input" step="0.01" value="0" placeholder="₱0.00"></div>
      <div class="overhead-field"><label><i class="fa-solid fa-newspaper"></i> Subscriptions</label><input type="number" id="overhead_subscriptions" class="overhead-input" step="0.01" value="0" placeholder="₱0.00"></div>
      <div class="overhead-field"><label><i class="fa-solid fa-microchip"></i> Machine Depreciation (Fixed)</label><input type="number" id="overhead_machine_depreciation" class="overhead-input" step="0.01" value="0" placeholder="₱0.00"></div>
      <div class="overhead-field"><label><i class="fa-solid fa-wrench"></i> Maintenance & Repair Fund Allocation</label><input type="number" id="overhead_maintenance_repair" class="overhead-input" step="0.01" value="0" placeholder="₱0.00"></div>
      <div class="overhead-field"><label><i class="fa-solid fa-chart-line"></i> Marketing & Promo Materials</label><input type="number" id="overhead_marketing" class="overhead-input" step="0.01" value="0" placeholder="₱0.00"></div>
      <div class="overhead-field"><label><i class="fa-solid fa-bolt"></i> Electricity</label><input type="number" id="overhead_electricity" class="overhead-input" step="0.01" value="0" placeholder="₱0.00"></div>
    </div>
    <div class="overhead-total-row"><div class="overhead-total-item"><span>TOTAL MONTHLY OVERHEAD</span><span id="total_overhead_display">₱0.00</span></div></div>
    <div class="production-hours-row"><div class="production-hours-field"><label><i class="fa-regular fa-clock"></i> Production Hours (for this project)</label><input type="number" id="production_hours" step="0.5" value="0" placeholder="Hours"></div><div class="overhead-per-hour-display" id="overhead_per_hour_display"><strong>₱0.00</strong> per hour</div></div>
  </div>
</div>
<!-- ========= END OVERHEAD SECTION ========= -->

<div class="audit-section"><h3 class="audit-section-title">Items</h3><div id="itemsContainer"></div><button type="button" class="add-row-btn" onclick="matAddItemRow()"><i class="fa-solid fa-plus"></i> Add Item</button></div><div class="totals-grid"><div class="total-card"><label>Total Material Cost</label><div class="total-value" id="totalMaterialCost">₱0.00</div></div><div class="total-card"><label>Total Reject Cost</label><div class="total-value" id="totalRejectCost">₱0.00</div></div><div class="total-card"><label>Total Overhead</label><div class="total-value" id="totalOverheadCost">₱0.00</div></div><div class="total-card"><label>Total Amount Due</label><div class="total-value" id="totalAmountDue">₱0.00</div></div><div class="total-card"><label>Profit <input type="checkbox" id="manualProfitCheck" title="Enable manual profit entry" style="margin-left:8px;"></label><div class="total-value profit-value" id="profitDisplay" style="cursor:pointer;">₱0.00</div><input type="number" id="manualProfitInput" placeholder="Enter profit amount" step="0.01" style="display:none;width:100%;margin-top:5px;padding:8px;border:1px solid #ddd;border-radius:4px;"></div></div><div class="auto-compute-row"><label for="autoComputeCheck">Auto-compute totals</label><input type="checkbox" id="autoComputeCheck" checked></div><div class="signatures-grid"><div class="signature-field"><label>Created By</label><input type="text" id="createdBy" placeholder="Enter name..."></div><div class="signature-field"><label>Audited By</label><input type="text" id="auditedBy" placeholder="Enter name..."></div><div class="signature-field"><label>Acknowledged By</label><input type="text" id="acknowledgedBy" placeholder="Enter name..."></div></div><div class="modal-actions"><button type="button" class="btn-cancel" onclick="matCloseAuditModal()">Cancel</button><button type="button" class="btn-submit" onclick="matSubmitAudit()"><i class="fa-solid fa-save"></i> Create Audit</button></div></div></div></div>`;
    document.body.insertAdjacentHTML("beforeend", modalHtml);
  }

  // Existing listeners
  document
    .getElementById("autoComputeCheck")
    ?.addEventListener("change", (e) => {
      auditAutoCompute = e.target.checked;
      if (auditAutoCompute) matComputeTotals();
    });
  document
    .getElementById("manualProfitCheck")
    ?.addEventListener("change", (e) => {
      const manualInput = document.getElementById("manualProfitInput");
      const profitDisplay = document.getElementById("profitDisplay");
      if (e.target.checked) {
        manualInput.style.display = "block";
        profitDisplay.style.display = "none";
        manualInput.focus();
      } else {
        manualInput.style.display = "none";
        profitDisplay.style.display = "block";
        matComputeTotals();
      }
    });
  document
    .getElementById("manualProfitInput")
    ?.addEventListener("input", () => {
      const manualInput = document.getElementById("manualProfitInput");
      const profitDisplay = document.getElementById("profitDisplay");
      const value = parseFloat(manualInput.value) || 0;
      profitDisplay.textContent = `₱${value.toFixed(2)}`;
    });
  document
    .getElementById("auditItemName")
    ?.addEventListener("input", () => matComputeTotals());

  // Initialize OverheadHelper (fields start at 0)
  const modalContainer = document.getElementById("auditModal");
  OverheadHelper.attachListeners(modalContainer, () => matComputeTotals());
};

const matLoadMaterialsForAudit = async () => {
  try {
    const data = await matFetch(MAT_API.materialsForAudit);
    auditMaterialsList = data.materials || [];
  } catch (err) {
    console.error("Failed to load materials:", err);
  }
};

// ============================================================
// FIXED: matAddMaterialRow - Properly handles undefined values
// ============================================================
const matAddMaterialRow = (materialData = null) => {
  const container = document.getElementById("materialCostsContainer");
  if (!container) {
    console.warn("materialCostsContainer not found");
    return;
  }

  const rowId =
    "material_row_" +
    Date.now() +
    "_" +
    Math.random().toString(36).substr(2, 6);
  const row = document.createElement("div");
  row.className = "dynamic-row";
  row.id = rowId;

  // ===== CRITICAL FIX: Ensure all values are properly defined =====
  // Use safe defaults for all values to prevent "undefined" errors
  const materialId = materialData?.id || null;

  // Get quantity - handle both 'quantity' and 'suggested_quantity'
  const quantity =
    materialData?.quantity || materialData?.suggested_quantity || 1;

  // Get unit cost - ensure it's a number
  const unitCost =
    typeof materialData?.unit_cost === "number"
      ? materialData.unit_cost
      : typeof materialData?.cost === "number"
        ? materialData.cost
        : 0;

  // Get total cost - calculate if not provided
  const totalCost =
    typeof materialData?.total_cost === "number"
      ? materialData.total_cost
      : quantity * unitCost;

  // Get material name
  const materialName = materialData?.name || materialData?.material_name || "";

  console.log(
    `Adding material row: id=${materialId}, name=${materialName}, qty=${quantity}, cost=${unitCost}, total=${totalCost}`,
  );

  row.innerHTML = `
    <div class="material-selector-wrapper" style="flex:2;"></div>
    <input type="number" class="material-qty" placeholder="QTY" step="1" min="0" 
           value="${quantity}" style="flex:1;" 
           oninput="matUpdateMaterialCostFromRow('${rowId}')">
    <input type="number" class="material-cost-per-unit" placeholder="Cost/Unit" step="0.0001" 
           value="${parseFloat(unitCost).toFixed(4)}" style="flex:1;" readonly>
    <input type="number" class="material-total-cost" placeholder="Total Cost" step="0.01" 
           value="${parseFloat(totalCost).toFixed(2)}" style="flex:1;" readonly>
    <button class="remove-row" onclick="matRemoveDynamicRow(this)"><i class="fa-solid fa-trash"></i></button>
    ${materialName ? `<span class="prefill-badge" style="background:#dbeafe; color:#1e40af; font-size:0.7rem; padding:2px 6px; border-radius:4px;">from quotation</span>` : ""}
  `;

  container.appendChild(row);

  // Initialize the material selector
  const selectorWrapper = row.querySelector(".material-selector-wrapper");
  const selector = new MaterialSelector(
    selectorWrapper,
    (material) => {
      const costInput = row.querySelector(".material-cost-per-unit");
      if (costInput && material) {
        const cost = parseFloat(material.unit_cost || 0);
        costInput.value = cost.toFixed(4);
      }
      matUpdateMaterialCostFromRow(rowId);
      row.dataset.materialId = material.id;
    },
    materialId || null,
  );

  materialSelectors.push({ rowId, selector });

  // If we have a material name but no ID, try to find the material by name
  if (materialName && !materialId) {
    // Try to find a matching material in the selector's list
    setTimeout(() => {
      const matchingMaterial = selector.materials.find(
        (m) => m.material_name.toLowerCase() === materialName.toLowerCase(),
      );
      if (matchingMaterial) {
        // Select the matching material
        selector.selectedMaterialId = matchingMaterial.id;
        const selectedText = selector.container.querySelector(".selected-text");
        if (selectedText)
          selectedText.textContent = matchingMaterial.material_name;
        row.dataset.materialId = matchingMaterial.id;

        // Update cost
        const costInput = row.querySelector(".material-cost-per-unit");
        if (costInput) {
          costInput.value = parseFloat(matchingMaterial.unit_cost || 0).toFixed(
            4,
          );
        }
        matUpdateMaterialCostFromRow(rowId);
      }
    }, 200);
  }

  // Ensure totals are calculated
  setTimeout(() => matUpdateMaterialCostFromRow(rowId), 100);
};

const matUpdateMaterialCostFromRow = (rowId) => {
  const row = document.getElementById(rowId);
  if (!row) return;

  const qtyInput = row.querySelector(".material-qty");
  const costInput = row.querySelector(".material-cost-per-unit");
  const totalInput = row.querySelector(".material-total-cost");

  if (!qtyInput || !costInput || !totalInput) return;

  const qty = parseFloat(qtyInput.value) || 0;
  const unitCost = parseFloat(costInput.value) || 0;
  const total = qty * unitCost;

  totalInput.value = total.toFixed(2);
  matComputeTotals();
};

// ============================================================
// FIXED: matAddRejectRow - Properly handles undefined values
// ============================================================
const matAddRejectRow = (rejectData = null) => {
  const container = document.getElementById("rejectCostsContainer");
  if (!container) {
    console.warn("rejectCostsContainer not found");
    return;
  }

  const rowId =
    "reject_row_" + Date.now() + "_" + Math.random().toString(36).substr(2, 6);
  const row = document.createElement("div");
  row.className = "dynamic-row";
  row.id = rowId;

  // ===== CRITICAL FIX: Ensure all values are properly defined =====
  const materialId = rejectData?.id || null;
  const quantity = rejectData?.quantity || 1;
  const unitCost =
    typeof rejectData?.unit_cost === "number"
      ? rejectData.unit_cost
      : typeof rejectData?.cost === "number"
        ? rejectData.cost
        : 0;
  const totalCost =
    typeof rejectData?.total_cost === "number"
      ? rejectData.total_cost
      : quantity * unitCost;
  const materialName = rejectData?.name || rejectData?.material_name || "";

  row.innerHTML = `
    <div class="material-selector-wrapper" style="flex:2;"></div>
    <input type="number" class="reject-qty" placeholder="QTY" step="1" min="0" 
           value="${quantity}" style="flex:1;" 
           oninput="matUpdateRejectCostFromRow('${rowId}')">
    <input type="number" class="reject-cost-per-unit" placeholder="Cost/Unit" step="0.0001" 
           value="${parseFloat(unitCost).toFixed(4)}" style="flex:1;" readonly>
    <input type="number" class="reject-total-cost" placeholder="Total Cost" step="0.01" 
           value="${parseFloat(totalCost).toFixed(2)}" style="flex:1;" readonly>
    <button class="remove-row" onclick="matRemoveDynamicRow(this)"><i class="fa-solid fa-trash"></i></button>
    ${materialName ? `<span class="prefill-badge" style="background:#dbeafe; color:#1e40af; font-size:0.7rem; padding:2px 6px; border-radius:4px;">from quotation</span>` : ""}
  `;

  container.appendChild(row);

  const selectorWrapper = row.querySelector(".material-selector-wrapper");
  const selector = new MaterialSelector(
    selectorWrapper,
    (material) => {
      const costInput = row.querySelector(".reject-cost-per-unit");
      if (costInput && material) {
        const cost = parseFloat(material.unit_cost || 0);
        costInput.value = cost.toFixed(4);
      }
      matUpdateRejectCostFromRow(rowId);
      row.dataset.materialId = material.id;
    },
    materialId || null,
  );

  rejectSelectors.push({ rowId, selector });

  // If we have a material name but no ID, try to find the material by name
  if (materialName && !materialId) {
    setTimeout(() => {
      const matchingMaterial = selector.materials.find(
        (m) => m.material_name.toLowerCase() === materialName.toLowerCase(),
      );
      if (matchingMaterial) {
        selector.selectedMaterialId = matchingMaterial.id;
        const selectedText = selector.container.querySelector(".selected-text");
        if (selectedText)
          selectedText.textContent = matchingMaterial.material_name;
        row.dataset.materialId = matchingMaterial.id;

        const costInput = row.querySelector(".reject-cost-per-unit");
        if (costInput) {
          costInput.value = parseFloat(matchingMaterial.unit_cost || 0).toFixed(
            4,
          );
        }
        matUpdateRejectCostFromRow(rowId);
      }
    }, 200);
  }

  setTimeout(() => matUpdateRejectCostFromRow(rowId), 100);
};

const matUpdateRejectCostFromRow = (rowId) => {
  const row = document.getElementById(rowId);
  if (!row) return;

  const qtyInput = row.querySelector(".reject-qty");
  const costInput = row.querySelector(".reject-cost-per-unit");
  const totalInput = row.querySelector(".reject-total-cost");

  if (!qtyInput || !costInput || !totalInput) return;

  const qty = parseFloat(qtyInput.value) || 0;
  const unitCost = parseFloat(costInput.value) || 0;
  const total = qty * unitCost;

  totalInput.value = total.toFixed(2);
  matComputeTotals();
};

const matAddItemRow = (itemData = null) => {
  const container = document.getElementById("itemsContainer");
  if (!container) return;

  const rowId = Date.now() + Math.random();
  const row = document.createElement("div");
  row.className = "dynamic-row";
  row.dataset.id = rowId;

  // Clean the description (strip HTML)
  let cleanName = "";
  if (itemData?.name) {
    const tempDiv = document.createElement("div");
    tempDiv.innerHTML = itemData.name;
    cleanName = tempDiv.textContent || tempDiv.innerText || itemData.name;
  }

  row.innerHTML = `
    <input type="text" class="item-name" placeholder="Item name" 
           value="${matEsc(cleanName)}" style="flex:2;" 
           oninput="matUpdateItemTotal(this)">
    <input type="number" class="item-qty" placeholder="QTY" 
           value="${itemData?.quantity || 1}" style="flex:1;" 
           oninput="matUpdateItemTotal(this)">
    <input type="number" class="item-unit-price" placeholder="Unit Price" step="0.01" 
           value="${itemData?.unit_price || 0}" style="flex:1;" 
           oninput="matUpdateItemTotal(this)">
    <input type="number" class="item-total-amount" placeholder="Total Amount" step="0.01" 
           value="${itemData?.total_amount || (itemData?.quantity || 1) * (itemData?.unit_price || 0)}" style="flex:1;" readonly>
    <button class="remove-row" onclick="matRemoveDynamicRow(this)"><i class="fa-solid fa-trash"></i></button>
    ${itemData ? '<span class="prefill-badge" style="background:#dbeafe; color:#1e40af; font-size:0.7rem; padding:2px 6px; border-radius:4px;">from quotation</span>' : ""}
  `;

  container.appendChild(row);
  matComputeTotals();
};

const matUpdateItemTotal = (element) => {
  const row = element.closest(".dynamic-row");
  if (!row) return;

  const qtyInput = row.querySelector(".item-qty");
  const priceInput = row.querySelector(".item-unit-price");
  const totalInput = row.querySelector(".item-total-amount");

  if (!qtyInput || !priceInput || !totalInput) return;

  const qty = parseFloat(qtyInput.value) || 0;
  const price = parseFloat(priceInput.value) || 0;
  const total = qty * price;

  totalInput.value = total.toFixed(2);
  matComputeTotals();
};

const matRemoveDynamicRow = (button) => {
  const row = button.closest(".dynamic-row");
  if (!row) return;

  const rowId = row.id;
  materialSelectors = materialSelectors.filter((s) => s.rowId !== rowId);
  rejectSelectors = rejectSelectors.filter((s) => s.rowId !== rowId);
  row.remove();
  matComputeTotals();
};

const matComputeTotals = () => {
  if (!auditAutoCompute) return;
  let materialTotal = 0,
    rejectTotal = 0,
    amountTotal = 0;

  document
    .querySelectorAll("#materialCostsContainer .material-total-cost")
    .forEach((input) => {
      materialTotal += parseFloat(input.value) || 0;
    });
  document
    .querySelectorAll("#rejectCostsContainer .reject-total-cost")
    .forEach((input) => {
      rejectTotal += parseFloat(input.value) || 0;
    });
  document
    .querySelectorAll("#itemsContainer .item-total-amount")
    .forEach((input) => {
      amountTotal += parseFloat(input.value) || 0;
    });

  const modalContainer = document.getElementById("auditModal");
  const overheadTotal = OverheadHelper.getTotalOverhead(modalContainer);

  // Update total overhead display
  const totalOverheadEl = document.getElementById("totalOverheadCost");
  if (totalOverheadEl)
    totalOverheadEl.textContent = `₱${overheadTotal.toFixed(2)}`;

  const manualProfitCheck = document.getElementById("manualProfitCheck");
  if (!manualProfitCheck?.checked) {
    const profit = amountTotal - (materialTotal + rejectTotal + overheadTotal);
    const profitDisplay = document.getElementById("profitDisplay");
    if (profitDisplay) profitDisplay.textContent = `₱${profit.toFixed(2)}`;
    const manualInput = document.getElementById("manualProfitInput");
    if (manualInput) manualInput.value = profit.toFixed(2);
  }

  const materialCostEl = document.getElementById("totalMaterialCost");
  const rejectCostEl = document.getElementById("totalRejectCost");
  const amountDueEl = document.getElementById("totalAmountDue");

  if (materialCostEl)
    materialCostEl.textContent = `₱${materialTotal.toFixed(2)}`;
  if (rejectCostEl) rejectCostEl.textContent = `₱${rejectTotal.toFixed(2)}`;
  if (amountDueEl) amountDueEl.textContent = `₱${amountTotal.toFixed(2)}`;
};

const matPrefillAuditFormFromQuotation = async (quotationId) => {
  if (!quotationId) return;

  try {
    const response = await fetch(
      `../../api/admin_site/inventory/get_quotation_materials_for_audit.php?id=${quotationId}`,
    );
    const result = await response.json();

    if (!result.success) {
      matShowToast(
        result.message || "Failed to load quotation details",
        "error",
      );
      return;
    }

    const quotation = result.quotation || {};
    const items = result.items || [];
    const suggestedMaterials = result.suggested_materials || [];

    // Clear existing rows
    document.getElementById("materialCostsContainer").innerHTML = "";
    document.getElementById("rejectCostsContainer").innerHTML = "";
    document.getElementById("itemsContainer").innerHTML = "";

    materialSelectors = [];
    rejectSelectors = [];

    // ===== FIXED: Add suggested materials with proper error handling =====
    if (suggestedMaterials && suggestedMaterials.length > 0) {
      suggestedMaterials.forEach((material) => {
        // Ensure material has the expected properties
        const safeMaterial = {
          id: material.id || null,
          name: material.name || material.material_name || "",
          quantity: material.suggested_quantity || material.quantity || 1,
          unit_cost:
            typeof material.unit_cost === "number" ? material.unit_cost : 0,
          total_cost:
            typeof material.total_cost === "number" ? material.total_cost : 0,
        };
        matAddMaterialRow(safeMaterial);
      });
    } else {
      matAddMaterialRow();
    }

    matAddRejectRow();

    if (items && items.length > 0) {
      items.forEach((item) =>
        matAddItemRow({
          name: item.description || "",
          quantity: item.quantity || 1,
          unit_price: item.unit_price || 0,
          total_amount: item.total || 0,
        }),
      );
    } else {
      matAddItemRow();
    }

    const itemNameInput = document.getElementById("auditItemName");
    if (itemNameInput) {
      const itemName = items?.[0]?.description || quotation.quote_number || "";
      itemNameInput.value = itemName ? itemName.replace(/<[^>]+>/g, "") : "";
    }

    const createdByInput = document.getElementById("createdBy");
    if (createdByInput) {
      createdByInput.value = quotation.client_name || "";
    }

    const auditedByInput = document.getElementById("auditedBy");
    if (auditedByInput) {
      auditedByInput.value = "";
    }

    const acknowledgedByInput = document.getElementById("acknowledgedBy");
    if (acknowledgedByInput) {
      acknowledgedByInput.value = "";
    }

    // Recalculate totals after all rows are added
    setTimeout(() => matComputeTotals(), 200);
  } catch (error) {
    console.error("Failed to load quotation for audit:", error);
    matShowToast("Unable to load quotation details for audit.", "error");
  }
};

window.matOpenAuditModalForQuotation = async (
  quotationId,
  quotationNumber = null,
) => {
  if (quotationId) {
    window.currentAuditQuotationId = quotationId;
  }
  if (quotationNumber) {
    window.currentAuditQuotationNumber = quotationNumber;
  }

  await window.matOpenAuditModal();
};

window.matOpenAuditModal = async () => {
  matInitAuditModal();
  await matLoadMaterialsForAudit();

  // Clear and reset form
  document.getElementById("materialCostsContainer").innerHTML = "";
  document.getElementById("rejectCostsContainer").innerHTML = "";
  document.getElementById("itemsContainer").innerHTML = "";
  document.getElementById("auditItemName").value = "";
  document.getElementById("createdBy").value = "";
  document.getElementById("auditedBy").value = "";
  document.getElementById("acknowledgedBy").value = "";
  document.getElementById("autoComputeCheck").checked = true;
  document.getElementById("manualProfitCheck").checked = false;
  document.getElementById("manualProfitInput").style.display = "none";
  document.getElementById("profitDisplay").style.display = "block";
  document.getElementById("manualProfitInput").value = "";
  auditAutoCompute = true;
  materialSelectors = [];
  rejectSelectors = [];

  if (window.currentAuditQuotationId) {
    await matPrefillAuditFormFromQuotation(window.currentAuditQuotationId);
  } else {
    matAddMaterialRow();
    matAddRejectRow();
    matAddItemRow();
  }

  // Show the modal
  const auditModal = document.getElementById("auditModal");
  if (auditModal) {
    auditModal.style.display = "flex";
    document.body.style.overflow = "hidden";
  } else {
    console.error("Audit modal not found!");
    matShowToast("Error: Audit modal not found", "error");
  }
};

const matCloseAuditModal = () => {
  const auditModal = document.getElementById("auditModal");
  if (auditModal) {
    auditModal.style.display = "none";
    document.body.style.overflow = "";
  }
};

const matSubmitAudit = async () => {
  const materials = [];
  document
    .querySelectorAll("#materialCostsContainer .dynamic-row")
    .forEach((row) => {
      const materialId = row.dataset.materialId;
      if (materialId) {
        const selector = materialSelectors.find((s) => s.rowId === row.id);
        const selectedMaterial = selector?.selector.getSelectedMaterial();
        materials.push({
          id: parseInt(materialId),
          name: selectedMaterial?.material_name || "",
          quantity: parseFloat(row.querySelector(".material-qty")?.value) || 0,
          unit_cost:
            parseFloat(row.querySelector(".material-cost-per-unit")?.value) ||
            0,
          total_cost:
            parseFloat(row.querySelector(".material-total-cost")?.value) || 0,
        });
      }
    });

  const rejects = [];
  document
    .querySelectorAll("#rejectCostsContainer .dynamic-row")
    .forEach((row) => {
      const materialId = row.dataset.materialId;
      if (materialId) {
        const selector = rejectSelectors.find((s) => s.rowId === row.id);
        const selectedMaterial = selector?.selector.getSelectedMaterial();
        rejects.push({
          id: parseInt(materialId),
          name: selectedMaterial?.material_name || "",
          quantity: parseFloat(row.querySelector(".reject-qty")?.value) || 0,
          unit_cost:
            parseFloat(row.querySelector(".reject-cost-per-unit")?.value) || 0,
          total_cost:
            parseFloat(row.querySelector(".reject-total-cost")?.value) || 0,
        });
      }
    });

  const items = [];
  document.querySelectorAll("#itemsContainer .dynamic-row").forEach((row) => {
    const name = row.querySelector(".item-name")?.value;
    if (name) {
      items.push({
        name: name,
        quantity: parseFloat(row.querySelector(".item-qty")?.value) || 0,
        unit_price:
          parseFloat(row.querySelector(".item-unit-price")?.value) || 0,
        total_amount:
          parseFloat(row.querySelector(".item-total-amount")?.value) || 0,
      });
    }
  });

  const itemName = document.getElementById("auditItemName")?.value;
  if (itemName) {
    items.unshift({
      name: itemName,
      quantity: 1,
      unit_price: 0,
      total_amount: 0,
    });
  }

  if (materials.length === 0 && items.length === 0) {
    matShowToast("Please add at least one material or item", "error");
    return;
  }

  // ========== CRITICAL: Collect overhead data ==========
  const modalContainer = document.getElementById("auditModal");
  const overheadData = {
    shop_rent:
      parseFloat(modalContainer.querySelector("#overhead_shop_rent")?.value) ||
      0,
    fixed_salaries:
      parseFloat(
        modalContainer.querySelector("#overhead_fixed_salaries")?.value,
      ) || 0,
    shop_utilities:
      parseFloat(
        modalContainer.querySelector("#overhead_shop_utilities")?.value,
      ) || 0,
    subscriptions:
      parseFloat(
        modalContainer.querySelector("#overhead_subscriptions")?.value,
      ) || 0,
    machine_depreciation:
      parseFloat(
        modalContainer.querySelector("#overhead_machine_depreciation")?.value,
      ) || 0,
    maintenance_repair:
      parseFloat(
        modalContainer.querySelector("#overhead_maintenance_repair")?.value,
      ) || 0,
    marketing:
      parseFloat(modalContainer.querySelector("#overhead_marketing")?.value) ||
      0,
    electricity:
      parseFloat(
        modalContainer.querySelector("#overhead_electricity")?.value,
      ) || 0,
  };
  const productionHours =
    parseFloat(modalContainer.querySelector("#production_hours")?.value) || 0;

  const manualProfitCheck = document.getElementById("manualProfitCheck");
  const manualProfitInput = document.getElementById("manualProfitInput");
  const profitValue = manualProfitCheck?.checked
    ? parseFloat(manualProfitInput?.value) || 0
    : null;

  const payload = {
    items: items,
    materials: materials,
    rejects: rejects,
    overhead: overheadData,
    production_hours: productionHours,
    created_by: document.getElementById("createdBy")?.value || "",
    audited_by: document.getElementById("auditedBy")?.value || "",
    acknowledged_by: document.getElementById("acknowledgedBy")?.value || "",
    auto_compute: auditAutoCompute,
  };

  if (profitValue !== null) {
    payload.manual_profit = profitValue;
  }

  try {
    const result = await matFetch(MAT_API.createAudit, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    });

    matCloseAuditModal();

    const infoBanner = document.querySelector(
      "#auditModal .audit-quotation-info",
    );
    if (infoBanner) infoBanner.remove();

    if (window.currentAuditQuotationId && result.audit_id) {
      try {
        const markResponse = await fetch(
          "../../api/admin_site/mark_quotation_audited.php",
          {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
              quotation_id: window.currentAuditQuotationId,
              audit_id: result.audit_id,
            }),
          },
        );
        const markResult = await markResponse.json();
        if (markResult.success) {
          matShowToast(
            "Audit created! Quotation marked as audited.",
            "success",
          );
        } else {
          matShowToast(
            "Audit created but failed to mark quotation as audited.",
            "warning",
          );
        }
        window.currentAuditQuotationId = null;
      } catch (err) {
        console.error(err);
        matShowToast(
          "Audit created! Please manually mark quotation as audited.",
          "warning",
        );
      }
    } else {
      matShowToast(
        "Audit created successfully! Inventory has been updated.",
        "success",
      );
    }

    matReloadMaterials(true);
    matReloadLogs(true);
    if (window.quotationManager) {
      window.quotationManager.fetchQuotations();
      window.quotationManager.fetchStats();
    }
  } catch (err) {
    matShowToast(err.message || "Failed to create audit", "error");
  }
};

/* ══════════════════════════════════════════════════════════════
   AUDIT MANAGEMENT FUNCTIONS - Kept for viewing from Inventory
══════════════════════════════════════════════════════════════ */

/* ── Audit List Modal ─────────────────────────────────────────── */
let currentAuditPage = 1;

const matInitAuditList = () => {
  if (!document.getElementById("auditListModal")) {
    const modalHtml = `
        <div id="auditListModal" class="audit-list-modal">
            <div class="audit-list-container">
            
                <div class="audit-list-search">
                    <input type="text" id="auditSearchInput" placeholder="Search audits by ID, item, or creator..." 
                           oninput="matDebouncedAuditSearch()">
                </div>
                <div class="audit-list-body">
                    <div id="auditListContainer" class="audit-list-items">
                        <div class="loading-spinner">Loading audits...</div>
                    </div>
                    <div class="audit-list-pagination" id="auditListPagination"></div>
                </div>
            </div>
        </div>`;
    document.body.insertAdjacentHTML("beforeend", modalHtml);
  }

  document.getElementById("auditSearchInput")?.addEventListener("input", () => {
    clearTimeout(window.auditSearchTimer);
    window.auditSearchTimer = setTimeout(() => matLoadAuditList(1), 350);
  });
};

const matOpenAuditList = () => {
  matInitAuditList();
  matLoadAuditList(1);
  document.getElementById("auditListModal").style.display = "flex";
  document.body.style.overflow = "hidden";
};

const matCloseAuditList = () => {
  const modal = document.getElementById("auditListModal");
  if (modal) modal.style.display = "none";
  document.body.style.overflow = "";
};

const matDebouncedAuditSearch = () => {
  clearTimeout(window.auditSearchTimer);
  window.auditSearchTimer = setTimeout(() => matLoadAuditList(1), 350);
};

const matLoadAuditList = async (page = 1) => {
  currentAuditPage = page;
  const search = document.getElementById("auditSearchInput")?.value || "";
  const container = document.getElementById("auditListContainer");

  if (!container) return;
  container.innerHTML =
    '<div class="loading-spinner"><i class="fa-solid fa-spinner fa-spin"></i> Loading audits...</div>';

  try {
    const params = new URLSearchParams({
      page: page,
      per_page: 10,
      search: search,
    });

    const response = await fetch(
      `../../api/admin_site/inventory/list_audits.php?${params}`,
    );
    const data = await response.json();

    if (!data.success) throw new Error(data.message);

    matRenderAuditList(data.audits, data.pagination);
  } catch (err) {
    container.innerHTML = `<div class="error-message">Error loading audits: ${err.message}</div>`;
  }
};

const matRenderAuditList = (audits, pagination) => {
  const container = document.getElementById("auditListContainer");
  if (!container) return;

  if (!audits || audits.length === 0) {
    container.innerHTML =
      '<div class="no-results"><i class="fa-solid fa-box-open"></i> No audits found</div>';
    return;
  }

  container.innerHTML = audits
    .map((audit) => {
      const itemCount = audit.items_count || 0;
      const materialCount = audit.materials_count || 0;
      const createdBy = audit.created_by;
      const date = new Date(audit.created_at).toLocaleString();
      const profitClass =
        audit.profit >= 0 ? "profit-positive" : "profit-negative";

      const itemPreview = [
        ...audit.item_names.slice(0, 2),
        ...audit.material_names.slice(0, 2),
      ].join(", ");

      return `
        <div class="audit-list-item" data-id="${audit.id}">
            <div class="audit-item-header">
                <span class="audit-id">#${audit.id}</span>
                <span class="audit-date"><i class="fa-regular fa-calendar"></i> ${date}</span>
            </div>
            <div class="audit-item-body">
                <div class="audit-item-preview">
                    <i class="fa-solid fa-clipboard-list"></i>
                    <span>${itemPreview || "No items"} ${audit.item_names.length + audit.material_names.length > 4 ? "..." : ""}</span>
                </div>
                <div class="audit-item-stats">
                    <span class="stat-badge"><i class="fa-solid fa-cube"></i> ${itemCount} items</span>
                    <span class="stat-badge"><i class="fa-solid fa-box"></i> ${materialCount} materials</span>
                    <span class="stat-badge"><i class="fa-solid fa-chart-line"></i> ₱${(audit.total_amount_due || 0).toFixed(2)}</span>
                    <span class="stat-badge ${profitClass}"><i class="fa-solid fa-coins"></i> ₱${(audit.profit || 0).toFixed(2)}</span>
                </div>
                <div class="audit-item-creator">
                    <i class="fa-regular fa-user"></i> Created by: ${matEsc(createdBy)}
                </div>
            </div>
            <div class="audit-item-actions">
                <button class="btn-view-audit" onclick="matViewAuditDetails(${audit.id})">
                    <i class="fa-solid fa-eye"></i> View Details
                </button>
            </div>
        </div>`;
    })
    .join("");
};

const matRenderAuditPagination = (pagination) => {
  const container = document.getElementById("auditListPagination");
  if (!container) return;

  const total = pagination.total || 0;
  const currentPage = pagination.current_page || 1;
  const lastPage = pagination.last_page || 1;

  if (lastPage <= 1) {
    container.innerHTML = "";
    return;
  }

  let html = '<div class="pagination-controls">';
  html += `<button class="page-btn" ${currentPage <= 1 ? "disabled" : ""} onclick="matLoadAuditList(${currentPage - 1})">‹ Prev</button>`;

  const startPage = Math.max(1, currentPage - 2);
  const endPage = Math.min(lastPage, startPage + 4);

  if (startPage > 1) {
    html += `<button class="page-btn" onclick="matLoadAuditList(1)">1</button>`;
    if (startPage > 2) html += '<span class="page-dots">...</span>';
  }

  for (let i = startPage; i <= endPage; i++) {
    html += `<button class="page-btn ${i === currentPage ? "active" : ""}" onclick="matLoadAuditList(${i})">${i}</button>`;
  }

  if (endPage < lastPage) {
    if (endPage < lastPage - 1) html += '<span class="page-dots">...</span>';
    html += `<button class="page-btn" onclick="matLoadAuditList(${lastPage})">${lastPage}</button>`;
  }

  html += `<button class="page-btn" ${currentPage >= lastPage ? "disabled" : ""} onclick="matLoadAuditList(${currentPage + 1})">Next ›</button>`;
  html += `<span class="total-info">Total: ${total} audits</span>`;
  html += "</div>";

  container.innerHTML = html;
};

const matViewAuditDetails = async (auditId) => {
  try {
    const response = await fetch(
      `../../api/admin_site/inventory/get_audit_details.php?id=${auditId}`,
    );
    const data = await response.json();

    if (!data.success) throw new Error(data.message);

    matShowDetailedAuditModal(data.audit, data.inventory_logs, data.audit_logs);
  } catch (err) {
    matShowToast("Failed to load audit details: " + err.message, "error");
  }
};

const matShowDetailedAuditModal = (audit, inventoryLogs, auditLogs) => {
  let modal = document.getElementById("detailedAuditModal");

  if (!modal) {
    modal = document.createElement("div");
    modal.id = "detailedAuditModal";
    modal.className = "detailed-audit-modal";
    document.body.appendChild(modal);
  }

  const items = audit.items || [];
  const materials = audit.materials || [];
  const rejects = audit.rejects || [];
  const signatures = audit.signatures || {};
  const createdDate = new Date(audit.created_at).toLocaleString();

  modal.innerHTML = `
    <div class="detailed-audit-container">
        <div class="detailed-audit-header">
            <h2><i class="fa-solid fa-receipt"></i> Audit #${audit.id}</h2>
            <button class="detailed-audit-close" onclick="matCloseDetailedAudit()">&times;</button>
        </div>
        <div class="detailed-audit-tabs">
            <button class="tab-btn active" data-tab="items">Items & Materials</button>
            <button class="tab-btn" data-tab="inventory">Inventory Changes</button>
            <button class="tab-btn" data-tab="audit-log">Audit Log</button>
        </div>
        <div class="detailed-audit-body">
            <div class="tab-content active" id="tab-items">
                ${
                  items.length > 0
                    ? `
                <div class="section">
                    <h3><i class="fa-solid fa-cube"></i> Items</h3>
                    <table class="audit-detail-table">
                        <thead><tr><th>Item Name</th><th>Quantity</th><th>Unit Price</th><th>Total Amount</th></tr></thead>
                        <tbody>${items
                          .map(
                            (item) => `
                            <tr>
                                <td>${matEsc(item.name)}</td>
                                <td>${item.quantity || 0}</td>
                                <td>₱${(item.unit_price || 0).toFixed(2)}</td>
                                <td>₱${(item.total_amount || 0).toFixed(2)}</td>
                            </tr>
                        `,
                          )
                          .join("")}</tbody>
                    </table>
                </div>
                `
                    : ""
                }
                
                ${
                  materials.length > 0
                    ? `
                <div class="section">
                    <h3><i class="fa-solid fa-box"></i> Materials Used</h3>
                    <table class="audit-detail-table">
                        <thead><tr><th>Material Name</th><th>Quantity</th><th>Unit Cost</th><th>Total Cost</th></tr></thead>
                        <tbody>${materials
                          .map(
                            (mat) => `
                            <tr>
                                <td>${matEsc(mat.name)}</td>
                                <td>${mat.quantity || 0}</td>
                                <td>₱${(mat.unit_cost || 0).toFixed(4)}</td>
                                <td>₱${(mat.total_cost || 0).toFixed(2)}</td>
                            </tr>
                        `,
                          )
                          .join("")}</tbody>
                    </table>
                </div>
                `
                    : ""
                }
                
                ${
                  rejects.length > 0
                    ? `
                <div class="section">
                    <h3><i class="fa-solid fa-trash"></i> Reject Materials</h3>
                    <table class="audit-detail-table">
                        <thead><tr><th>Material Name</th><th>Quantity</th><th>Unit Cost</th><th>Total Cost</th></tr></thead>
                        <tbody>${rejects
                          .map(
                            (rej) => `
                            <tr>
                                <td>${matEsc(rej.name)}</td>
                                <td>${rej.quantity || 0}</td>
                                <td>₱${(rej.unit_cost || 0).toFixed(4)}</td>
                                <td>₱${(rej.total_cost || 0).toFixed(2)}</td>
                            </tr>
                        `,
                          )
                          .join("")}</tbody>
                    </table>
                </div>
                `
                    : ""
                }
                
<div class="totals-summary">
  <div class="total-row">Total Material Cost: <strong>₱${(audit.total_material_cost || 0).toFixed(2)}</strong></div>
  <div class="total-row">Total Reject Cost: <strong>₱${(audit.total_reject_cost || 0).toFixed(2)}</strong></div>
  <div class="total-row">Total Overhead: <strong>₱${(audit.total_overhead || 0).toFixed(2)}</strong></div>
  <div class="total-row">Total Amount Due: <strong>₱${(audit.total_amount_due || 0).toFixed(2)}</strong></div>
  <div class="total-row profit">Profit: <strong>₱${(audit.profit || 0).toFixed(2)}</strong></div>
</div>
                
                <div class="signatures-section">
                    <div><strong>Created By:</strong> ${matEsc(signatures.created_by || "—")}</div>
                    <div><strong>Audited By:</strong> ${matEsc(signatures.audited_by || "—")}</div>
                    <div><strong>Acknowledged By:</strong> ${matEsc(signatures.acknowledged_by || "—")}</div>
                    <div><strong>Date:</strong> ${createdDate}</div>
                </div>
            </div>
            
            <div class="tab-content" id="tab-inventory">
                ${
                  inventoryLogs && inventoryLogs.length > 0
                    ? `
                <table class="audit-detail-table">
                    <thead>
                        <tr>
                            <th>Material</th>
                            <th>Change Type</th>
                            <th>Quantity</th>
                            <th>Before → After</th>
                            <th>Admin</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${inventoryLogs
                          .map(
                            (log) => `
                            <tr>
                                <td>${matEsc(log.material_name)}</td>
                                <td><span class="badge ${matLogTypeBadge(log.change_type).cls}">${log.change_type}</span></td>
                                <td class="${log.new_stock < log.previous_stock ? "delta-neg" : "delta-pos"}">${log.new_stock - log.previous_stock}</td>
                                <td>${log.previous_stock} → ${log.new_stock}</td>
                                <td>${matEsc(log.admin_name)}</td>
                                <td>${new Date(log.created_at).toLocaleString()}</td>
                            </tr>
                        `,
                          )
                          .join("")}
                    </tbody>
                </table>
                `
                    : '<div class="no-data">No inventory changes recorded for this audit</div>'
                }
            </div>
            
            <div class="tab-content" id="tab-audit-log">
                ${
                  auditLogs && auditLogs.length > 0
                    ? `
                <table class="audit-detail-table">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Admin</th>
                            <th>Details</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${auditLogs
                          .map(
                            (alog) => `
                            <tr>
                                <td><span class="badge badge-info">${alog.action}</span></td>
                                <td>${matEsc(alog.admin_name)}</td>
                                <td>${
                                  alog.details
                                    ? (() => {
                                        try {
                                          const details = JSON.parse(
                                            alog.details,
                                          );
                                          return `${details.items_count || 0} items, ${details.materials_count || 0} materials`;
                                        } catch (e) {
                                          return alog.details;
                                        }
                                      })()
                                    : "—"
                                }</td>
                                <td>${new Date(alog.created_at).toLocaleString()}</td>
                            </tr>
                        `,
                          )
                          .join("")}
                    </tbody>
                </table>
                `
                    : '<div class="no-data">No audit logs found</div>'
                }
            </div>
        </div>
        <div class="detailed-audit-footer">
            <button class="btn-close" onclick="matCloseDetailedAudit()">Close</button>
        </div>
    </div>`;

  const tabs = modal.querySelectorAll(".tab-btn");
  tabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      const tabId = tab.dataset.tab;
      tabs.forEach((t) => t.classList.remove("active"));
      tab.classList.add("active");

      modal.querySelectorAll(".tab-content").forEach((content) => {
        content.classList.remove("active");
      });
      modal.querySelector(`#tab-${tabId}`).classList.add("active");
    });
  });

  modal.style.display = "flex";
  document.body.style.overflow = "hidden";
};

const matCloseDetailedAudit = () => {
  const modal = document.getElementById("detailedAuditModal");
  if (modal) modal.style.display = "none";
  document.body.style.overflow = "";
};
/* ══════════════════════════════════════════════════════════════
   PENDING AUDIT QUOTATIONS - AUTO SHOW MODAL
══════════════════════════════════════════════════════════════ */

// Call this when the inventory page loads - AFTER DOM is ready
function initPendingAuditCheck() {
  console.log("Checking for pending audit...");

  // Check sessionStorage for pending audit
  const pendingQuotationId = sessionStorage.getItem(
    "pending_audit_quotation_id",
  );
  const pendingQuotationNumber = sessionStorage.getItem(
    "pending_audit_quotation_number",
  );
  const pendingTimestamp = sessionStorage.getItem("pending_audit_timestamp");

  console.log("SessionStorage values:", {
    id: pendingQuotationId,
    number: pendingQuotationNumber,
    timestamp: pendingTimestamp,
  });

  if (pendingQuotationId && pendingQuotationNumber) {
    // Clear the storage immediately to prevent multiple triggers
    sessionStorage.removeItem("pending_audit_quotation_id");
    sessionStorage.removeItem("pending_audit_quotation_number");
    sessionStorage.removeItem("pending_audit_timestamp");

    // Small delay to ensure page is fully loaded
    setTimeout(() => {
      console.log(
        "Opening pending audit modal for quotation:",
        pendingQuotationId,
      );
      matShowToast(
        `Ready to create audit for Quotation ${pendingQuotationNumber}`,
        "info",
      );

      // Open the pending audit modal and auto-select this quotation
      openPendingAuditModalWithSelection(
        pendingQuotationId,
        pendingQuotationNumber,
      );
    }, 800);
  } else {
    // Optional: Still check if there are any pending audits on page load
    setTimeout(() => {
      checkForPendingAuditsAndShowBadge();
    }, 500);
  }
}

// Open modal and automatically select a specific quotation
async function openPendingAuditModalWithSelection(
  quotationId,
  quotationNumber,
) {
  // First, open the modal
  openPendingAuditModal();

  // Wait for modal to open and load data
  setTimeout(async () => {
    // Find and select the specific quotation
    const selectedItem = document.querySelector(
      `.pending-audit-item[data-id="${quotationId}"]`,
    );

    if (selectedItem) {
      // Scroll to the item
      selectedItem.scrollIntoView({ behavior: "smooth", block: "center" });

      // Highlight it
      selectedItem.style.transition = "all 0.3s";
      selectedItem.style.backgroundColor = "#dbeafe";
      selectedItem.style.borderColor = "#2563eb";

      // Add selected class
      selectedItem.classList.add("selected");

      // Store the selected quotation
      selectedPendingQuotation = pendingQuotationsList.find(
        (q) => q.id == quotationId,
      );

      // Show a confirmation message
      matShowToast(
        `Quotation ${quotationNumber} is selected. Click it to start the audit.`,
        "info",
      );

      // Auto-select after 1 second
      setTimeout(() => {
        if (selectedPendingQuotation) {
          closePendingAuditModal();
          openAuditModalWithQuotation(quotationId, quotationNumber);
        }
      }, 1500);
    } else {
      // If quotation not found in list, show message
      matShowToast(
        `Quotation ${quotationNumber} is ready for audit. Please select it from the list.`,
        "warning",
      );
    }
  }, 1000);
}

// Open the pending audit modal
function openPendingAuditModal() {
  const modal = document.getElementById("pendingAuditModal");
  if (!modal) {
    console.error("Pending audit modal not found in DOM");
    return;
  }

  modal.style.display = "flex";
  document.body.style.overflow = "hidden";
  loadPendingAuditQuotations();
}

// Close the pending audit modal
function closePendingAuditModal() {
  const modal = document.getElementById("pendingAuditModal");
  if (modal) modal.style.display = "none";
  document.body.style.overflow = "";
  selectedPendingQuotation = null;
}

// Load pending audit quotations from server
async function loadPendingAuditQuotations() {
  const listContainer = document.getElementById("pendingAuditList");
  if (!listContainer) return;

  listContainer.innerHTML =
    '<div class="loading-spinner"><i class="fa-solid fa-spinner fa-spin"></i> Loading pending quotations...</div>';

  try {
    const response = await fetch(
      "../../api/admin_site/get_pending_audit_quotations.php",
    );
    const result = await response.json();

    console.log("Pending audits response:", result);

    if (result.success) {
      pendingQuotationsList = result.quotations;
      renderPendingAuditList(pendingQuotationsList);

      // Show modal title with count
      const headerTitle = document.querySelector("#pendingAuditModal h3");
      if (headerTitle && result.count > 0) {
        headerTitle.innerHTML = `<i class="fa-solid fa-clipboard-list"></i> Pending Audit Quotations (${result.count})`;
      }
    } else {
      listContainer.innerHTML = `<div class="empty-pending"><i class="fa-solid fa-exclamation-triangle"></i><p>${result.message}</p></div>`;
    }
  } catch (error) {
    console.error("Error loading pending audits:", error);
    listContainer.innerHTML =
      '<div class="empty-pending"><i class="fa-solid fa-circle-exclamation"></i><p>Failed to load quotations</p></div>';
  }
}

// Render the list of pending audit quotations
function renderPendingAuditList(quotations) {
  const listContainer = document.getElementById("pendingAuditList");
  if (!listContainer) return;

  if (!quotations || quotations.length === 0) {
    listContainer.innerHTML =
      '<div class="empty-pending"><i class="fa-solid fa-check-circle"></i><p>No pending audits! All delivered quotations have been audited.</p></div>';
    return;
  }

  listContainer.innerHTML = quotations
    .map(
      (quote) => `
        <div class="pending-audit-item" data-id="${quote.id}" onclick="selectPendingQuotation(${quote.id})">
            <div class="pending-audit-item-header">
                <span class="pending-audit-quote-num"><i class="fa-solid fa-file-invoice"></i> ${escapeHtml(quote.quote_number)}</span>
                <span class="pending-audit-date"><i class="fa-regular fa-calendar"></i> ${new Date(quote.created_at).toLocaleDateString()}</span>
            </div>
            <div class="pending-audit-client">
                <i class="fa-solid fa-building"></i> ${escapeHtml(quote.client_name)}
            </div>
            <div class="pending-audit-details">
                <span><i class="fa-regular fa-user"></i> ${escapeHtml(quote.contact_person || "N/A")}</span>
                <span><i class="fa-regular fa-envelope"></i> ${escapeHtml(quote.email || "N/A")}</span>
                <span><i class="fa-solid fa-phone"></i> ${escapeHtml(quote.phone || "N/A")}</span>
                <span><i class="fa-solid fa-money-bill"></i> ₱ ${parseFloat(quote.total || 0).toFixed(2)}</span>
            </div>
        </div>
    `,
    )
    .join("");
}

// Filter pending audit list by search term
function filterPendingAuditList() {
  const searchTerm =
    document.getElementById("pendingAuditSearch")?.value.toLowerCase() || "";

  if (!searchTerm) {
    renderPendingAuditList(pendingQuotationsList);
    return;
  }

  const filtered = pendingQuotationsList.filter(
    (quote) =>
      quote.quote_number.toLowerCase().includes(searchTerm) ||
      quote.client_name.toLowerCase().includes(searchTerm) ||
      (quote.contact_person &&
        quote.contact_person.toLowerCase().includes(searchTerm)) ||
      (quote.email && quote.email.toLowerCase().includes(searchTerm)),
  );

  renderPendingAuditList(filtered);
}

// Select a quotation and open audit modal with its data
async function selectPendingQuotation(quotationId) {
  const selectedItem = document.querySelector(".pending-audit-item.selected");
  if (selectedItem) {
    selectedItem.classList.remove("selected");
  }

  const newSelected = document.querySelector(
    `.pending-audit-item[data-id="${quotationId}"]`,
  );
  if (newSelected) {
    newSelected.classList.add("selected");
  }

  selectedPendingQuotation = pendingQuotationsList.find(
    (q) => q.id == quotationId,
  );

  if (selectedPendingQuotation) {
    // Close the pending audit modal
    closePendingAuditModal();

    // Open the audit modal with quotation data
    await openAuditModalWithQuotation(
      selectedPendingQuotation.id,
      selectedPendingQuotation.quote_number,
    );
  }
}

// Check for pending audits and show a badge/notification (optional)
async function checkForPendingAuditsAndShowBadge() {
  try {
    const response = await fetch(
      "../../api/admin_site/get_pending_audit_quotations.php",
    );
    const result = await response.json();

    if (result.success && result.count > 0) {
      console.log(`${result.count} pending audit(s) waiting`);
      // Optional: Show a small badge or notification
      matShowToast(
        `${result.count} quotation(s) need inventory audit. Click the Audit button to process.`,
        "info",
      );
    }
  } catch (err) {
    console.error("Error checking pending audits:", err);
  }
}

// ============================================================
// HELPER FUNCTION: Add Audit List Button to UI
// ============================================================
function matAddAuditListButton() {
  // Find the top button container
  const topBtnContainer = document.querySelector(".top-btn");
  if (!topBtnContainer) {
    console.warn("Could not find .top-btn container for audit list button");
    return;
  }

  // Check if button already exists
  if (document.getElementById("matViewAuditsBtn")) return;

  const auditBtn = document.createElement("button");
  auditBtn.id = "matViewAuditsBtn";
  auditBtn.className = "btn-audit-list";
  auditBtn.innerHTML = '<i class="fa-solid fa-clipboard-list"></i> View Audits';
  auditBtn.onclick = matOpenAuditList;
  auditBtn.style.cssText = `
    padding: 8px 16px;
    border: none;
    border-radius: 8px;
    background: #2563eb;
    color: #fff;
    cursor: pointer;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
  `;

  topBtnContainer.appendChild(auditBtn);
}

// Make sure the Escape key listener is properly set
if (typeof document !== "undefined") {
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      matCloseDetailedAudit();
      matCloseAuditList();
      matCloseModal();
      matCloseAddItemModal();
      matCloseAuditModal();
    }
  });
}
/* ══════════════════════════════════════════════════════════════
   IMPORT/EXPORT
══════════════════════════════════════════════════════════════ */
const matOpenImportModal = () => {
  document.getElementById("matImportModal").style.display = "flex";
};
const matCloseImportModal = () => {
  document.getElementById("matImportModal").style.display = "none";
};
const matDownloadSampleCSV = () => {
  const sampleData = [
    [
      "Type",
      "Materials",
      "Shop",
      "PH",
      "Total On Hand",
      "Total Cost",
      "Quantity per pack",
      "Unit Cost",
      "Remarks",
    ],
    [
      "Ink",
      "Sample Ink Black",
      "5",
      "10",
      "15",
      "95.00",
      "1",
      "95.0000",
      "Test material",
    ],
    [
      "Paper",
      "Sample Photo Paper",
      "20",
      "30",
      "50",
      "135.00",
      "20",
      "6.7500",
      "For testing",
    ],
  ];

  // FIX: Use UTF-8 without BOM
  let csvContent = sampleData.map((row) => row.join(",")).join("\n");

  // NO BOM - just plain UTF-8
  const blob = new Blob([csvContent], {
    type: "text/csv;charset=utf-8",
  });

  const link = document.createElement("a");
  const url = URL.createObjectURL(blob);
  link.href = url;
  link.setAttribute("download", "materials_sample.csv");
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
  URL.revokeObjectURL(url);
};

const matProcessImport = async () => {
  const fileInput = document.getElementById("importCSVFile");
  const file = fileInput.files[0];
  if (!file) {
    matShowToast("Please select a file", "error");
    return;
  }

  const formData = new FormData();
  formData.append("csv_file", file);

  try {
    const response = await fetch(MAT_API.import, {
      method: "POST",
      body: formData,
    });
    const text = await response.text();
    let data;
    try {
      data = JSON.parse(text);
    } catch (e) {
      throw new Error(
        "Server returned invalid response: " + text.substring(0, 100),
      );
    }
    if (data.success) {
      matShowToast(data.message, "success");
      matCloseImportModal();
      matReloadMaterials(true);
      fileInput.value = "";
    } else if (data.sheets) {
      // Handle multi-sheet Excel files - display sheet selector
      const sheetSelector = document.getElementById("matImportSheetSelector");
      const sheetSelect = document.getElementById("matImportSheetSelect");
      if (sheetSelector && sheetSelect) {
        sheetSelect.innerHTML = "";
        data.sheets.forEach((sheet, idx) => {
          const option = document.createElement("option");
          option.value = idx;
          option.textContent = sheet;
          sheetSelect.appendChild(option);
        });
        sheetSelector.style.display = "block";
      }
      matShowToast("Please select a sheet to import from", "info");
    } else {
      matShowToast(data.message, "error");
    }
  } catch (err) {
    matShowToast("Import failed: " + err.message, "error");
  }
};

const matExportData = () => {
  window.location.href = MAT_API.export;
};

// ============================================================
// DELETE ALL MATERIALS
// ============================================================
const matOpenDeleteAllModal = () => {
  const modal = document.getElementById("matDeleteAllModal");
  if (modal) {
    document.getElementById("matDeleteAllPassword").value = "";
    const feedback = document.getElementById("matDeleteAllFeedback");
    if (feedback) {
      feedback.style.display = "none";
      feedback.textContent = "";
    }
    modal.style.display = "flex";
    document.getElementById("matDeleteAllPassword").focus();
  }
};

const matCloseDeleteAllModal = () => {
  const modal = document.getElementById("matDeleteAllModal");
  if (modal) {
    modal.style.display = "none";
    document.getElementById("matDeleteAllPassword").value = "";
    const feedback = document.getElementById("matDeleteAllFeedback");
    if (feedback) {
      feedback.style.display = "none";
      feedback.textContent = "";
    }
  }
};

const matConfirmDeleteAll = async () => {
  const password = document.getElementById("matDeleteAllPassword").value.trim();
  const feedback = document.getElementById("matDeleteAllFeedback");
  const confirmBtn = document.getElementById("matDeleteAllConfirmBtn");

  if (!password) {
    if (feedback) {
      feedback.style.display = "block";
      feedback.className = "text-danger";
      feedback.style.color = "#dc2626";
      feedback.style.backgroundColor = "#fee2e2";
      feedback.textContent = "Please enter your admin password.";
    }
    return;
  }

  if (confirmBtn) confirmBtn.disabled = true;
  if (confirmBtn)
    confirmBtn.innerHTML =
      '<i class="fa-solid fa-spinner fa-spin"></i> Deleting...';

  try {
    const response = await fetch(MAT_API.deleteAll, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ password }),
    });

    const data = await response.json();

    if (data.success) {
      if (feedback) {
        feedback.style.display = "block";
        feedback.className = "text-success";
        feedback.style.color = "#166534";
        feedback.style.backgroundColor = "#f0fdf4";
        feedback.textContent =
          data.message || "All materials deleted successfully.";
      }
      matShowToast(
        data.message || "All materials deleted successfully.",
        "success",
      );
      setTimeout(() => {
        matCloseDeleteAllModal();
        matReloadMaterials(true);
      }, 800);
    } else {
      if (feedback) {
        feedback.style.display = "block";
        feedback.className = "text-danger";
        feedback.style.color = "#dc2626";
        feedback.style.backgroundColor = "#fee2e2";
        feedback.textContent =
          data.message || "Unable to delete all materials.";
      }
      matShowToast(data.message || "Unable to delete all materials.", "error");
    }
  } catch (err) {
    if (feedback) {
      feedback.style.display = "block";
      feedback.className = "text-danger";
      feedback.style.color = "#dc2626";
      feedback.style.backgroundColor = "#fee2e2";
      feedback.textContent = "Network error. Please try again.";
    }
    matShowToast("Network error: " + err.message, "error");
  } finally {
    if (confirmBtn) confirmBtn.disabled = false;
    if (confirmBtn)
      confirmBtn.innerHTML =
        '<i class="fa-solid fa-trash"></i> Delete All Materials';
  }
};
