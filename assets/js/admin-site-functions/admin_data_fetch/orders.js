/**
 * Orders Management - Improved Version (FIXED)
 * Handles data fetching, filtering, and modal population
 */

document.addEventListener("DOMContentLoaded", function () {
  // Initialize state
  window.currentOrders = [];
  window.currentOrderData = null;
  window.currentOrderNumber = null;

  // Initial load
  fetchOrders();

  // Poll for updates every 3 seconds (more frequent for live showing)
  setInterval(() => fetchOrders(true), 3000);

  // Modal close handlers
  setupModalHandlers();

  // Filter handlers
  setupFilterHandlers();

  // Setup tab switching
  setupTabHandlers();
});

/* ═════════════════════════════════════════════════════════════ */
/* TAB HANDLERS */
/* ═════════════════════════════════════════════════════════════ */

function setupTabHandlers() {
  // Tab buttons already have onclick handlers, but we need to load pending payments
  window.showTab = function (tabId, btn) {
    // Hide all tabs
    document
      .querySelectorAll(".tab-content")
      .forEach((tab) => tab.classList.remove("active"));
    document
      .querySelectorAll(".tab-btn")
      .forEach((b) => b.classList.remove("active"));

    // Show selected tab
    document.getElementById(tabId).classList.add("active");
    btn.classList.add("active");

    // Load pending payments when payments tab is shown
    if (tabId === "paymentsTab") {
      loadPendingPayments();
    }
  };
}

/* ═════════════════════════════════════════════════════════════ */
/* MODAL HANDLERS */
/* ═════════════════════════════════════════════════════════════ */

function setupModalHandlers() {
  // Close on X button
  const closeBtn = document.querySelector(".modal-close");
  if (closeBtn) {
    closeBtn.addEventListener("click", closeOrderModal);
  }

  // Close on overlay click
  const modal = document.getElementById("OrderItemModal");
  if (modal) {
    modal.addEventListener("click", function (e) {
      if (e.target === this) {
        closeOrderModal();
      }
    });
  }

  // Close on footer button
  const footerBtn = document.querySelector(".modal-footer .btn-secondary");
  if (footerBtn) {
    footerBtn.addEventListener("click", closeOrderModal);
  }

  // Keyboard close
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
      closeOrderModal();
    }
  });
}

function openOrderModal(orderId) {
  fetch(
    `../../api/admin_site/order_processes/fetch_order_details.php?order_id=${orderId}`,
  )
    .then((res) => res.json())
    .then((data) => {
      if (!data.success) {
        showToast("Error loading order details", true);
        return;
      }

      window.currentOrderData = data.order;
      window.currentOrderNumber = orderId;

      populateModal(data.order, data.items || []);
      document.getElementById("OrderItemModal").classList.add("active");
    })
    .catch((err) => {
      console.error("Error:", err);
      showToast("Error loading order details", true);
    });
}

function closeOrderModal() {
  const modal = document.getElementById("OrderItemModal");
  if (modal) {
    modal.classList.remove("active");
  }
  window.currentOrderData = null;
  window.currentOrderNumber = null;
}

