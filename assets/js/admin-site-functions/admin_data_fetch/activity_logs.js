/**
 * Activity Logs — Dynamic JS
 * Features: live search, user filter, action-type filter, status filter,
 *           dynamic dropdown population, CSV export, pagination, stats bar.
 */

const PAGE_SIZE = 10;
let allLogs = [];
let filtered = [];
let currentPage = 1;

// ─── Bootstrap ───────────────────────────────────────────────────────────────

document.addEventListener("DOMContentLoaded", () => {
  fetchLogs();
});

// ─── Fetch ────────────────────────────────────────────────────────────────────

function fetchLogs() {
  fetch("../../api/admin_site/fetch_activity_logs.php")
    .then((res) => res.json())
    .then((data) => {
      if (data.status === "success") {
        allLogs = data.data;
        filtered = data.data;
        updateStats(allLogs);
        populateDropdowns(allLogs);
        renderTable();
      } else {
        showError(data.message);
      }
    })
    .catch((err) => {
      console.error("Fetch Error:", err);
      showError("Could not load activity logs.");
    });
}

// ─── Stats Bar ────────────────────────────────────────────────────────────────

function updateStats(logs) {
  const total = logs.length;
  const success = logs.filter((l) => l.Status === "Success").length;
  const fail = total - success;
  const users = new Set(logs.map((l) => l.UserName)).size;

  document.getElementById("s-total").textContent = total;
  document.getElementById("s-success").textContent = success;
  document.getElementById("s-fail").textContent = fail;
  document.getElementById("s-users").textContent = users;
}

// ─── Dropdowns ───────────────────────────────────────────────────────────────

/**
 * Populates #userFilter and #actionFilter from live data.
 * Any new users or action types added to the DB automatically appear here.
 */
function populateDropdowns(logs) {
  // Users
  const users = [...new Set(logs.map((l) => l.UserName))].sort();
  const uf = document.getElementById("userFilter");
  // Clear all but the first "All Users" option
  while (uf.options.length > 1) uf.remove(1);
  users.forEach((u) => {
    const o = document.createElement("option");
    o.value = u;
    o.textContent = u;
    uf.appendChild(o);
  });

  // Action types — derived dynamically from ActionDetails text
  const actionTypes = [
    ...new Set(logs.map((l) => getActionLabel(l.ActionDetails))),
  ].sort();
  const af = document.getElementById("actionFilter");
  while (af.options.length > 1) af.remove(1);
  actionTypes.forEach((a) => {
    const o = document.createElement("option");
    o.value = a;
    o.textContent = a;
    af.appendChild(o);
  });
}

// ─── Filtering ────────────────────────────────────────────────────────────────

function applyFilters() {
  const query = document
    .getElementById("searchInput")
    .value.toLowerCase()
    .trim();
  const user = document.getElementById("userFilter").value;
  const action = document.getElementById("actionFilter").value;
  const status = document.getElementById("statusFilter").value;

  filtered = allLogs.filter((log) => {
    if (user && log.UserName !== user) return false;
    if (status && log.Status !== status) return false;
    if (action && getActionLabel(log.ActionDetails) !== action) return false;

    if (query) {
      const haystack = [
        log.UserName,
        log.ActionDetails,
        log.ReferenceID || "",
        log.Status,
      ]
        .join(" ")
        .toLowerCase();
      if (!haystack.includes(query)) return false;
    }

    return true;
  });

  currentPage = 1;
  renderTable();
}

// ─── Table Rendering ─────────────────────────────────────────────────────────

function renderTable() {
  const tbody = document.getElementById("tableBody");
  const total = filtered.length;
  const pages = Math.max(1, Math.ceil(total / PAGE_SIZE));
  const start = (currentPage - 1) * PAGE_SIZE;
  const slice = filtered.slice(start, start + PAGE_SIZE);

  document.getElementById("tableCount").innerHTML =
    `Showing <strong>${slice.length}</strong> of <strong>${total}</strong> events`;

  if (!slice.length) {
    tbody.innerHTML = `
      <tr>
        <td colspan="4">
          <div class="empty-state">
            <i class="fa-solid fa-magnifying-glass empty-icon"></i>
            <div class="empty-title">No logs found</div>
            <div class="empty-sub">Try adjusting your search or filters</div>
          </div>
        </td>
      </tr>`;
    renderPager(0, 1);
    return;
  }

  tbody.innerHTML = slice.map((log) => buildRow(log)).join("");
  renderPager(total, pages);
}

