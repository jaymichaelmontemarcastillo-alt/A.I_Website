// ============================================================
//  audit_logs_manager.js
//  Handles: Audit Log Tab, Audit Details Modal, View Audit
//  FIXED VERSION - Proper separation from manual logs
// ============================================================

/* ── API Endpoints ─────────────────────────────────────────── */
const AUDIT_API = {
  listAudits: "../../api/admin_site/inventory/list_audits.php",
  getAuditDetails: "../../api/admin_site/inventory/get_audit_details.php",
  getAudit: "../../api/admin_site/inventory/get_audit.php",
};

/* ── State ─────────────────────────────────────────────────── */
let currentAuditPage = 1;
let auditSearchTimer = null;

// Helper function to safely parse numeric values
function safeParseFloat(value, defaultValue = 0) {
  if (value === null || value === undefined || value === "")
    return defaultValue;
  const parsed = parseFloat(value);
  return isNaN(parsed) ? defaultValue : parsed;
}

function safeParseInt(value, defaultValue = 0) {
  if (value === null || value === undefined || value === "")
    return defaultValue;
  const parsed = parseInt(value, 10);
  return isNaN(parsed) ? defaultValue : parsed;
}

/* ══════════════════════════════════════════════════════════════
   LOAD AUDIT LOGS (Only audit-created changes)
══════════════════════════════════════════════════════════════ */
async function loadAuditLogs(page = 1) {
  const search = document.getElementById("auditSearchInput")?.value || "";
  const tbody = document.getElementById("auditLogsTableBody");

  if (!tbody) return;

  currentAuditPage = page;

  tbody.innerHTML =
    '<tr><td colspan="8" class="loading-cell"><i class="fa-solid fa-spinner fa-spin"></i> Loading audits...<\/td><\/tr>';

  try {
    const params = new URLSearchParams({
      page: page,
      per_page: 10,
      search: search,
    });

    const response = await fetch(
      `../../api/admin_site/inventory/list_audits.php?${params}`,
    );

    if (!response.ok) {
      throw new Error(`HTTP ${response.status}: ${response.statusText}`);
    }

    const data = await response.json();

    if (data.success) {
      if (data.audits && data.audits.length > 0) {
        renderAuditLogs(data.audits);
      } else {
        tbody.innerHTML =
          '<tr><td colspan="8" class="loading-cell">No audit records found<\/td><\/tr>';
      }
      renderAuditPagination(data.pagination);
    } else {
      tbody.innerHTML = `<tr><td colspan="8" class="loading-cell error-cell">Error: ${data.message || "Failed to load audits"}<\/td><\/tr>`;
    }
  } catch (err) {
    console.error("Error loading audit logs:", err);
    tbody.innerHTML = `<tr><td colspan="8" class="loading-cell error-cell">Failed to load audits: ${err.message}<\/td><\/tr>`;
  }
}