function populateModal(order, items) {
  // Header
  document.getElementById("modalOrderId").textContent =
    `Order #${order.order_number}`;

  // Left Column - Order Information
  document.getElementById("modalOrderNumber").textContent = order.order_number;
  document.getElementById("modalCustomerName").textContent =
    order.customer_name || "N/A";
  document.getElementById("modalCustomerEmail").textContent =
    order.customer_email || "N/A";
  document.getElementById("modalCustomerPhone").textContent =
    order.customer_phone || "N/A";
  document.getElementById("modalOrderDate").textContent = formatDate(
    order.created_at,
  );

  // Items Table
  const itemsBody = document.getElementById("modalItemsBody");
  itemsBody.innerHTML = "";

  if (items && items.length > 0) {
    items.forEach((item) => {
      const row = document.createElement("tr");
      const price = parseFloat(item.price) || 0;
      const qty = parseInt(item.quantity) || 0;
      const subtotal = price * qty;

      row.innerHTML = `
                <td>${item.product_name || "N/A"}</td>
                <td style="text-align: center;">${qty}</td>
                <td style="text-align: right;">₱${price.toFixed(2)}</td>
                <td style="text-align: right;">₱${subtotal.toFixed(2)}</td>
            `;
      itemsBody.appendChild(row);
    });
  } else {
    itemsBody.innerHTML =
      '<tr><td colspan="4" style="text-align: center; color: var(--text-secondary);">No items</td></tr>';
  }

  // Total Amount
  const totalAmount = parseFloat(order.total_amount) || 0;
  document.getElementById("modalTotalAmount").textContent =
    `₱${totalAmount.toFixed(2)}`;

  // Right Column - Payment Information
  document.getElementById("modalPaymentMethod").innerHTML =
    getPaymentMethodBadge(order.payment_method);
  document.getElementById("modalPaymentStatus").innerHTML =
    getPaymentStatusBadge(order.payment_status);
  document.getElementById("modalOrderStatus").innerHTML = getOrderStatusBadge(
    order.order_status,
  );

  // Reference number
  const refRow = document.getElementById("refRow");
  if (order.resolved_reference) {
    document.getElementById("modalReference").textContent =
      order.resolved_reference;
    refRow.style.display = "flex";
  } else {
    refRow.style.display = "none";
  }

  // Proof of Payment
  const proofCard = document.getElementById("proofCard");
  const proofContent = document.getElementById("proofContent");

  if (order.payment_method === "gcash") {
    proofCard.style.display = "block";

    if (order.resolved_proof) {
      proofContent.innerHTML = `
                <a href="${order.resolved_proof}" target="_blank" class="proof-link">
                    <img 
                        src="${order.resolved_proof}" 
                        alt="Payment Proof" 
                        class="proof-image"
                        onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22150%22%3E%3Crect fill=%22%23f0f0f0%22 width=%22200%22 height=%22150%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 font-family=%22Arial%22 font-size=%2214%22 fill=%22%23999%22 text-anchor=%22middle%22 dominant-baseline=%22middle%22%3EImage unavailable%3C/text%3E%3C/svg%3E'"
                    >
                </a>
            `;
    } else {
      proofContent.innerHTML = `
                <div class="proof-missing">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>No proof uploaded yet</span>
                </div>
            `;
    }
  } else {
    proofCard.style.display = "none";
  }

  // Action Buttons
  const actionCard = document.getElementById("actionCard");
  if (
    order.payment_status === "pending" &&
    order.payment_method === "gcash" &&
    order.resolved_proof
  ) {
    actionCard.style.display = "block";
  } else {
    actionCard.style.display = "none";
  }
}

/* ═════════════════════════════════════════════════════════════ */
/* FETCH & RENDER ORDERS */
/* ═════════════════════════════════════════════════════════════ */

function fetchOrders(isPolling = false) {
  fetch("../../api/admin_site/order_processes/fetch_orders.php")
    .then((res) => res.json())
    .then((response) => {
      if (response.status === "success") {
        if (isPolling) {
          updateTableLive(response.data);
        } else {
          window.currentOrders = response.data;
          applyFilters();
          updateOrderCount();
        }
      }
    })
    .catch((err) => console.error("Error fetching orders:", err));
}

function updateOrderCount() {
  const filteredOrders = getFilteredOrders();
  document.getElementById("totalOrdersCount").textContent =
    filteredOrders.length;
}

