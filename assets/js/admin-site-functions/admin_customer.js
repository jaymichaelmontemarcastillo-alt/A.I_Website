/**
 * Customer Management - Admin Site
 * Based on Quotations table
 * assets/js/admin-site-functions/admin_customers.js
 */

(() => {
  "use strict";

  const state = {
    search: "",
    filter: "all",
    page: 1,
    customers: [],
    isLoading: false,
  };

  let dom = {};

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
      btnExport: document.getElementById("btnExport"),
    };

    const missing = Object.entries(dom)
      .filter(([_, el]) => {
        if (el instanceof NodeList) return el.length === 0;
        return !el;
      })
      .map(([name]) => name);

    if (missing.length > 0) {
      console.error("❌ Missing DOM elements:", missing);
      return false;
    }
    console.log("✓ All DOM elements initialized");
    return true;
  }

  function formatCurrency(amount) {
    return (
      "₱" +
      parseFloat(amount || 0).toLocaleString("en-PH", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      })
    );
  }

  function formatDate(date) {
    if (!date) return "—";
    try {
      const d = new Date(date);
      if (isNaN(d.getTime())) return "—";
      return d.toLocaleDateString("en-PH", {
        year: "numeric",
        month: "short",
        day: "numeric",
      });
    } catch (e) {
      return "—";
    }
  }

  function getInitials(name) {
    if (!name || name === "Unknown") return "?";
    return name
      .trim()
      .split(/\s+/)
      .map((w) => w[0])
      .join("")
      .slice(0, 2)
      .toUpperCase();
  }

  function escapeHtml(str) {
    if (!str) return "";
    return String(str)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
  }

  function showSearchResultsCount(count) {
    let indicator = document.querySelector(".search-results-count");
    if (!indicator) {
      indicator = document.createElement("div");
      indicator.className = "search-results-count";
      const tableContainer = document.querySelector(".table-container");
      const tableHeader = document.querySelector(".table-header");
      if (tableHeader) {
        tableHeader.insertAdjacentElement("afterend", indicator);
      } else if (tableContainer) {
        tableContainer.insertBefore(indicator, tableContainer.firstChild);
      }
    }

    if (count === 0) {
      indicator.innerHTML = `<i class="fa-solid fa-search"></i> No customers found for "${escapeHtml(state.search)}"`;
      indicator.style.background = "#fef2f2";
      indicator.style.color = "#dc2626";
    } else {
      indicator.innerHTML = `<i class="fa-solid fa-search"></i> Found ${count} customer${count !== 1 ? "s" : ""} for "${escapeHtml(state.search)}"`;
      indicator.style.background = "#eff6ff";
      indicator.style.color = "#2563eb";
    }
    indicator.style.display = "block";
  }

  function hideSearchResultsCount() {
    const indicator = document.querySelector(".search-results-count");
    if (indicator) {
      indicator.style.display = "none";
    }
  }

  async function fetchCustomers() {
    if (state.isLoading) return;
    state.isLoading = true;

    console.log("🔄 Fetching customers...");
    console.log(
      "Current state - Search:",
      state.search,
      "Filter:",
      state.filter,
      "Page:",
      state.page,
    );

    // Show loading state
    if (dom.tbody) {
      dom.tbody.innerHTML = `
        <tr class="loading-row">
          <td colspan="7">
            <div class="loading">
              <div class="spinner"></div>
              <span>Loading customers...</span>
            </div>
          </td>
        </tr>
      `;
    }

    let apiUrl;
    // ALWAYS use search API if there's a search term, regardless of filter
    if (state.search && state.search.trim() !== "") {
      const params = new URLSearchParams({
        search: state.search,
        page: state.page,
        limit: 20,
      });
      apiUrl = `../../api/admin_site/customers/search_customers.php?${params}`;
      console.log("📡 Using SEARCH API:", apiUrl);
    } else {
      const params = new URLSearchParams({
        filter: state.filter,
        page: state.page,
        limit: 20,
      });
      apiUrl = `../../api/admin_site/customers/fetch_customers.php?${params}`;
      console.log("📡 Using MAIN API:", apiUrl);
    }

    try {
      const response = await fetch(apiUrl);

      if (!response.ok) {
        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
      }

      const data = await response.json();
      console.log("✓ API Response:", data);

      if (!data.success) {
        throw new Error(data.error || "API returned success: false");
      }

      // Update stats ONLY when not searching (main API returns stats)
      if (data.stats && (!state.search || state.search === "")) {
        updateStats(data.stats);
      }

      // Render customers
      if (data.customers && Array.isArray(data.customers)) {
        console.log(`Rendering ${data.customers.length} customers`);
        renderTable(data.customers);
        state.customers = data.customers;
      } else {
        console.warn("No customers array in response");
        renderTable([]);
      }

      // Update pagination
      if (data.pagination) {
        renderPagination(data.pagination);
      }

      // Show/hide search results info
      if (state.search && state.search !== "") {
        showSearchResultsCount(
          data.results_count || data.customers?.length || 0,
        );
      } else {
        hideSearchResultsCount();
      }
    } catch (error) {
      console.error("❌ Fetch error:", error);
      if (dom.tbody) {
        dom.tbody.innerHTML = `
          <tr>
            <td colspan="7" style="text-align:center;padding:40px;color:#dc2626">
              <div style="font-size:24px;margin-bottom:12px">❌</div>
              <strong>Failed to load customers</strong><br>
              <small>${escapeHtml(error.message)}</small><br><br>
              <button onclick="location.reload()" style="padding:8px 16px;cursor:pointer;background:#3b82f6;color:white;border:none;border-radius:4px">
                Retry
              </button>
              <div style="margin-top:16px;font-size:12px;color:#6b7280">
                Search term: "${escapeHtml(state.search)}"
              </div>
            </td>
          </tr>
        `;
      }
    } finally {
      state.isLoading = false;
    }
  }

  function updateStats(stats) {
    console.log("📊 Updating stats:", stats);

    const elements = {
      statTotal: stats.total_customers,
      statHasQuotations: stats.has_quotations,
      statHighValue: stats.high_value,
      statDelivered: stats.delivered,
      statCancelled: stats.cancelled,
      statPending: stats.pending,
    };

    for (const [id, value] of Object.entries(elements)) {
      const el = document.getElementById(id);
      if (el) {
        el.textContent = (value || 0).toLocaleString();
      }
    }
  }

  function renderTable(customers) {
    console.log("📋 Rendering table with", customers.length, "customers");

    if (!dom.tbody) return;

    if (!customers || customers.length === 0) {
      dom.tbody.innerHTML = `
        <tr>
          <td colspan="7" style="text-align:center;padding:40px;color:#6b7280">
            <div style="font-size:32px;margin-bottom:8px">👥</div>
            <strong>No customers found</strong>
            ${state.search ? "<br><small>Try adjusting your search criteria</small>" : ""}
          </td>
        </tr>
      `;
      return;
    }

    let html = "";
    for (const c of customers) {
      html += `
        <tr data-email="${escapeHtml(c.email)}" data-phone="${escapeHtml(c.phone)}">
          <td>
            <div class="customer-cell">
              <div class="avatar">${escapeHtml(getInitials(c.name))}</div>
              <span>${escapeHtml(c.name || "Unknown")}</span>
            </div>
          </td>
          <td>${escapeHtml(c.email || "—")}</td>
          <td>${escapeHtml(c.phone || "—")}</td>
          <td><strong>${c.total_orders || 0}</strong></td>
          <td title="${escapeHtml(c.address)}" style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
            ${escapeHtml(c.address || "—")}
          </td>
          <td>${formatDate(c.last_order)}</td>
          <td>
            <button class="btn-view" title="View Profile" data-email="${escapeHtml(c.email)}" data-phone="${escapeHtml(c.phone)}">
              <i class="fa-solid fa-eye"></i>
            </button>
          </td>
        </tr>
      `;
    }

    dom.tbody.innerHTML = html;

    // Attach click handlers for view buttons
    document.querySelectorAll(".btn-view").forEach((btn) => {
      btn.addEventListener("click", (e) => {
        e.stopPropagation();
        const email = btn.getAttribute("data-email");
        const phone = btn.getAttribute("data-phone");
        openModal(email, phone);
      });
    });
  }

  function renderPagination(pagination) {
    if (!dom.paginationBar) return;

    if (!pagination || pagination.total_pages <= 1) {
      dom.paginationBar.innerHTML = "";
      return;
    }

    const start = (pagination.current_page - 1) * pagination.per_page + 1;
    const end = Math.min(
      pagination.current_page * pagination.per_page,
      pagination.total_rows,
    );

    let html = `<span class="pg-info">Showing ${start}–${end} of ${pagination.total_rows}</span>`;
    html += `<button class="pg-btn" ${pagination.current_page <= 1 ? "disabled" : ""} data-page="${pagination.current_page - 1}"><i class="fa-solid fa-chevron-left"></i></button>`;

    const start_btn = Math.max(1, pagination.current_page - 2);
    const end_btn = Math.min(pagination.total_pages, start_btn + 4);

    for (let i = start_btn; i <= end_btn; i++) {
      html += `<button class="pg-btn ${i === pagination.current_page ? "current" : ""}" data-page="${i}">${i}</button>`;
    }

    html += `<button class="pg-btn" ${pagination.current_page >= pagination.total_pages ? "disabled" : ""} data-page="${pagination.current_page + 1}"><i class="fa-solid fa-chevron-right"></i></button>`;

    dom.paginationBar.innerHTML = html;

    dom.paginationBar.querySelectorAll(".pg-btn[data-page]").forEach((btn) => {
      btn.addEventListener("click", () => {
        state.page = parseInt(btn.dataset.page);
        fetchCustomers();
      });
    });
  }

  async function openModal(email, phone) {
    console.log("🔍 Opening modal for:", email || phone);

    if (!dom.modalOverlay || !dom.modalLoading || !dom.modalContent) return;

    document.body.classList.add("modal-open");
    dom.modalOverlay.classList.add("open");
    dom.modalLoading.style.display = "block";
    dom.modalContent.style.display = "none";

    const params = new URLSearchParams();
    if (email && email !== "—") params.set("email", email);
    if (phone && phone !== "—") params.set("phone", phone);

    const apiUrl = `../../api/admin_site/customers/fetch_customer_details.php?${params}`;
    console.log("📡 Loading details from:", apiUrl);

    try {
      const response = await fetch(apiUrl);

      if (!response.ok) {
        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
      }

      const data = await response.json();
      console.log("✓ Customer details:", data);

      if (!data.success) {
        throw new Error(data.error || "Failed to load customer details");
      }

      populateModal(data);
    } catch (error) {
      console.error("❌ Modal error:", error);
      if (dom.modalLoading) {
        dom.modalLoading.innerHTML = `<p style="color:#dc2626"><i class="fa-solid fa-circle-exclamation"></i> ${escapeHtml(error.message)}</p>`;
      }
    }
  }

  function populateModal(data) {
    const { info, quotations, summary } = data;

    // Set profile info
    const nameEl = document.getElementById("modalCustomerName");
    const emailEl = document.getElementById("modalCustomerEmail");
    const phoneEl = document.getElementById("modalCustomerPhone");
    const addressEl = document.getElementById("modalCustomerAddress");
    const avatarEl = document.getElementById("modalAvatar");

    if (nameEl) nameEl.textContent = info.name || "Unknown";
    if (emailEl) emailEl.textContent = info.email || "—";
    if (phoneEl) phoneEl.textContent = info.phone || "—";
    if (addressEl) addressEl.textContent = info.address || "—";
    if (avatarEl) avatarEl.textContent = getInitials(info.name);

    // Set summary tiles
    const quotesEl = document.getElementById("mTileQuotes");
    const totalEl = document.getElementById("mTileTotal");
    const convertedEl = document.getElementById("mTileConverted");
    const lastQuoteEl = document.getElementById("mTileLastQuote");

    if (quotesEl) quotesEl.textContent = summary.total_quotations || 0;
    if (totalEl) totalEl.textContent = formatCurrency(summary.total_amount);
    if (convertedEl) convertedEl.textContent = summary.converted_count || 0;
    if (lastQuoteEl)
      lastQuoteEl.textContent = formatDate(summary.last_quote_date);

    // Render quotations list
    const quotesList = document.getElementById("quotesList");
    const quotesEmpty = document.getElementById("quotesEmpty");

    if (quotations && quotations.length > 0) {
      if (quotesEmpty) quotesEmpty.style.display = "none";
      if (quotesList) {
        quotesList.innerHTML = quotations
          .map(
            (q) => `
            <div class="history-card">
              <div class="history-card-top">
                <span class="history-card-num">${escapeHtml(q.quote_number)}</span>
                <span class="history-card-date">${formatDate(q.created_at)}</span>
              </div>
              ${q.items_summary ? `<div class="history-card-items"><i class="fa-solid fa-file-lines"></i> ${escapeHtml(q.items_summary)}</div>` : ""}
              <div class="history-card-foot">
                <span class="history-card-amount">${formatCurrency(q.total)}</span>
                <div style="display:flex;gap:6px;">
                  <span class="status-badge ${escapeHtml(q.status)}">${escapeHtml(q.status || "draft")}</span>
                  ${q.expires_at ? `<span style="font-size:11px;color:#6b7280">Expires ${formatDate(q.expires_at)}</span>` : ""}
                </div>
              </div>
            </div>
          `,
          )
          .join("");
      }
    } else {
      if (quotesEmpty) quotesEmpty.style.display = "block";
      if (quotesList) quotesList.innerHTML = "";
    }

    // Show modal content
    if (dom.modalLoading) dom.modalLoading.style.display = "none";
    if (dom.modalContent) dom.modalContent.style.display = "block";
    console.log("✓ Modal populated");
  }

  function closeModal() {
    document.body.classList.remove("modal-open");
    if (dom.modalOverlay) dom.modalOverlay.classList.remove("open");
  }

  function addClearSearchButton() {
    const searchWrapper = document.querySelector(".search-bar");
    if (!searchWrapper) return;

    // Check if clear button already exists
    if (searchWrapper.querySelector(".search-clear")) return;

    const clearBtn = document.createElement("button");
    clearBtn.className = "search-clear";
    clearBtn.innerHTML = '<i class="fa-solid fa-times"></i>';
    clearBtn.style.cssText =
      "position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#9ca3af;display:none;";
    clearBtn.onclick = () => {
      if (dom.searchInput) {
        dom.searchInput.value = "";
        state.search = "";
        state.page = 1;
        fetchCustomers();
        clearBtn.style.display = "none";
        hideSearchResultsCount();
      }
    };

    // Make search wrapper relative for absolute positioning
    searchWrapper.style.position = "relative";
    searchWrapper.appendChild(clearBtn);

    // Show/hide clear button based on input
    if (dom.searchInput) {
      dom.searchInput.addEventListener("input", () => {
        clearBtn.style.display = dom.searchInput.value ? "flex" : "none";
      });
    }
  }

  function attachEventListeners() {
    if (dom.modalClose) dom.modalClose.addEventListener("click", closeModal);
    if (dom.modalOverlay) {
      dom.modalOverlay.addEventListener("click", (e) => {
        if (e.target === dom.modalOverlay) closeModal();
      });
    }
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") closeModal();
    });

    // Search with debounce
    let searchTimer;
    if (dom.searchInput) {
      dom.searchInput.addEventListener("input", () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
          state.search = dom.searchInput.value.trim();
          state.page = 1; // Reset to first page when searching
          console.log("🔍 Searching for:", state.search);
          fetchCustomers();
        }, 350);
      });
    }

    // Filter pills
    if (dom.filterPills) {
      dom.filterPills.forEach((pill) => {
        pill.addEventListener("click", () => {
          // Clear search when using filter pills
          if (dom.searchInput && state.search) {
            dom.searchInput.value = "";
            state.search = "";
          }
          dom.filterPills.forEach((p) => p.classList.remove("active"));
          pill.classList.add("active");
          state.filter = pill.dataset.filter;
          state.page = 1;
          console.log("🏷️ Filter:", state.filter);
          fetchCustomers();
        });
      });
    }

    // Stat cards
    if (dom.statCards) {
      dom.statCards.forEach((card) => {
        card.addEventListener("click", () => {
          // Clear search when using stat cards
          if (dom.searchInput && state.search) {
            dom.searchInput.value = "";
            state.search = "";
          }
          const filter = card.dataset.filter;
          if (dom.filterPills) {
            dom.filterPills.forEach((pill) => {
              pill.classList.toggle("active", pill.dataset.filter === filter);
            });
          }
          state.filter = filter;
          state.page = 1;
          console.log("📊 Stat card filter:", state.filter);
          fetchCustomers();
        });
      });
    }

    // CSV Export
    if (dom.btnExport) {
      dom.btnExport.addEventListener("click", exportToCSV);
    }
  }

  async function exportToCSV() {
    console.log("📥 Exporting CSV...");
    const params = new URLSearchParams({
      search: state.search,
      filter: state.filter,
      page: 1,
      limit: 9999,
    });

    try {
      const response = await fetch(
        `../../api/admin_site/customers/fetch_customers.php?${params}`,
      );
      const data = await response.json();

      if (!data.success || !data.customers) {
        throw new Error("Failed to fetch data for export");
      }

      const rows = data.customers;
      const cols = [
        "Name",
        "Email",
        "Phone",
        "Total Orders",
        "Address",
        "Last Order",
        "Total Spent",
      ];
      const csv = [
        cols.join(","),
        ...rows.map((r) =>
          [
            `"${(r.name || "").replace(/"/g, '""')}"`,
            `"${(r.email || "").replace(/"/g, '""')}"`,
            `"${(r.phone || "").replace(/"/g, '""')}"`,
            r.total_orders || 0,
            `"${(r.address || "").replace(/"/g, '""')}"`,
            r.last_order || "",
            r.total_spent || 0,
          ].join(","),
        ),
      ].join("\n");

      const blob = new Blob(["\uFEFF" + csv], {
        type: "text/csv;charset=utf-8;",
      });
      const link = document.createElement("a");
      const url = URL.createObjectURL(blob);
      link.setAttribute("href", url);
      link.setAttribute(
        "download",
        `customers_${new Date().toISOString().slice(0, 10)}.csv`,
      );
      link.style.visibility = "hidden";
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
      URL.revokeObjectURL(url);
      console.log("✓ CSV exported successfully");
    } catch (error) {
      console.error("❌ Export error:", error);
      alert("Failed to export CSV: " + error.message);
    }
  }

  function init() {
    console.log("🚀 Initializing Customer Management App");

    if (!initDOMRefs()) {
      console.error("❌ Failed to initialize DOM references");
      return;
    }

    attachEventListeners();
    addClearSearchButton();
    console.log("✓ Event listeners attached");

    // Load customers
    fetchCustomers();
  }

  // Start the app
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }

  // Export for debugging
  window.customerApp = {
    fetchCustomers,
    getState: () => ({ ...state }),
  };
})();