/* ══════════════════════════════════════════════════════════════
   RENDER AUDIT LOGS TABLE (With working View button)
══════════════════════════════════════════════════════════════ */
function renderAuditLogs(audits) {
  const tbody = document.getElementById("auditLogsTableBody");
  if (!tbody) return;

  if (!audits || audits.length === 0) {
    tbody.innerHTML =
      '<tr><td colspan="8" class="loading-cell">No audit records found<\/td><\/tr>';
    return;
  }

  tbody.innerHTML = audits
    .map((audit) => {
      // CRITICAL FIX: SAFELY parse numeric values - .toFixed() will work now
      const profit = safeParseFloat(audit.profit);
      const totalAmount = safeParseFloat(audit.total_amount_due);

      const profitClass = profit >= 0 ? "profit-positive" : "profit-negative";

      const dateStr = audit.created_at
        ? new Date(audit.created_at).toLocaleString()
        : "Unknown";

      const createdBy = audit.created_by || audit.signatures?.created_by || "—";

      // Safely get item names
      let itemsList = "—";
      if (audit.items && Array.isArray(audit.items) && audit.items.length > 0) {
        const itemNames = audit.items
          .slice(0, 2)
          .map((i) => i?.name || "Unknown")
          .join(", ");
        itemsList =
          itemNames +
          (audit.items.length > 2 ? ` +${audit.items.length - 2} more` : "");
      }

      // Safely get material names
      let materialsList = "—";
      if (
        audit.materials &&
        Array.isArray(audit.materials) &&
        audit.materials.length > 0
      ) {
        const materialNames = audit.materials
          .slice(0, 2)
          .map((m) => m?.name || "Unknown")
          .join(", ");
        materialsList =
          materialNames +
          (audit.materials.length > 2
            ? ` +${audit.materials.length - 2} more`
            : "");
      }

      const auditId = safeParseInt(audit.id);

      return `
      <tr>
        <td><strong>#${auditId || "?"}</strong></td>
        <td title="${Array.isArray(audit.items) ? audit.items.map((i) => i?.name).join("\n") : ""}">${escapeHtml(itemsList)}</td>
        <td title="${Array.isArray(audit.materials) ? audit.materials.map((m) => m?.name).join("\n") : ""}">${escapeHtml(materialsList)}</td>
        <td>₱${totalAmount.toFixed(2)}</td>
        <td class="${profitClass}">₱${profit.toFixed(2)}</td>
        <td>${escapeHtml(createdBy)}</td>
        <td>${dateStr}</td>
        <td>
          <button class="btn-action view-audit-btn" data-audit-id="${auditId}">
            <i class="fa-solid fa-eye"></i> View
          </button>
          </td>
        </tr>
    `;
    })
    .join("");

  // Attach event listeners to View buttons
  tbody.querySelectorAll(".view-audit-btn").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      const auditId = btn.getAttribute("data-audit-id");
      if (auditId) {
        viewAuditDetails(auditId);
      }
    });
  });
}

/* ══════════════════════════════════════════════════════════════
   RENDER PAGINATION for Audit Logs
══════════════════════════════════════════════════════════════ */
function renderAuditPagination(pagination) {
  const infoEl = document.getElementById("auditLogsPageInfo");
  const pagerEl = document.getElementById("auditLogsPager");

  if (!infoEl || !pagerEl) return;

  const total = pagination?.total || 0;
  const currentPage = pagination?.current_page || 1;
  const lastPage = pagination?.last_page || 1;
  const perPage = pagination?.per_page || 10;

  if (total === 0) {
    infoEl.textContent = "No results";
    pagerEl.innerHTML = "";
    return;
  }

  const from = (currentPage - 1) * perPage + 1;
  const to = Math.min(currentPage * perPage, total);
  infoEl.textContent = `${from}–${to} of ${total}`;

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
    mkBtn("‹", currentPage <= 1, () => loadAuditLogs(currentPage - 1)),
  );

  const startPage = Math.max(1, currentPage - 2);
  const endPage = Math.min(lastPage, startPage + 4);

  if (startPage > 1) {
    pagerEl.appendChild(mkBtn("1", false, () => loadAuditLogs(1)));
    if (startPage > 2) {
      const dots = document.createElement("span");
      dots.className = "page-dots";
      dots.textContent = "…";
      pagerEl.appendChild(dots);
    }
  }

  for (let i = startPage; i <= endPage; i++) {
    const btn = mkBtn(i, false, () => loadAuditLogs(i));
    if (i === currentPage) btn.classList.add("active");
    pagerEl.appendChild(btn);
  }

  if (endPage < lastPage) {
    if (endPage < lastPage - 1) {
      const dots = document.createElement("span");
      dots.className = "page-dots";
      dots.textContent = "…";
      pagerEl.appendChild(dots);
    }
    pagerEl.appendChild(mkBtn(lastPage, false, () => loadAuditLogs(lastPage)));
  }

  pagerEl.appendChild(
    mkBtn("›", currentPage >= lastPage, () => loadAuditLogs(currentPage + 1)),
  );
}

/* ══════════════════════════════════════════════════════════════
   VIEW AUDIT DETAILS MODAL
══════════════════════════════════════════════════════════════ */
async function viewAuditDetails(auditId) {
  console.log("viewAuditDetails called for audit ID:", auditId);

  if (!auditId || auditId <= 0) {
    showToast("Invalid audit ID", "error");
    return;
  }

  try {
    const response = await fetch(
      `../../api/admin_site/inventory/get_audit_details.php?id=${auditId}`,
    );
    const data = await response.json();

    if (data.success) {
      showDetailedAuditModal(data.audit, data.inventory_logs, data.audit_logs);
    } else {
      showToast(
        "Failed to load audit details: " + (data.message || "Unknown error"),
        "error",
      );
    }
  } catch (err) {
    console.error("Error loading audit details:", err);
    showToast("Error loading audit details: " + err.message, "error");
  }
}