function getFilteredOrders() {
  const statusFilter = document.getElementById("statusFilter")?.value || "";
  const paymentFilter = document.getElementById("paymentFilter")?.value || "";
  const methodFilter = document.getElementById("methodFilter")?.value || "";
  const searchTerm = (document.getElementById("orderSearch")?.value || "")
    .toLowerCase()
    .trim();

  return window.currentOrders.filter((order) => {
    const matchesStatus = !statusFilter || order.order_status === statusFilter;
    const matchesPayment =
      !paymentFilter || order.payment_status === paymentFilter;
    const matchesMethod =
      !methodFilter || order.payment_method === methodFilter;
    const matchesSearch =
      !searchTerm ||
      (order.order_number || "").toLowerCase().includes(searchTerm) ||
      (order.customer_name || "").toLowerCase().includes(searchTerm);

    return matchesStatus && matchesPayment && matchesMethod && matchesSearch;
  });
}

function applyFilters() {
  const filteredOrders = getFilteredOrders();
  renderOrders(filteredOrders);
  updateOrderCount();
}

function renderOrders(orders) {
  const tbody = document.getElementById("ordersTableBody");
  tbody.innerHTML = "";

  if (orders.length === 0) {
    tbody.innerHTML = `
            <tr class="loading-row">
                <td colspan="8" style="text-align: center; padding: 40px;">
                    <p style="color: var(--text-secondary); margin: 0;">No orders found</p>
                </td>
            </tr>
        `;
    return;
  }

  orders.forEach((order) => {
    const row = createTableRow(order);
    tbody.appendChild(row);
  });
}

function createTableRow(order) {
  const tr = document.createElement("tr");
  tr.dataset.orderId = order.order_number;

  tr.innerHTML = `
        <td><strong>${order.order_number}</strong></td>
        <td>${order.customer_name || "N/A"}</td>
        <td>${formatDate(order.created_at)}</td>
        <td><strong>₱${(parseFloat(order.total_amount) || 0).toFixed(2)}</strong></td>
        <td>${getPaymentMethodBadge(order.payment_method)}</td>
        <td><select class="payment-dropdown" data-order-id="${order.order_number}">
            <option value="paid" ${order.payment_status === "paid" ? "selected" : ""}>Paid</option>
            <option value="pending" ${order.payment_status === "pending" ? "selected" : ""}>Pending</option>
            <option value="failed" ${order.payment_status === "failed" ? "selected" : ""}>Failed</option>
        </select></td>
        <td><select class="status-dropdown" data-order-id="${order.order_number}">
            <option value="pending" ${order.order_status === "pending" ? "selected" : ""}>Pending</option>
            <option value="processing" ${order.order_status === "processing" ? "selected" : ""}>Processing</option>
            <option value="packed" ${order.order_status === "packed" ? "selected" : ""}>Packed</option>
            <option value="shipped" ${order.order_status === "shipped" ? "selected" : ""}>Shipped</option>
            <option value="delivered" ${order.order_status === "delivered" ? "selected" : ""}>Delivered</option>
            <option value="cancelled" ${order.order_status === "cancelled" ? "selected" : ""}>Cancelled</option>
        </select></td>
        <td>
            <button class="action-icon" title="View Order" onclick="openOrderModal('${order.order_number}')">
                <i class="fa-regular fa-eye"></i>
            </button>
        </td>
    `;

  // Add event listeners
  const statusDropdown = tr.querySelector(".status-dropdown");
  const paymentDropdown = tr.querySelector(".payment-dropdown");

  if (statusDropdown) {
    statusDropdown.addEventListener("change", (e) => {
      updateOrderStatus(order.order_number, e.target.value);
    });
  }

  if (paymentDropdown) {
    paymentDropdown.addEventListener("change", (e) => {
      updatePaymentStatus(order.order_number, e.target.value);
    });
  }

  return tr;
}

/* ═════════════════════════════════════════════════════════════ */
/* UPDATE HANDLERS */
/* ═════════════════════════════════════════════════════════════ */

