document.addEventListener("DOMContentLoaded", function () {
  const adminListEl = document.getElementById("adminList");
  const pendingRequestsBtn = document.getElementById("pendingRequestsBtn");
  const pendingRequestsModal = document.getElementById("pendingRequestsModal");
  const pendingRequestsList = document.getElementById("pendingRequestsList");
  const pendingRequestsError = document.getElementById("pendingRequestsError");
  const pendingRequestsCount = document.getElementById("pendingRequestsCount");
  const pendingRequestsCountLabel = document.getElementById(
    "pendingRequestsCountLabel",
  );
  const pendingRequestsEmpty = document.getElementById("pendingRequestsEmpty");
  const toastAlert = document.getElementById("toastAlert");
  let currentAdminId = null;
  let previousRequestsJSON = "";
  // Auto-refresh configuration
  let pendingRequestsRefreshInterval = null;
  let previousPendingCount = 0;
  const AUTO_REFRESH_INTERVAL = 5000; // 5 seconds

  function formatDateTime(dateTimeString) {
    const date = new Date(dateTimeString);
    if (isNaN(date.getTime())) {
      return dateTimeString;
    }
    return date.toLocaleString();
  }

  // Detect new request from other page
  if (localStorage.getItem("newAdminRequest") === "true") {
    loadPendingCount(); // refresh immediately
    fetchPendingRequests(); // optional (if modal is open)

    showToast("New admin request submitted!", "info");

    localStorage.removeItem("newAdminRequest");
  }

  function showToast(message, type = "success") {
    if (!toastAlert) return;
    toastAlert.textContent = message;
    toastAlert.className = `toast-alert visible ${type}`;
    clearTimeout(showToast.timeout);
    showToast.timeout = setTimeout(() => {
      toastAlert.classList.remove("visible");
    }, 3800);
  }

  // Small HTML-escaping helper so user-supplied strings (FullName, Email,
  // username) never get interpreted as markup when inserted via innerHTML.
  function escapeHtml(str) {
    if (str === null || str === undefined) return "";
    const div = document.createElement("div");
    div.textContent = String(str);
    return div.innerHTML;
  }

  function renderAdminCard(admin) {
    const row = document.createElement("tr");
    row.className = "admin-table-row";
    const isSelf = currentAdminId !== null && admin.AdminID === currentAdminId;
    const accountStatus = admin.AccountStatus ? admin.AccountStatus : "Active";
    const roleOptions = ["Admin", "Finance", "Staff"]
      .map(
        (role) =>
          `<option value="${role}" ${admin.Role === role ? "selected" : ""}>${role}</option>`,
      )
      .join("");

    // admin.AdminID is placed in data-admin-id (a real DOM attribute read
    // back with .dataset), never interpolated into an onclick string. This
    // means a missing/undefined id shows up as the literal string
    // "undefined" in the DOM - easy to spot in devtools - instead of being
    // silently swallowed into executable JS that fails far away from here.
    row.dataset.adminId = admin.AdminID;

    row.innerHTML = `
      <td class="cell-name">
        <div class="admin-cell-content">
          <div class="avatar ${admin.Role === "Finance" ? "gold" : "gray"}">
            <i class="fa-solid fa-user-gear"></i>
          </div>
          <div class="name-info">
            <div class="admin-name">${escapeHtml(admin.FullName)}</div>
            <div class="admin-email">${escapeHtml(admin.Email)}</div>
          </div>
        </div>
      </td>
      <td class="cell-email">${escapeHtml(admin.Email)}</td>
      <td class="cell-role">
        <select class="role-select-table" data-prev="${admin.Role || "Admin"}" ${isSelf ? "disabled" : ""} data-action="change-role">
          ${roleOptions}
        </select>
      </td>
      <td class="cell-status">
        <span class="badge status ${accountStatus.toLowerCase() === "active" ? "active" : "inactive"}">${escapeHtml(accountStatus)}</span>
      </td>
      <td class="cell-actions">
        <div class="action-group">
          ${
            isSelf
              ? '<span class="self-label">Current</span>'
              : `
              <button class="admin-btn-sm
                ${accountStatus.toLowerCase() === "active" ? "deactivate" : "activate"}"
                type="button"
                data-action="toggle-status"
                data-current-status="${escapeHtml(accountStatus)}"
                title="${accountStatus.toLowerCase() === "active" ? "Deactivate" : "Activate"}">
                <i class="fa-solid ${accountStatus.toLowerCase() === "active" ? "fa-lock" : "fa-unlock"}"></i>
              </button>

              <button class="admin-btn-sm delete"
                type="button"
                data-action="delete"
                data-full-name="${escapeHtml(admin.FullName)}"
                data-email="${escapeHtml(admin.Email)}"
                title="Delete">
                <i class="fa-solid fa-trash"></i>
              </button>
            `
          }
        </div>
      </td>
    `;

    return row;
  }

  function showError(message) {
    adminListEl.innerHTML = `<div class="admin-list-error">${escapeHtml(message)}</div>`;
  }

  function showPendingError(message) {
    if (pendingRequestsError) {
      pendingRequestsError.textContent = message;
    }
  }

  function renderRequestRow(request) {
    // request.request_id is placed in a data-request-id attribute rather
    // than interpolated directly into an onclick="...(${...})" string. If
    // the API ever returns a row missing request_id, the buttons simply
    // won't fire (caught defensively in the click handler below) instead
    // of sending the literal text "undefined" to the server as request_id,
    // which is what previously produced "Invalid request details".
    return `
            <tr data-request-id="${escapeHtml(request.request_id)}">
                <td>${escapeHtml(request.username)}</td>
                <td>${escapeHtml(request.email)}</td>
                <td>${formatDateTime(request.submitted_at)}</td>
                <td class="request-actions">
                    <button class="btn-action accept" type="button" data-action="accept">Accept</button>
                    <button class="btn-action reject" type="button" data-action="reject">Reject</button>
                </td>
            </tr>
        `;
  }

  function loadAdminAccounts() {
    const adminTableBody = document.getElementById("adminTableBody");
    if (!adminTableBody) return;

    adminTableBody.innerHTML =
      '<tr class="loading-row"><td colspan="5"><div class="admin-list-loading">Loading admin accounts...</div></td></tr>';

    fetch("../../api/admin_site/fetch_admin_list.php", {
      credentials: "include",
      headers: {
        Accept: "application/json",
      },
    })
      .then((response) => response.json())
      .then((data) => {
        adminTableBody.innerHTML = "";

        if (data.status !== "success" || !Array.isArray(data.data)) {
          showError(data.message || "Unable to load admin list.");
          return;
        }

        currentAdminId = data.currentAdminId || null;

        if (data.data.length === 0) {
          adminTableBody.innerHTML =
            '<tr class="empty-row"><td colspan="5"><div class="admin-list-empty">No admin accounts found.</div></td></tr>';
          return;
        }

        data.data.forEach((admin) => {
          adminTableBody.appendChild(renderAdminCard(admin));
        });
      })
      .catch((error) => {
        showError("Failed to load admin list.");
        console.error("Admin list fetch error:", error);
      });
  }

  async function fetchPendingRequests() {
    if (
      !pendingRequestsModal ||
      !pendingRequestsList ||
      !pendingRequestsCount ||
      !pendingRequestsCountLabel ||
      !pendingRequestsEmpty
    )
      return;

    pendingRequestsError.textContent = "";

    try {
      const response = await fetch(
        "../../api/admin_site/fetch_pending_admins.php",
        {
          credentials: "include",
          headers: { Accept: "application/json" },
        },
      );
      const data = await response.json();

      if (data.status !== "success" || !Array.isArray(data.data)) {
        showPendingError(data.message || "Unable to load pending requests.");
        return;
      }

      // Update the badge
      pendingRequestsCount.textContent = data.data.length;
      pendingRequestsCountLabel.textContent = `${data.data.length} pending request${data.data.length === 1 ? "" : "s"}`;

      // Show empty message if no requests
      if (data.data.length === 0) {
        pendingRequestsList.innerHTML = "";
        pendingRequestsEmpty.classList.remove("hidden");
        previousRequestsJSON = ""; // reset previous data
        return;
      }

      pendingRequestsEmpty.classList.add("hidden");

      // Compare current data with previous data to avoid flicker
      const newDataJSON = JSON.stringify(data.data);
      if (newDataJSON !== previousRequestsJSON) {
        previousRequestsJSON = newDataJSON;

        // Only rebuild table if data changed
        pendingRequestsList.innerHTML = "";
        data.data.forEach((request) => {
          pendingRequestsList.insertAdjacentHTML(
            "beforeend",
            renderRequestRow(request),
          );
        });
      }
    } catch (error) {
      pendingRequestsList.innerHTML = "";
      showPendingError("Failed to load pending requests.");
      console.error("Pending requests fetch error:", error);
    }
  }

  async function processPendingRequest(requestId, action, buttonEl) {
    showPendingError("");

    // Defensive guard: if requestId somehow isn't a valid positive integer
    // (e.g. the row's data-request-id was empty/missing), fail loudly in
    // the UI instead of sending a bad value to the server.
    const idNum = Number(requestId);
    if (!Number.isInteger(idNum) || idNum <= 0) {
      showPendingError(
        `Cannot ${action} this request: missing or invalid request ID (${requestId}). Try refreshing the list.`,
      );
      console.error(
        "Invalid requestId passed to processPendingRequest:",
        requestId,
      );
      return;
    }

    if (buttonEl) buttonEl.disabled = true;

    try {
      const response = await fetch(
        "../../api/admin_site/process_pending_admin_request.php",
        {
          method: "POST",
          credentials: "include",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
            Accept: "application/json",
          },
          body: `request_id=${encodeURIComponent(idNum)}&action=${encodeURIComponent(action)}`,
        },
      );
      const data = await response.json();

      if (data.status !== "success") {
        showPendingError(data.message || "Unable to update request.");
        return;
      }

      showToast(data.message, "success");
      fetchPendingRequests();
      loadAdminAccounts();
    } catch (error) {
      showPendingError("Unable to update the request.");
      console.error("Pending action error:", error);
    } finally {
      if (buttonEl) buttonEl.disabled = false;
    }
  }

  async function processAdminUser(
    adminId,
    action,
    payload = {},
    element = null,
  ) {
    const idNum = Number(adminId);
    if (!Number.isInteger(idNum) || idNum <= 0) {
      showToast(
        `Cannot perform action: missing or invalid admin ID (${adminId}).`,
        "error",
      );
      console.error("Invalid adminId passed to processAdminUser:", adminId);
      return;
    }

    try {
      const formData = new URLSearchParams({
        admin_id: idNum,
        action,
        ...payload,
      });
      const response = await fetch(
        "../../api/admin_site/process_admin_user.php",
        {
          method: "POST",
          credentials: "include",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
            Accept: "application/json",
          },
          body: formData.toString(),
        },
      );
      const data = await response.json();

      if (data.status !== "success") {
        if (element && element.dataset.prev) {
          element.value = element.dataset.prev;
        }
        showToast(data.message || "Unable to update user.", "error");
        return;
      }

      if (element && payload.role) {
        element.dataset.prev = payload.role;
      }

      showToast(data.message, "success");
      loadAdminAccounts();
    } catch (error) {
      if (element && element.dataset.prev) {
        element.value = element.dataset.prev;
      }
      showToast("Unable to update user. Please try again.", "error");
      console.error("Admin user action error:", error);
    }
  }

  let adminToDelete = null;

  function openDeleteModal(adminId, fullName, email) {
    adminToDelete = adminId;

    const modal = document.getElementById("deleteConfirmModal");
    const message = document.getElementById("deleteMessage");

    message.innerHTML = `
    You are about to delete:<br><br>
    <strong>${escapeHtml(fullName)}</strong><br>
    <span class="email">${escapeHtml(email)}</span><br><br>
    This admin will lose access permanently.
  `;

    modal.classList.add("show");
  }

  window.closeDeleteModal = () => {
    adminToDelete = null;
    document.getElementById("deleteConfirmModal").classList.remove("show");
  };

  document
    .getElementById("confirmDeleteBtn")
    .addEventListener("click", async function () {
      if (adminToDelete === null) return;

      const btn = this;
      const text = btn.querySelector(".btn-text");
      const loader = btn.querySelector(".btn-loading");

      // 🔄 loading state
      btn.disabled = true;
      text.textContent = "Deleting...";
      loader.classList.remove("hidden");

      await processAdminUser(adminToDelete, "delete");

      // reset
      btn.disabled = false;
      text.textContent = "Delete";
      loader.classList.add("hidden");

      closeDeleteModal();
    });

  // ─── Event delegation for the admin accounts table ──────────────────────
  // Replaces the previous inline onclick="...(${value})" approach. Clicks
  // and changes are read from real DOM attributes (data-admin-id, etc.) at
  // the moment of interaction, so there's no window where a bad/missing
  // value gets baked into an HTML string and silently breaks later.
  if (adminListEl) {
    adminListEl.addEventListener("click", (e) => {
      const btn = e.target.closest("button[data-action]");
      if (!btn) return;

      const row = btn.closest("tr[data-admin-id]");
      if (!row) return;
      const adminId = row.dataset.adminId;

      if (btn.dataset.action === "toggle-status") {
        const currentStatus = btn.dataset.currentStatus || "Active";
        const nextStatus =
          currentStatus.toLowerCase() === "active" ? "Disabled" : "Active";
        processAdminUser(adminId, "set_status", { status: nextStatus });
      } else if (btn.dataset.action === "delete") {
        openDeleteModal(adminId, btn.dataset.fullName, btn.dataset.email);
      }
    });

    adminListEl.addEventListener("change", (e) => {
      const select = e.target.closest('select[data-action="change-role"]');
      if (!select) return;

      const row = select.closest("tr[data-admin-id]");
      if (!row) return;
      const adminId = row.dataset.adminId;

      processAdminUser(adminId, "change_role", { role: select.value }, select);
    });
  }

  // ─── Event delegation for the pending requests modal ────────────────────
  if (pendingRequestsList) {
    pendingRequestsList.addEventListener("click", (e) => {
      const btn = e.target.closest("button[data-action]");
      if (!btn) return;

      const row = btn.closest("tr[data-request-id]");
      if (!row) return;
      const requestId = row.dataset.requestId;

      if (btn.dataset.action === "accept") {
        processPendingRequest(requestId, "accept", btn);
      } else if (btn.dataset.action === "reject") {
        processPendingRequest(requestId, "reject", btn);
      }
    });
  }

  window.closePendingRequestsModal = () => {
    if (pendingRequestsModal) {
      pendingRequestsModal.classList.remove("show");
    }
  };

  async function loadPendingCount() {
    if (!pendingRequestsCount || !pendingRequestsCountLabel) return;

    try {
      const response = await fetch(
        "../../api/admin_site/fetch_pending_admins.php",
        {
          credentials: "include",
          headers: {
            Accept: "application/json",
          },
        },
      );
      const data = await response.json();
      if (data.status === "success" && Array.isArray(data.data)) {
        const currentCount = data.data.length;
        pendingRequestsCount.textContent = currentCount;
        pendingRequestsCountLabel.textContent = `${currentCount} pending request${currentCount === 1 ? "" : "s"}`;

        // Check if new requests arrived and show notification
        if (previousPendingCount < currentCount) {
          const newRequestsCount = currentCount - previousPendingCount;
          showToast(
            `${newRequestsCount} new admin request${newRequestsCount === 1 ? "" : "s"} arrived!`,
            "info",
          );
          addNewRequestIndicator();
        }
        previousPendingCount = currentCount;
      }
    } catch (error) {
      console.error("Pending count load error:", error);
    }
  }

  function addNewRequestIndicator() {
    if (!pendingRequestsCount) return;
    pendingRequestsCount.classList.add("new-request-pulse");
    setTimeout(() => {
      pendingRequestsCount.classList.remove("new-request-pulse");
    }, 6000);
  }

  function startAutoRefreshPendingRequests() {
    // Clear existing interval if any
    if (pendingRequestsRefreshInterval) {
      clearInterval(pendingRequestsRefreshInterval);
    }

    // Immediately fetch when page loads
    loadPendingCount();

    // Then set up auto-refresh at regular intervals
    // Only update the count, NOT the full request list (to avoid flickering)
    pendingRequestsRefreshInterval = setInterval(() => {
      loadPendingCount();

      // If modal is open → also refresh list
      if (pendingRequestsModal.classList.contains("show")) {
        fetchPendingRequests();
      }
    }, AUTO_REFRESH_INTERVAL);
  }

  function stopAutoRefreshPendingRequests() {
    if (pendingRequestsRefreshInterval) {
      clearInterval(pendingRequestsRefreshInterval);
      pendingRequestsRefreshInterval = null;
    }
  }

  if (pendingRequestsBtn && pendingRequestsModal) {
    pendingRequestsBtn.addEventListener("click", () => {
      pendingRequestsModal.classList.add("show");
      fetchPendingRequests();
    });
  }

  // Close modal event listener
  const closeModalBtn = document.querySelector(
    '.modal-close[onclick="closePendingRequestsModal()"]',
  );
  if (closeModalBtn) {
    closeModalBtn.addEventListener("click", () => {
      stopAutoRefreshPendingRequests();
    });
  }

  // Also stop refresh when clicking outside modal
  if (pendingRequestsModal) {
    pendingRequestsModal.addEventListener("click", (e) => {
      if (e.target === pendingRequestsModal) {
        stopAutoRefreshPendingRequests();
      }
    });
  }

  loadAdminAccounts();
  // Start auto-refresh immediately
  startAutoRefreshPendingRequests();
});
