// ============================================================
//  admin_materials.js (FIXED)
//
//  Manages material inventory UI and API interactions.
//  All function names, element IDs, and globals prefixed "mat"
//  to prevent collision with admin_products.js.
//
//  FIXES:
//  - Robust JSON parsing with error handling
//  - Response status checking
//  - Content-Type verification
//  - Debug logging
// ============================================================

/* ── API Endpoints ─────────────────────────────────────────── */
const MAT_API = {
  materials: "../../api/admin_site/inventory/get_materials.php",
  logs: "../../api/admin_site/inventory/get_materials_logs.php",
  update: "../../api/admin_site/inventory/update_materials_stock.php",
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

/* ══════════════════════════════════════════════════════════════
   BOOT
══════════════════════════════════════════════════════════════ */
document.addEventListener("DOMContentLoaded", () => {
  console.log("🚀 Materials inventory page loaded");
  matInitModal();
  matInitToast();
  matLoadMaterials();
  matLoadLogs();
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
  if (!tbody) {
    console.error("❌ materialsTableBody element not found in DOM");
    return;
  }

  tbody.innerHTML = matLoadingRow(8, "Loading materials…");

  try {
    console.log(
      "📊 Loading materials with params:",
      Object.fromEntries(params),
    );
    const data = await matFetch(`${MAT_API.materials}?${params}`);

    // Verify response has expected data
    if (!data.materials) {
      throw new Error("Response missing 'materials' field");
    }

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
      return `
      <div class="${cls}">
        <i class="fa-solid ${icon}"></i>
        <span><strong>${matEsc(m.material_name)}</strong> — ${label}</span>
        <button class="alert-update-btn"
                data-id="${m.id}"
                data-name="${matEsc(m.material_name)}"
                data-stock="${m.total_stock}">Update</button>
      </div>`;
    })
    .join("");

  container.querySelectorAll(".alert-update-btn").forEach((btn) => {
    btn.addEventListener("click", () =>
      matOpenModal(btn.dataset.id, btn.dataset.name, btn.dataset.stock),
    );
  });
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
      return `
      <tr>
        <td><div class="mat-cell"><span>${matEsc(m.material_name)}</span></div></td>
        <td>${matEsc(m.type || "—")}</td>
        <td><strong>${m.shop_stock}</strong></td>
        <td><strong>${m.ph_stock}</strong></td>
        <td><strong>${m.total_stock}</strong></td>
        <td>${costPerUnit}</td>
        <td><span class="badge ${badge.cls}">${badge.label}</span></td>
        <td>
          <button class="btn-action mat-update-btn"
                  data-id="${m.id}"
                  data-name="${matEsc(m.material_name)}"
                  data-stock="${m.total_stock}">
            <i class="fa-solid fa-pen-to-square"></i> Update
          </button>
        </td>
      </tr>`;
    })
    .join("");

  tbody.querySelectorAll(".mat-update-btn").forEach((btn) => {
    btn.addEventListener("click", () =>
      matOpenModal(btn.dataset.id, btn.dataset.name, btn.dataset.stock),
    );
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
  if (!tbody) {
    console.error("❌ materialsLogsTableBody element not found in DOM");
    return;
  }

  tbody.innerHTML = matLoadingRow(9, "Loading logs…");

  try {
    console.log("📋 Loading logs with params:", Object.fromEntries(params));
    const data = await matFetch(`${MAT_API.logs}?${params}`);

    if (!data.logs) {
      throw new Error("Response missing 'logs' field");
    }

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
    tbody.innerHTML = matErrorRow(9, `Error: ${err.message}`);
  }
};

window.matReloadLogs = (reset = false) => {
  if (reset) matState.logsPage = 1;
  matLoadLogs();
};

const matRenderLogRows = (logs = []) => {
  const tbody = document.getElementById("materialsLogsTableBody");
  if (!tbody) return;

  if (!logs.length) {
    tbody.innerHTML = matLoadingRow(9, "No log entries found.");
    return;
  }

  tbody.innerHTML = logs
    .map((l) => {
      const delta = l.new_stock - l.previous_stock;
      const deltaStr = (delta >= 0 ? "+" : "") + delta;
      const deltaCls =
        delta > 0 ? "delta-pos" : delta < 0 ? "delta-neg" : "delta-zero";
      const tb = matLogTypeBadge(l.change_type);
      const locBadge = matLocationBadge(l.location);
      const dateStr = new Date(l.created_at).toLocaleString();

      return `
      <tr>
        <td><div class="mat-cell"><span>${matEsc(l.material_name)}</span></div></td>
        <td><span class="badge ${tb.cls}">${tb.label}</span></td>
        <td><span class="badge ${locBadge.cls}">${locBadge.label}</span></td>
        <td class="qty-cell"><span class="${deltaCls}">${deltaStr}</span></td>
        <td class="stock-range">${l.previous_stock} → ${l.new_stock}</td>
        <td>${matEsc(l.admin_name || "—")}</td>
        <td class="note-cell" title="${matEsc(l.note || "")}">${matEsc(l.note || "—")}</td>
        <td class="date-cell">${dateStr}</td>
      </tr>`;
    })
    .join("");
};

/* ══════════════════════════════════════════════════════════════
   MODAL
══════════════════════════════════════════════════════════════ */
const matInitModal = () => {
  const overlay = document.getElementById("matStockModal");
  if (overlay && overlay.parentElement !== document.body) {
    document.body.appendChild(overlay);
  }

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
    console.log("📝 Submitting update:", {
      material_id: matState.modal.materialId,
      location: matState.modal.location,
      action: matState.modal.action,
      quantity: qty,
    });

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
    console.error("❌ Update failed:", err);
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

/**
 * ROBUST FETCH WITH ERROR HANDLING
 *
 * Checks:
 * - Response status (200-299)
 * - Content-Type is JSON
 * - Response can be parsed as JSON
 * - API success flag is true
 *
 * Logs all steps for debugging
 */
const matFetch = async (url, options = {}) => {
  console.log("🔍 Fetching:", url);

  try {
    const res = await fetch(url, options);

    // ── CHECK RESPONSE STATUS ────────────────────
    if (!res.ok) {
      console.error(`❌ HTTP ${res.status}:`, res.statusText);
      throw new Error(`HTTP ${res.status}: ${res.statusText}`);
    }

    // ── CHECK CONTENT-TYPE ───────────────────────
    const contentType = res.headers.get("content-type");
    if (!contentType || !contentType.includes("application/json")) {
      const text = await res.text();
      console.error("❌ Response is not JSON. Content-Type:", contentType);
      console.error("❌ Response body:", text.substring(0, 500));
      throw new Error(`Expected JSON, got ${contentType || "unknown"}`);
    }

    // ── PARSE JSON ───────────────────────────────
    let data;
    try {
      data = await res.json();
    } catch (parseErr) {
      console.error("❌ JSON parse error:", parseErr);
      throw new Error(`Failed to parse JSON response: ${parseErr.message}`);
    }

    // ── CHECK API SUCCESS FLAG ───────────────────
    if (!data.success) {
      const message = data.message || "Unknown server error";
      console.error("❌ API returned success=false:", message);
      throw new Error(message);
    }

    console.log("✅ Success:", data);
    return data;
  } catch (err) {
    console.error("❌ matFetch Error:", err.message);
    throw err; // Re-throw so calling code knows it failed
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