function updateOrderStatus(orderNumber, newStatus) {
  fetch("../../api/admin_site/order_processes/update_order_status.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      order_number: orderNumber,
      order_status: newStatus,
    }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        showToast("Order status updated successfully");
        fetchOrders();
      } else {
        showToast(data.message || "Failed to update status", true);
        fetchOrders(); // Refresh to restore previous value
      }
    })
    .catch((err) => {
      console.error("Error:", err);
      showToast("Error updating status", true);
      fetchOrders(); // Refresh to restore previous value
    });
}

function updatePaymentStatus(orderNumber, newStatus) {
  fetch("../../api/admin_site/order_processes/update_order_status.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      order_number: orderNumber,
      payment_status: newStatus,
    }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        showToast("Payment status updated successfully");
        fetchOrders();
      } else {
        showToast(data.message || "Failed to update payment status", true);
        fetchOrders(); // Refresh to restore previous value
      }
    })
    .catch((err) => {
      console.error("Error:", err);
      showToast("Error updating payment status", true);
      fetchOrders(); // Refresh to restore previous value
    });
}

function updateTableLive(newOrders) {
  const tbody = document.getElementById("ordersTableBody");
  const existingOrderIds = new Set();

  // Get existing order IDs
  tbody.querySelectorAll("tr").forEach((tr) => {
    const orderId = tr.dataset.orderId;
    if (orderId) {
      existingOrderIds.add(orderId);
    }
  });

  // Check for new orders (not in existing)
  let hasNewOrders = false;
  newOrders.forEach((order) => {
    if (!existingOrderIds.has(order.order_number)) {
      hasNewOrders = true;
    }
  });

  // If new orders, reapply filters to show them
  if (hasNewOrders) {
    window.currentOrders = newOrders;
    applyFilters();
    return;
  }

  // Otherwise, just update existing rows
  newOrders.forEach((newOrder) => {
    const existingRow = tbody.querySelector(
      `tr[data-order-id="${newOrder.order_number}"]`,
    );
    if (existingRow) {
      const statusDropdown = existingRow.querySelector(".status-dropdown");
      const paymentDropdown = existingRow.querySelector(".payment-dropdown");

      if (statusDropdown && statusDropdown.value !== newOrder.order_status) {
        statusDropdown.value = newOrder.order_status;
      }

      if (
        paymentDropdown &&
        paymentDropdown.value !== newOrder.payment_status
      ) {
        paymentDropdown.value = newOrder.payment_status;
      }
    }
  });

  window.currentOrders = newOrders;
}

/* ═════════════════════════════════════════════════════════════ */
/* PAYMENT ACTIONS */
/* ═════════════════════════════════════════════════════════════ */

function approvePayment() {
  if (!window.currentOrderNumber) return;
  if (!confirm("Approve this payment?")) return;

  fetch("../../api/admin_site/order_processes/approve_payment.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ order_number: window.currentOrderNumber }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        showToast("Payment approved successfully");
        fetchOrders();
        loadPendingPayments();
        setTimeout(() => closeOrderModal(), 1000);
      } else {
        showToast(data.message || "Failed to approve payment", true);
      }
    })
    .catch((err) => {
      console.error("Error:", err);
      showToast("Error approving payment", true);
    });
}

function rejectPayment() {
  if (!window.currentOrderNumber) return;
  if (!confirm("Reject this payment?")) return;

  fetch("../../api/admin_site/order_processes/reject_payment.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ order_number: window.currentOrderNumber }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        showToast("Payment rejected");
        fetchOrders();
        loadPendingPayments();
        setTimeout(() => closeOrderModal(), 1000);
      } else {
        showToast(data.message || "Failed to reject payment", true);
      }
    })
    .catch((err) => {
      console.error("Error:", err);
      showToast("Error rejecting payment", true);
    });
}

/* ═════════════════════════════════════════════════════════════ */
/* FILTER HANDLERS */
/* ═════════════════════════════════════════════════════════════ */