function buildRow(log) {
  const initials = getInitials(log.UserName);
  const avatarInner = log.ProfilePicture
    ? `<img src="../../${log.ProfilePicture}" alt="avatar" onerror="this.style.display='none'">`
    : initials;

  const statusClass = log.Status === "Success" ? "success" : "error";
  const type = getActionType(log.ActionDetails);
  const label = getActionLabel(log.ActionDetails);
  const actionHtml = log.ReferenceID
    ? log.ActionDetails.replace(
        log.ReferenceID,
        `<span class="action-ref">${log.ReferenceID}</span>`,
      )
    : log.ActionDetails;

  return `
    <tr>
      <td>
        <div class="user-cell">
          <div class="avatar">${avatarInner}</div>
          <div class="user-name">${escapeHtml(log.UserName)}</div>
        </div>
      </td>
      <td>
        <div class="action-cell">
          <span class="action-text">${actionHtml}</span>
          <span class="action-tag tag-${type}">${label}</span>
        </div>
      </td>
      <td>
        <span class="timestamp">${formatDate(log.CreatedAt)}</span>
      </td>
      <td>
        <span class="badge ${statusClass}">${escapeHtml(log.Status)}</span>
      </td>
    </tr>`;
}

// ─── Pagination ───────────────────────────────────────────────────────────────

function renderPager(total, pages) {
  const info = document.getElementById("pageInfo");
  const pager = document.getElementById("pager");

  if (!total) {
    info.textContent = "";
    pager.innerHTML = "";
    return;
  }

  const start = (currentPage - 1) * PAGE_SIZE + 1;
  const end = Math.min(currentPage * PAGE_SIZE, total);
  info.textContent = `${start}–${end} of ${total}`;

  let html = `<button class="page-btn" onclick="goPage(${currentPage - 1})" ${
    currentPage === 1 ? "disabled" : ""
  }>&#8249;</button>`;

  for (let i = 1; i <= pages; i++) {
    if (pages > 7 && i > 2 && i < pages - 1 && Math.abs(i - currentPage) > 1) {
      if (i === 3 || i === pages - 2)
        html += `<button class="page-btn" disabled>…</button>`;
      continue;
    }
    html += `<button class="page-btn ${
      i === currentPage ? "active" : ""
    }" onclick="goPage(${i})">${i}</button>`;
  }

  html += `<button class="page-btn" onclick="goPage(${currentPage + 1})" ${
    currentPage === pages ? "disabled" : ""
  }>&#8250;</button>`;

  pager.innerHTML = html;
}

function goPage(p) {
  const pages = Math.ceil(filtered.length / PAGE_SIZE);
  if (p < 1 || p > pages) return;
  currentPage = p;
  renderTable();
}

// ─── CSV Export ───────────────────────────────────────────────────────────────

function exportCSV() {
  const headers = ["User", "Action", "Reference", "Status", "Date"];
  const rows = filtered.map((l) => [
    `"${l.UserName}"`,
    `"${l.ActionDetails}"`,
    l.ReferenceID || "",
    l.Status,
    l.CreatedAt,
  ]);

  const csv = [headers, ...rows].map((r) => r.join(",")).join("\n");
  const a = document.createElement("a");
  a.href = "data:text/csv;charset=utf-8," + encodeURIComponent(csv);
  a.download = "activity_logs.csv";
  a.click();
}

// ─── Helpers ─────────────────────────────────────────────────────────────────

/**
 * Classify action verb from ActionDetails string.
 * Add new verbs here as your system grows.
 */
function getActionType(action) {
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
  return name
    .split(" ")
    .map((w) => w[0])
    .join("")
    .toUpperCase()
    .slice(0, 2);
}

function formatDate(dateString) {
  const date = new Date(dateString);
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
}

function escapeHtml(str) {
  return String(str)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;");
}

function showError(msg) {
  const tbody = document.getElementById("tableBody");
  if (tbody) {
    tbody.innerHTML = `
      <tr>
        <td colspan="4">
          <div class="empty-state">
            <i class="fa-solid fa-circle-exclamation empty-icon"></i>
            <div class="empty-title">Failed to load logs</div>
            <div class="empty-sub">${escapeHtml(msg)}</div>
          </div>
        </td>
      </tr>`;
  }
}
