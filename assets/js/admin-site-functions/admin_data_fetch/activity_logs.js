/**
 * Activity Logs - Simplified working version
 */

// ─── Double-load guard ────────────────────────────────────────────────────────
// If this script tag ever gets included twice on the same page (duplicate
// <script> in a shared header/include, browser cache weirdness, etc.), the
// second execution would normally throw "Identifier already declared" on the
// const/let below and silently kill the whole script - leaving the table
// stuck on "Loading..." forever with no visible error. This guard makes a
// second inclusion a harmless no-op instead.
if (window.__activityLogsLoaded) {
  console.warn("⚠️ activity_logs.js loaded more than once - skipping re-init");
} else {
  window.__activityLogsLoaded = true;

  (function () {
    const PAGE_SIZE = 10;
    let allLogs = [];
    let filtered = [];
    let currentPage = 1;

    // ─── Bootstrap ─────────────────────────────────────────────────────────────

    console.log("🚀 Activity Logs JS loaded");

    // Wait for DOM to be fully loaded
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", init);
    } else {
      init();
    }

    function init() {
      console.log("📋 Initializing Activity Logs");

      // Test if elements exist
      const tbody = document.getElementById("tableBody");
      const tableCount = document.getElementById("tableCount");

      if (!tbody) {
        console.error("❌ Table body element not found!");
        return;
      }

      console.log("✅ Table body found, fetching logs...");
      fetchLogs();
    }

    // ─── Fetch ────────────────────────────────────────────────────────────────────

    function fetchLogs() {
      console.log("📡 Fetching logs from API...");

      // Show loading state
      const tbody = document.getElementById("tableBody");
      if (tbody) {
        tbody.innerHTML = `
            <tr>
                <td colspan="4" style="text-align: center; padding: 40px;">
                    <div>
                        <i class="fa-solid fa-spinner fa-spin" style="font-size: 30px; color: #007bff;"></i>
                        <div style="margin-top: 10px; color: #666;">Loading logs...</div>
                    </div>
                </td>
            </tr>`;
      }

      // Direct fetch to API
      const apiUrl = "../../api/admin_site/fetch_activity_logs.php";
      console.log("🔗 API URL:", apiUrl);

      fetch(apiUrl)
        .then((response) => {
          console.log("📥 Response status:", response.status);
          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }
          return response.json();
        })
        .then((data) => {
          console.log("📦 Data received:", data);

          if (data.status === "success") {
            allLogs = data.data || [];
            filtered = [...allLogs];
            console.log(`✅ Loaded ${allLogs.length} logs`);

            if (allLogs.length === 0) {
              showEmptyState("No logs found in the database");
              return;
            }

            // Show stats
            showStats(allLogs);

            // Populate filters
            populateFilters(allLogs);

            // Render table
            renderTable();

            // Show stats row
            const statsRow = document.querySelector(".stats-row");
            if (statsRow) {
              statsRow.style.display = "flex";
            }
          } else {
            console.error("❌ API Error:", data.message);
            showError(data.message || "Failed to load logs");
          }
        })
        .catch((error) => {
          console.error("❌ Fetch error:", error);
          showError("Could not connect to server: " + error.message);
        });
    }

    // ─── Stats ──────────────────────────────────────────────────────────────────

    function showStats(logs) {
      const total = logs.length;
      const success = logs.filter((l) => l.Status === "Success").length;
      const fail = total - success;
      const users = new Set(logs.map((l) => l.UserName)).size;

      document.getElementById("s-total").textContent = total;
      document.getElementById("s-success").textContent = success;
      document.getElementById("s-fail").textContent = fail;
      document.getElementById("s-users").textContent = users;
    }

    // ─── Filters ─────────────────────────────────────────────────────────────────

    function populateFilters(logs) {
      // Users
      const users = [...new Set(logs.map((l) => l.UserName))]
        .filter(Boolean)
        .sort();
      const userFilter = document.getElementById("userFilter");
      if (userFilter) {
        userFilter.innerHTML = '<option value="">All Users</option>';
        users.forEach((u) => {
          const opt = document.createElement("option");
          opt.value = u;
          opt.textContent = u;
          userFilter.appendChild(opt);
        });
      }

      // Action types
      const actions = [
        ...new Set(logs.map((l) => getActionLabel(l.ActionDetails))),
      ]
        .filter(Boolean)
        .sort();
      const actionFilter = document.getElementById("actionFilter");
      if (actionFilter) {
        actionFilter.innerHTML = '<option value="">All Actions</option>';
        actions.forEach((a) => {
          const opt = document.createElement("option");
          opt.value = a;
          opt.textContent = a;
          actionFilter.appendChild(opt);
        });
      }
    }

    // ─── Filtering ────────────────────────────────────────────────────────────────

    function applyFilters() {
      const search = document
        .getElementById("searchInput")
        .value.toLowerCase()
        .trim();
      const user = document.getElementById("userFilter").value;
      const action = document.getElementById("actionFilter").value;
      const status = document.getElementById("statusFilter").value;

      filtered = allLogs.filter((log) => {
        if (user && log.UserName !== user) return false;
        if (status && log.Status !== status) return false;
        if (action && getActionLabel(log.ActionDetails) !== action)
          return false;

        if (search) {
          const text = [
            log.UserName,
            log.ActionDetails,
            log.ReferenceID,
            log.Status,
          ]
            .join(" ")
            .toLowerCase();
          if (!text.includes(search)) return false;
        }

        return true;
      });

      currentPage = 1;
      renderTable();
    }

    // ─── Table Rendering ─────────────────────────────────────────────────────────

    function renderTable() {
      const tbody = document.getElementById("tableBody");
      if (!tbody) return;

      const total = filtered.length;
      const pages = Math.max(1, Math.ceil(total / PAGE_SIZE));
      const start = (currentPage - 1) * PAGE_SIZE;
      const slice = filtered.slice(start, start + PAGE_SIZE);

      // Update count
      document.getElementById("tableCount").innerHTML =
        `Showing <strong>${slice.length}</strong> of <strong>${total}</strong> events`;

      if (slice.length === 0) {
        showEmptyState("No logs match your filters");
        renderPager(0, 1);
        return;
      }

      // Build table rows
      let html = "";
      slice.forEach((log) => {
        html += createRow(log);
      });
      tbody.innerHTML = html;

      renderPager(total, pages);
    }

    function createRow(log) {
      // Avatar
      const initials = getInitials(log.UserName || "U");
      const avatar = `<div class="avatar">${initials}</div>`;

      // Status class
      const statusClass = log.Status === "Success" ? "success" : "error";

      // Action type
      const type = getActionType(log.ActionDetails || "");
      const label = getActionLabel(log.ActionDetails || "");

      // Action details with reference
      let actionText = escapeHtml(log.ActionDetails || "");
      if (log.ReferenceID) {
        actionText = actionText.replace(
          log.ReferenceID,
          `<span class="action-ref">${escapeHtml(log.ReferenceID)}</span>`,
        );
      }

      return `
        <tr>
            <td>
                <div class="user-cell">
                    ${avatar}
                    <span class="user-name">${escapeHtml(log.UserName || "Unknown")}</span>
                </div>
            </td>
            <td>
                <div class="action-cell">
                    <span class="action-text">${actionText}</span>
                    <span class="action-tag tag-${type}">${escapeHtml(label)}</span>
                </div>
            </td>
            <td>
                <span class="timestamp">${formatDate(log.CreatedAt)}</span>
            </td>
            <td>
                <span class="badge ${statusClass}">${escapeHtml(log.Status || "Unknown")}</span>
            </td>
        </tr>
    `;
    }

    // ─── Pagination ───────────────────────────────────────────────────────────────

    function renderPager(total, pages) {
      const info = document.getElementById("pageInfo");
      const pager = document.getElementById("pager");

      if (total === 0) {
        if (info) info.textContent = "";
        if (pager) pager.innerHTML = "";
        return;
      }

      const start = (currentPage - 1) * PAGE_SIZE + 1;
      const end = Math.min(currentPage * PAGE_SIZE, total);
      if (info) info.textContent = `${start}–${end} of ${total}`;

      let html = `<button class="page-btn" onclick="goPage(${currentPage - 1})" ${currentPage === 1 ? "disabled" : ""}>&#8249;</button>`;

      for (let i = 1; i <= pages; i++) {
        if (
          pages > 7 &&
          i > 2 &&
          i < pages - 1 &&
          Math.abs(i - currentPage) > 1
        ) {
          if (i === 3 || i === pages - 2) {
            html += `<button class="page-btn" disabled>…</button>`;
          }
          continue;
        }
        html += `<button class="page-btn ${i === currentPage ? "active" : ""}" onclick="goPage(${i})">${i}</button>`;
      }

      html += `<button class="page-btn" onclick="goPage(${currentPage + 1})" ${currentPage === pages ? "disabled" : ""}>&#8250;</button>`;

      if (pager) pager.innerHTML = html;
    }

    function goPage(p) {
      const pages = Math.ceil(filtered.length / PAGE_SIZE);
      if (p < 1 || p > pages) return;
      currentPage = p;
      renderTable();
    }

    // ─── Export CSV ───────────────────────────────────────────────────────────────

    function exportCSV() {
      if (filtered.length === 0) {
        alert("No data to export");
        return;
      }

      const headers = ["User", "Action", "Reference", "Status", "Date"];
      const rows = filtered.map((l) => [
        `"${(l.UserName || "").replace(/"/g, '""')}"`,
        `"${(l.ActionDetails || "").replace(/"/g, '""')}"`,
        `"${(l.ReferenceID || "").replace(/"/g, '""')}"`,
        `"${(l.Status || "").replace(/"/g, '""')}"`,
        `"${(l.CreatedAt || "").replace(/"/g, '""')}"`,
      ]);

      const csv = [headers.join(","), ...rows.map((r) => r.join(","))].join(
        "\n",
      );
      const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
      const link = document.createElement("a");
      link.href = URL.createObjectURL(blob);
      link.download = `activity_logs_${new Date().toISOString().split("T")[0]}.csv`;
      link.click();
      URL.revokeObjectURL(link.href);
    }

    // ─── UI States ───────────────────────────────────────────────────────────────

    function showEmptyState(message) {
      const tbody = document.getElementById("tableBody");
      if (tbody) {
        tbody.innerHTML = `
            <tr>
                <td colspan="4">
                    <div class="empty-state">
                        <i class="fa-solid fa-inbox empty-icon"></i>
                        <div class="empty-title">${message || "No logs found"}</div>
                        <div class="empty-sub">Try adjusting your search or filters</div>
                    </div>
                </td>
            </tr>`;
      }
    }

    function showError(message) {
      const tbody = document.getElementById("tableBody");
      if (tbody) {
        tbody.innerHTML = `
            <tr>
                <td colspan="4">
                    <div class="empty-state error-state">
                        <i class="fa-solid fa-circle-exclamation empty-icon" style="color: #dc3545;"></i>
                        <div class="empty-title">Error Loading Logs</div>
                        <div class="empty-sub">${escapeHtml(message)}</div>
                        <button onclick="fetchLogs()" class="retry-btn" style="margin-top:15px;padding:8px 20px;background:#007bff;color:white;border:none;border-radius:4px;cursor:pointer;">
                            <i class="fa-solid fa-rotate"></i> Retry
                        </button>
                    </div>
                </td>
            </tr>`;
      }
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────────

    function getActionType(action) {
      if (!action) return "other";
      const a = action.toLowerCase();
      if (a.includes("login") || a.includes("logged in")) return "login";
      if (a.includes("update") || a.includes("updated")) return "update";
      if (a.includes("delete") || a.includes("deleted")) return "delete";
      if (a.includes("create") || a.includes("created")) return "create";
      if (a.includes("view") || a.includes("viewed")) return "view";
      if (a.includes("export") || a.includes("exported")) return "export";
      return "other";
    }

    function getActionLabel(action) {
      const map = {
        login: "Login",
        update: "Update",
        delete: "Delete",
        create: "Create",
        view: "View",
        export: "Export",
        other: "Other",
      };
      return map[getActionType(action)] || "Other";
    }

    function getInitials(name) {
      if (!name) return "U";
      return name
        .split(" ")
        .map((w) => w[0])
        .join("")
        .toUpperCase()
        .slice(0, 2);
    }

    function formatDate(dateString) {
      if (!dateString) return "N/A";
      try {
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return "Invalid Date";
        return date
          .toLocaleString("en-US", {
            month: "short",
            day: "2-digit",
            year: "numeric",
            hour: "2-digit",
            minute: "2-digit",
            hour12: true,
          })
          .replace(",", " •");
      } catch (e) {
        return dateString;
      }
    }

    function escapeHtml(str) {
      if (!str) return "";
      const div = document.createElement("div");
      div.textContent = str;
      return div.innerHTML;
    }

    // Make functions globally accessible
    window.applyFilters = applyFilters;
    window.exportCSV = exportCSV;
    window.goPage = goPage;
    window.fetchLogs = fetchLogs;
  })(); // end IIFE
} // end double-load guard