function setupFilterHandlers() {
  const searchInput = document.getElementById("orderSearch");
  const statusFilter = document.getElementById("statusFilter");
  const paymentFilter = document.getElementById("paymentFilter");
  const methodFilter = document.getElementById("methodFilter");

  if (searchInput) searchInput.addEventListener("input", applyFilters);
  if (statusFilter) statusFilter.addEventListener("change", applyFilters);
  if (paymentFilter) paymentFilter.addEventListener("change", applyFilters);
  if (methodFilter) methodFilter.addEventListener("change", applyFilters);
}

/* ═════════════════════════════════════════════════════════════ */
/* PENDING PAYMENTS TAB */
/* ═════════════════════════════════════════════════════════════ */

function loadPendingPayments() {
  const container = document.getElementById("paymentsList");

  if (!container) return; // Exit if element doesn't exist

  container.innerHTML =
    '<div style="padding:20px;text-align:center;"><i class="fa-solid fa-spinner" style="animation: spin 1s linear infinite; font-size: 24px;"></i><p style="margin-top: 10px; color: var(--text-secondary);">Loading...</p></div>';

  fetch("../../api/admin_site/order_processes/fetch_orders.php")
    .then((res) => res.json())
    .then((response) => {
      if (response.status === "success") {
        const pending = response.data.filter(
          (o) => o.payment_status === "pending" && o.payment_method === "gcash",
        );
        displayPendingPayments(pending);
      } else {
        container.innerHTML =
          '<div class="empty-state"><p>Failed to load payments</p></div>';
      }
    })
    .catch((err) => {
      console.error("Error:", err);
      container.innerHTML =
        '<div class="empty-state"><p>Failed to load payments</p></div>';
    });
}

function displayPendingPayments(payments) {
  const container = document.getElementById("paymentsList");

  if (!container) return;

  if (payments.length === 0) {
    container.innerHTML = `
            <div class="empty-state">
                <i class="fa-solid fa-inbox"></i>
                <p>No pending GCash payments</p>
            </div>
        `;
    return;
  }

  container.innerHTML = "";
  let loadedCount = 0;

  payments.forEach((order) => {
    fetch(
      `../../api/admin_site/order_processes/fetch_order_details.php?order_id=${order.order_number}`,
    )
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          const card = createPaymentCard(data.order);
          container.appendChild(card);
        }
        loadedCount++;
      })
      .catch((err) => {
        console.error("Error fetching payment details:", err);
        loadedCount++;
      });
  });
}

function createPaymentCard(order) {
  const card = document.createElement("div");
  card.className = "payment-card";

  let proofHTML = "";
  if (order.resolved_proof) {
    proofHTML = `
            <div class="proof-section">
                <label class="proof-label">Payment Proof:</label>
                <a href="${order.resolved_proof}" target="_blank">
                    <img src="${order.resolved_proof}" alt="Proof" class="proof-thumbnail">
                </a>
            </div>
        `;
  } else {
    proofHTML = `
            <div class="proof-section">
                <div class="proof-missing">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>No proof uploaded yet</span>
                </div>
            </div>
        `;
  }

  const refHTML = order.resolved_reference
    ? `<div class="payment-card-field">
            <label>Reference #:</label>
            <span>${order.resolved_reference}</span>
        </div>`
    : "";

  const canApprove = !!order.resolved_proof;

  card.innerHTML = `
        <div class="payment-card-content">
            <div class="payment-order-header">
                <span class="payment-order-id">${order.order_number}</span>
                <span class="badge pending">Pending</span>
            </div>

            <div class="payment-card-info">
                <div class="payment-card-field">
                    <label>Customer:</label>
                    <span>${order.customer_name || "N/A"}</span>
                </div>
                <div class="payment-card-field">
                    <label>Method:</label>
                    <span>GCash</span>
                </div>
                <div class="payment-card-field">
                    <label>Amount:</label>
                    <span style="color: var(--primary-blue); font-weight: 600;">₱${(parseFloat(order.total_amount) || 0).toFixed(2)}</span>
                </div>
                ${refHTML}
            </div>

            ${proofHTML}
        </div>

        <div class="payment-card-actions">
            <button class="btn-approve" ${canApprove ? "" : "disabled"} title="${canApprove ? "Approve" : "Waiting for proof upload"}"
                onclick="approvePaymentFromCard('${order.order_number}')">
                <i class="fa-solid fa-check"></i>
                Approve
            </button>
            <button class="btn-reject" onclick="rejectPaymentFromCard('${order.order_number}')">
                <i class="fa-solid fa-times"></i>
                Reject
            </button>
        </div>
    `;

  return card;
}

