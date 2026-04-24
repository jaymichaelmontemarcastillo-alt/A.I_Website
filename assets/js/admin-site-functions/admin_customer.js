/**
 * Customer Management - Admin Site
 * Path: assets/js/admin-site-function/customers.js
 *
 * Handles customer list loading, filtering, searching, and modal details
 */

(() => {
  "use strict";

  // ── State ────────────────────────────────────────────────────────────────
  const state = {
    search: "",
    filter: "all",
    page: 1,
    allRows: [],
    isLoading: false,
  };

  // ── DOM refs (initialized after DOM ready) ──────────────────────────────
  let dom = {};

  /**
   * Initialize DOM references - must be called after DOM is ready
   */
  function initDOMRefs() {
    dom = {
      tbody: document.getElementById("customerTbody"),
      searchInput: document.getElementById("searchInput"),
      filterPills: document.querySelectorAll(".pill"),
      statCards: document.querySelectorAll(".stat-card"),
      paginationBar: document.getElementById("paginationBar"),
      modalOverlay: document.getElementById("modalOverlay"),
      modalClose: document.getElementById("modalClose"),
      modalLoading: document.getElementById("modalLoading"),
      modalContent: document.getElementById("modalContent"),
    };

    // Validate all refs exist
    const missing = Object.entries(dom)
      .filter(([_, el]) => {
        if (el instanceof NodeList) return el.length === 0;
        return !el;
      })
      .map(([name]) => name);

    if (missing.length) {
      console.error("Missing DOM elements:", missing);
      return false;
    }

    return true;
  }

  // ── Utility Functions ────────────────────────────────────────────────────
  const fmt = (n) =>
    "₱" +
    parseFloat(n || 0).toLocaleString("en-PH", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    });

  const dateFmt = (d) =>
    d
      ? new Date(d).toLocaleDateString("en-PH", {
          month: "short",
          day: "numeric",
          year: "numeric",
        })
      : "—";

  const initials = (name) =>
    (name || "?")
      .trim()
      .split(/\s+/)
      .map((w) => w[0])
      .join("")
      .slice(0, 2)
      .toUpperCase();

  function badgeClass(type) {
    const map = {
      Buyer: "buyer",
      "Returning Customer": "returning",
      "Quotation Only": "quote-only",
    };
    return map[type] || "buyer";
  }

  function statusBadge(status) {
    const s = (status || "").toLowerCase().replace(/\s+/g, "-");
    return `<span class="badge ${s}">${status || "—"}</span>`;
  }

  function escHtml(str) {
    return String(str || "")
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;");
  }

  // ── Fetch & Render ──────────────────────────────────────────────────────
  async function loadCustomers() {
    if (state.isLoading) return; // Prevent duplicate requests
    state.isLoading = true;

    // Show loading state
    dom.tbody.innerHTML = `
            <tr class="loading-row">
                <td colspan="9">
                    <div class="loading">
                        <div class="spinner"></div>
                        <span>Loading customers…</span>
                    </div>
                </td>
            </tr>
        `;

    const params = new URLSearchParams({
      search: state.search,
      filter: state.filter === "all" ? "" : state.filter,
      page: state.page,
    });

    try {
      const res = await fetch(
        `../../api/admin_site/customers/fetch_customers.php?${params}`,
      );
      const data = await res.json();

      if (!data.success) throw new Error(data.error || "Unknown error");

      updateSummary(data.summary);
      renderTable(data.customers);
      renderPagination(data.pagination);
      state.allRows = data.customers;
    } catch (e) {
      console.error("Load failed:", e);
      dom.tbody.innerHTML = `
                <tr>
                    <td colspan="9" style="text-align:center;padding:20px;color:#dc2626">
                        ❌ Failed to load customers<br>
                        <small>${escHtml(e.message)}</small><br><br>
                        <button onclick="window.customerApp.loadCustomers()" style="padding:6px 12px;cursor:pointer;background:#3b82f6;color:white;border:none;border-radius:4px">
                            Retry
                        </button>
                    </td>
                </tr>
            `;
    } finally {
      state.isLoading = false;
    }
  }

  function updateSummary(summary) {
    document.getElementById("statTotal").textContent =
      (+summary.total_customers).toLocaleString();
    document.getElementById("statActive").textContent =
      (+summary.active_customers).toLocaleString();
    document.getElementById("statQuoteOnly").textContent =
      (+summary.quotation_only).toLocaleString();
    document.getElementById("statRepeat").textContent =
      (+summary.repeat_customers).toLocaleString();
  }

  function renderTable(rows) {
    if (!rows || !Array.isArray(rows)) {
      dom.tbody.innerHTML = `
                <tr>
                    <td colspan="9" style="text-align:center;padding:40px;color:#dc2626">
                        Invalid data received
                    </td>
                </tr>
            `;
      return;
    }

    if (!rows.length) {
      dom.tbody.innerHTML = `
                <tr>
                    <td colspan="9" style="text-align:center;padding:40px;color:#6b7280">
                        <i class="fa-solid fa-users-slash" style="font-size:24px;display:block;margin-bottom:8px"></i>
                        No customers found
                    </td>
                </tr>
            `;
      return;
    }

    dom.tbody.innerHTML = rows
      .map((c) => {
        const type = c.customer_type;
        const bClass = badgeClass(type);
        return `
                    <tr data-email="${escHtml(c.email)}" data-phone="${escHtml(c.phone)}">
                        <td>
                            <div class="customer-cell">
                                <div class="avatar">${initials(c.name)}</div>
                                <span>${escHtml(c.name || "Unknown")}</span>
                            </div>
                        </td>
                        <td class="email-row">${escHtml(c.email || "—")}</td>
                        <td>${escHtml(c.phone || "—")}</td>
                        <td><strong>${c.total_orders}</strong></td>
                        <td>${c.total_quotations}</td>
                        <td class="currency">${fmt(c.total_spent)}</td>
                        <td>${dateFmt(c.last_activity)}</td>
                        <td><span class="badge ${bClass}">${type}</span></td>
                        <td>
                            <button class="btn-view" title="View Profile">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </button>
                        </td>
                    </tr>`;
      })
      .join("");

    // Attach row click handlers
    dom.tbody
      .querySelectorAll("tr[data-email], tr[data-phone]")
      .forEach((row) => {
        row.addEventListener("click", () => {
          openModal(row.dataset.email, row.dataset.phone);
        });
      });
  }

  function renderPagination(p) {
    if (p.total_pages <= 1) {
      dom.paginationBar.innerHTML = "";
      return;
    }

    let html = `<span class="pg-info">Showing ${(p.current_page - 1) * p.per_page + 1}–${Math.min(p.current_page * p.per_page, p.total_rows)} of ${p.total_rows}</span>`;
    html += `<button class="pg-btn" ${p.current_page <= 1 ? "disabled" : ""} data-page="${p.current_page - 1}"><i class="fa-solid fa-chevron-left"></i></button>`;

    const start = Math.max(1, p.current_page - 2);
    const end = Math.min(p.total_pages, start + 4);
    for (let i = start; i <= end; i++) {
      html += `<button class="pg-btn ${i === p.current_page ? "current" : ""}" data-page="${i}">${i}</button>`;
    }

    html += `<button class="pg-btn" ${p.current_page >= p.total_pages ? "disabled" : ""} data-page="${p.current_page + 1}"><i class="fa-solid fa-chevron-right"></i></button>`;

    dom.paginationBar.innerHTML = html;

    // Attach pagination handlers
    dom.paginationBar.querySelectorAll(".pg-btn[data-page]").forEach((btn) => {
      btn.addEventListener("click", () => {
        state.page = +btn.dataset.page;
        loadCustomers();
      });
    });
  }

  // ── Modal Functions ─────────────────────────────────────────────────────
  async function openModal(email, phone) {
    document.body.classList.add("modal-open");
    dom.modalOverlay.classList.add("open");
    dom.modalLoading.style.display = "block";
    dom.modalContent.style.display = "none";

    const params = new URLSearchParams();
    if (email) params.set("email", email);
    if (phone) params.set("phone", phone);

    try {
      const res = await fetch(
        `../../api/admin_site/customers/fetch_customer_details.php?${params}`,
      );
      const data = await res.json();

      if (!data.success) throw new Error(data.error || "Failed to load");

      populateModal(data);
    } catch (e) {
      dom.modalLoading.innerHTML = `<p style="color:#dc2626"><i class="fa-solid fa-circle-exclamation"></i> ${escHtml(e.message)}</p>`;
    }
  }

  function populateModal(data) {
    const { info, orders, quotations, summary } = data;

    document.getElementById("modalAvatar").textContent = initials(info.name);
    document.getElementById("modalCustomerName").textContent =
      info.name || "Unknown";
    document.getElementById("modalCustomerEmail").textContent =
      info.email || "—";
    document.getElementById("modalCustomerPhone").textContent =
      info.phone || "—";

    const typeEl = document.getElementById("modalCustomerType");
    typeEl.textContent = summary.customer_type;
    typeEl.className = `badge ${badgeClass(summary.customer_type)}`;

    document.getElementById("mTileOrders").textContent = summary.total_orders;
    document.getElementById("mTileQuotes").textContent =
      summary.total_quotations;
    document.getElementById("mTileSpent").textContent = fmt(
      summary.total_spent,
    );
    document.getElementById("mTileActivity").textContent = dateFmt(
      summary.last_activity,
    );

    // Orders list
    const ordersList = document.getElementById("ordersList");
    const ordersEmpty = document.getElementById("ordersEmpty");
    if (orders.length) {
      ordersEmpty.style.display = "none";
      ordersList.innerHTML = orders
        .map(
          (o) => `
                    <div class="history-card">
                        <div class="history-card-top">
                            <span class="history-card-num">${escHtml(o.order_number)}</span>
                            <span class="history-card-date">${dateFmt(o.created_at)}</span>
                        </div>
                        ${o.items_summary ? `<div class="history-card-items"><i class="fa-solid fa-box" style="font-size:11px;margin-right:4px"></i>${escHtml(o.items_summary)}</div>` : ""}
                        <div class="history-card-foot">
                            <span class="history-card-amount">${fmt(o.total_amount)}</span>
                            <span style="display:flex;gap:6px;flex-wrap:wrap;align-items:center">
                                ${statusBadge(o.payment_method)}
                                ${statusBadge(o.order_status)}
                            </span>
                        </div>
                    </div>`,
        )
        .join("");
    } else {
      ordersEmpty.style.display = "block";
      ordersList.innerHTML = "";
    }

    // Quotations list
    const quotesList = document.getElementById("quotesList");
    const quotesEmpty = document.getElementById("quotesEmpty");
    if (quotations.length) {
      quotesEmpty.style.display = "none";
      quotesList.innerHTML = quotations
        .map(
          (q) => `
                    <div class="history-card">
                        <div class="history-card-top">
                            <span class="history-card-num">${escHtml(q.quote_number)}</span>
                            <span class="history-card-date">${dateFmt(q.created_at)}</span>
                        </div>
                        ${q.items_summary ? `<div class="history-card-items"><i class="fa-solid fa-file-lines" style="font-size:11px;margin-right:4px"></i>${escHtml(q.items_summary)}</div>` : ""}
                        <div class="history-card-foot">
                            <span class="history-card-amount">${fmt(q.total)}</span>
                            <span style="display:flex;gap:6px;flex-wrap:wrap;align-items:center">
                                ${statusBadge(q.status)}
                                ${q.expires_at ? `<span style="font-size:11px;color:#6b7280">Expires ${dateFmt(q.expires_at)}</span>` : ""}
                            </span>
                        </div>
                    </div>`,
        )
        .join("");
    } else {
      quotesEmpty.style.display = "block";
      quotesList.innerHTML = "";
    }

    switchTab("orders");
    dom.modalLoading.style.display = "none";
    dom.modalContent.style.display = "block";
  }

  function switchTab(name) {
    document
      .querySelectorAll(".m-tab")
      .forEach((t) => t.classList.toggle("active", t.dataset.tab === name));
    document
      .querySelectorAll(".tab-panel")
      .forEach((p) =>
        p.classList.toggle(
          "active",
          p.id === "tab" + name.charAt(0).toUpperCase() + name.slice(1),
        ),
      );
  }

  function closeModal() {
    document.body.classList.remove("modal-open");
    dom.modalOverlay.classList.remove("open");
  }

  // ── Event Handlers ──────────────────────────────────────────────────────
  function attachEventListeners() {
    // Modal close
    dom.modalClose.addEventListener("click", closeModal);
    dom.modalOverlay.addEventListener("click", (e) => {
      if (e.target === dom.modalOverlay) closeModal();
    });
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") closeModal();
    });

    // Tab switching
    document.querySelectorAll(".m-tab").forEach((btn) => {
      btn.addEventListener("click", () => switchTab(btn.dataset.tab));
    });

    // Search with debounce
    let searchTimer;
    dom.searchInput.addEventListener("input", () => {
      clearTimeout(searchTimer);
      searchTimer = setTimeout(() => {
        state.search = dom.searchInput.value.trim();
        state.page = 1;
        loadCustomers();
      }, 350);
    });

    // Filter pills
    dom.filterPills.forEach((pill) => {
      pill.addEventListener("click", () => {
        dom.filterPills.forEach((p) => p.classList.remove("active"));
        pill.classList.add("active");
        state.filter = pill.dataset.filter;
        state.page = 1;
        dom.statCards.forEach((c) =>
          c.classList.toggle(
            "active-filter",
            c.dataset.filter === state.filter,
          ),
        );
        loadCustomers();
      });
    });

    // Stat cards
    dom.statCards.forEach((card) => {
      card.addEventListener("click", () => {
        const f = card.dataset.filter;
        dom.filterPills.forEach((p) =>
          p.classList.toggle("active", p.dataset.filter === f),
        );
        dom.statCards.forEach((c) =>
          c.classList.toggle("active-filter", c.dataset.filter === f),
        );
        state.filter = f;
        state.page = 1;
        loadCustomers();
      });
    });

    // CSV Export
    const btnExport = document.getElementById("btnExport");
    if (btnExport) {
      btnExport.addEventListener("click", async () => {
        const params = new URLSearchParams({
          search: state.search,
          filter: state.filter,
          page: 1,
          limit: 9999,
        });
        const res = await fetch(
          `../../api/admin_site/customers/fetch_customers.php?${params}`,
        );
        const data = await res.json();
        if (!data.success) return;

        const rows = data.customers;
        const cols = [
          "Name",
          "Email",
          "Phone",
          "Total Orders",
          "Total Quotations",
          "Total Spent",
          "Last Activity",
          "Customer Type",
        ];
        const csv = [
          cols.join(","),
          ...rows.map((r) =>
            [
              `"${(r.name || "").replace(/"/g, '""')}"`,
              `"${(r.email || "").replace(/"/g, '""')}"`,
              `"${(r.phone || "").replace(/"/g, '""')}"`,
              r.total_orders,
              r.total_quotations,
              parseFloat(r.total_spent || 0).toFixed(2),
              r.last_activity ? r.last_activity.split("T")[0] : "",
              `"${r.customer_type || ""}"`,
            ].join(","),
          ),
        ].join("\n");

        const blob = new Blob([csv], { type: "text/csv" });
        const url = URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        a.download = `customers_${new Date().toISOString().slice(0, 10)}.csv`;
        a.click();
        URL.revokeObjectURL(url);
      });
    }
  }

  // ── Initialize ──────────────────────────────────────────────────────────
  function init() {
    if (!initDOMRefs()) {
      console.error("Failed to initialize DOM references");
      return;
    }

    attachEventListeners();

    // Load customers on initialization
    loadCustomers();
  }

  // Ensure DOM is ready before initializing
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }

  // Expose public API for external calls (like retry button)
  window.customerApp = {
    loadCustomers,
    getState: () => ({ ...state }),
  };
})();