/* ══════════════════════════════════════════════════════════════
   SHOW DETAILED AUDIT MODAL
══════════════════════════════════════════════════════════════ */
function showDetailedAuditModal(audit, inventoryLogs, auditLogs) {
  // Remove existing modal if any
  let modal = document.getElementById("detailedAuditModal");
  if (modal) {
    modal.remove();
  }

  modal = document.createElement("div");
  modal.id = "detailedAuditModal";
  modal.className = "detailed-audit-modal";
  document.body.appendChild(modal);

  const items = audit.items || [];
  const materials = audit.materials || [];
  const rejects = audit.rejects || [];
  const signatures = audit.signatures || {};
  const createdDate = audit.created_at
    ? new Date(audit.created_at).toLocaleString()
    : "Unknown";

  // SAFELY parse numeric values
  const totalMaterialCost = safeParseFloat(audit.total_material_cost);
  const totalRejectCost = safeParseFloat(audit.total_reject_cost);
  const totalAmountDue = safeParseFloat(audit.total_amount_due);
  const profit = safeParseFloat(audit.profit);

  modal.innerHTML = `
    <div class="detailed-audit-container">
      <div class="detailed-audit-header">
        <h2><i class="fa-solid fa-receipt"></i> Audit #${audit.id}</h2>
        <button class="detailed-audit-close" onclick="closeDetailedAudit()">&times;</button>
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
                    <td>${escapeHtml(item.name)}</td>
                    <td>${safeParseInt(item.quantity)}</td>
                    <td>₱${safeParseFloat(item.unit_price).toFixed(2)}</td>
                    <td>₱${safeParseFloat(item.total_amount).toFixed(2)}</td>
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
                    <td>${escapeHtml(mat.name)}</td>
                    <td>${safeParseInt(mat.quantity)}</td>
                    <td>₱${safeParseFloat(mat.unit_cost).toFixed(4)}</td>
                    <td>₱${safeParseFloat(mat.total_cost).toFixed(2)}</td>
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
                    <td>${escapeHtml(rej.name)}</td>
                    <td>${safeParseInt(rej.quantity)}</td>
                    <td>₱${safeParseFloat(rej.unit_cost).toFixed(4)}</td>
                    <td>₱${safeParseFloat(rej.total_cost).toFixed(2)}</td>
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
            <div class="total-row">Total Material Cost: <strong>₱${totalMaterialCost.toFixed(2)}</strong></div>
            <div class="total-row">Total Reject Cost: <strong>₱${totalRejectCost.toFixed(2)}</strong></div>
            <div class="total-row">Total Amount Due: <strong>₱${totalAmountDue.toFixed(2)}</strong></div>
            <div class="total-row profit">Profit: <strong>₱${profit.toFixed(2)}</strong></div>
          </div>
          
          <div class="signatures-section">
            <div><strong>Created By:</strong> ${escapeHtml(signatures.created_by || "—")}</div>
            <div><strong>Audited By:</strong> ${escapeHtml(signatures.audited_by || "—")}</div>
            <div><strong>Acknowledged By:</strong> ${escapeHtml(signatures.acknowledged_by || "—")}</div>
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
                  .map((log) => {
                    const logType = log.change_type || "adjust";
                    const logTypeBadge = getLogTypeBadge(logType);
                    const delta =
                      safeParseInt(log.new_stock) -
                      safeParseInt(log.previous_stock);
                    const deltaClass = delta < 0 ? "delta-neg" : "delta-pos";
                    return `
                    <tr>
                      <td>${escapeHtml(log.material_name)}</td>
                      <td><span class="badge ${logTypeBadge.cls}">${logTypeBadge.label}</span></td>
                      <td class="${deltaClass}">${delta > 0 ? "+" : ""}${delta}</td>
                      <td>${safeParseInt(log.previous_stock)} → ${safeParseInt(log.new_stock)}</td>
                      <td>${escapeHtml(log.admin_name)}</td>
                      <td>${new Date(log.created_at).toLocaleString()}</td>
                    </tr>
                  `;
                  })
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
                  .map((alog) => {
                    let detailsText = "—";
                    if (alog.details) {
                      try {
                        const details = JSON.parse(alog.details);
                        detailsText = `${details.items_count || 0} items, ${details.materials_count || 0} materials`;
                      } catch (e) {
                        detailsText = alog.details.substring(0, 100);
                      }
                    }
                    return `
                    <tr>
                      <td><span class="badge badge-info">${escapeHtml(alog.action)}</span></td>
                      <td>${escapeHtml(alog.admin_name)}</td>
                      <td>${escapeHtml(detailsText)}</td>
                      <td>${new Date(alog.created_at).toLocaleString()}</td>
                    </tr>
                  `;
                  })
                  .join("")}
              </tbody>
            </table>
          `
              : '<div class="no-data">No audit logs found</div>'
          }
        </div>
      </div>
      <div class="detailed-audit-footer">
        <button class="btn-close" onclick="closeDetailedAudit()">Close</button>
      </div>
    </div>
  `;

  // Tab switching
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
}

function closeDetailedAudit() {
  const modal = document.getElementById("detailedAuditModal");
  if (modal) modal.style.display = "none";
  document.body.style.overflow = "";
}

function getLogTypeBadge(type) {
  const map = {
    add: { cls: "badge-success", label: "Add" },
    subtract: { cls: "badge-danger", label: "Remove" },
    order: { cls: "badge-info", label: "Order" },
    return: { cls: "badge-warning", label: "Return" },
    adjust: { cls: "badge-muted", label: "Adjust" },
  };
  return map[type] || { cls: "badge-muted", label: type };
}

function escapeHtml(str) {
  if (!str) return "";
  return String(str)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#39;");
}

function showToast(message, type = "success") {
  let container = document.getElementById("matToastContainer");
  if (!container) {
    container = document.createElement("div");
    container.id = "matToastContainer";
    container.className = "toast-container";
    document.body.appendChild(container);
  }
  const icon = type === "success" ? "fa-circle-check" : "fa-circle-exclamation";
  const toast = document.createElement("div");
  toast.className = `toast toast-${type}`;
  toast.innerHTML = `<i class="fa-solid ${icon}"></i><span>${escapeHtml(message)}</span>`;
  container.appendChild(toast);
  requestAnimationFrame(() => toast.classList.add("show"));
  setTimeout(() => {
    toast.classList.remove("show");
    toast.addEventListener("transitionend", () => toast.remove(), {
      once: true,
    });
  }, 3500);
}

// Debounced search
function debouncedAuditSearch() {
  clearTimeout(auditSearchTimer);
  auditSearchTimer = setTimeout(() => loadAuditLogs(1), 350);
}

// Initialize Audit Log Tab
function initAuditLogTab() {
  console.log("Initializing Audit Log Tab");

  const auditSearchInput = document.getElementById("auditSearchInput");
  if (auditSearchInput) {
    auditSearchInput.removeEventListener("input", debouncedAuditSearch);
    auditSearchInput.addEventListener("input", debouncedAuditSearch);
  }

  // Load audits when tab is active
  const auditTab = document.getElementById("audit-tab");
  if (auditTab && auditTab.classList.contains("active")) {
    loadAuditLogs(1);
  }

  // Also set up tab switching listener
  const auditTabBtn = document.querySelector(
    '.logs-tab-btn[data-tab="audit-tab"]',
  );
  if (auditTabBtn) {
    auditTabBtn.removeEventListener("click", onAuditTabClick);
    auditTabBtn.addEventListener("click", onAuditTabClick);
  }
}

function onAuditTabClick() {
  console.log("Audit tab clicked - loading audits");
  setTimeout(() => {
    loadAuditLogs(1);
  }, 100);
}

// Export global functions
window.loadAuditLogs = loadAuditLogs;
window.viewAuditDetails = viewAuditDetails;
window.closeDetailedAudit = closeDetailedAudit;
window.debouncedAuditSearch = debouncedAuditSearch;
window.initAuditLogTab = initAuditLogTab;

// Initialize when DOM is ready
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initAuditLogTab);
} else {
  initAuditLogTab();
}