function approvePaymentFromCard(orderNumber) {
  if (!confirm("Approve this payment?")) return;

  fetch("../../api/admin_site/order_processes/approve_payment.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ order_number: orderNumber }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        showToast("Payment approved successfully");
        loadPendingPayments();
        fetchOrders();
      } else {
        showToast(data.message || "Failed to approve payment", true);
      }
    })
    .catch((err) => {
      console.error("Error:", err);
      showToast("Error approving payment", true);
    });
}

function rejectPaymentFromCard(orderNumber) {
  if (!confirm("Reject this payment?")) return;

  fetch("../../api/admin_site/order_processes/reject_payment.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ order_number: orderNumber }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        showToast("Payment rejected");
        loadPendingPayments();
        fetchOrders();
      } else {
        showToast(data.message || "Failed to reject payment", true);
      }
    })
    .catch((err) => {
      console.error("Error:", err);
      showToast("Error rejecting payment", true);
    });
}

/* ═════════════════════════════════════════════════════════════ */
/* BADGE & STATUS FUNCTIONS */
/* ═════════════════════════════════════════════════════════════ */

function getPaymentMethodBadge(method) {
  const badges = {
    gcash:
      '<span class="badge" style="background: #e8f1f8; color: #1f4e79;">GCash</span>',
    cash: '<span class="badge" style="background: #f0f0f0; color: #666;">COD</span>',
  };
  return badges[method] || `<span class="badge">${method || "N/A"}</span>`;
}

function getPaymentStatusBadge(status) {
  const badges = {
    paid: '<span class="badge paid">Paid</span>',
    pending: '<span class="badge pending">Pending</span>',
    failed: '<span class="badge cancelled">Failed</span>',
  };
  return badges[status] || `<span class="badge">${status || "N/A"}</span>`;
}

function getOrderStatusBadge(status) {
  const badges = {
    pending: '<span class="badge pending">Pending</span>',
    processing: '<span class="badge processing">Processing</span>',
    packed: '<span class="badge packed">Packed</span>',
    shipped: '<span class="badge shipped">Shipped</span>',
    delivered: '<span class="badge delivered">Delivered</span>',
    cancelled: '<span class="badge cancelled">Cancelled</span>',
  };
  return badges[status] || `<span class="badge">${status || "N/A"}</span>`;
}

/* ═════════════════════════════════════════════════════════════ */
/* UTILITY FUNCTIONS */
/* ═════════════════════════════════════════════════════════════ */

function formatDate(dateString) {
  if (!dateString) return "N/A";
  try {
    return new Date(dateString).toISOString().split("T")[0];
  } catch {
    return dateString;
  }
}

function showToast(message, isError = false) {
  const container = document.getElementById("toast-container");
  if (!container) return;

  const toast = document.createElement("div");
  toast.className = `toast ${isError ? "error" : "success"}`;
  toast.textContent = message;

  container.appendChild(toast);

  // Trigger animation
  setTimeout(() => {
    toast.classList.add("show");
  }, 10);

  // Remove after 3 seconds
  setTimeout(() => {
    toast.classList.remove("show");
    setTimeout(() => toast.remove(), 300);
  }, 3000);
}
