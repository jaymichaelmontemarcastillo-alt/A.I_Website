document.addEventListener("DOMContentLoaded", function () {
  window.currentOrders = [];
  window.currentOrderData = null;

  fetchOrders();
  setInterval(() => fetchOrders(true), 5000);

  document
    .querySelector(".close-modal")
    .addEventListener("click", closeOrderModal);
  document
    .getElementById("closeOrderBtn")
    .addEventListener("click", closeOrderModal);

  window.addEventListener("click", function (e) {
    if (e.target === document.getElementById("OrderItemModal")) {
      closeOrderModal();
    }
  });

  document.getElementById("orderSearch").addEventListener("input", function () {
    const term = this.value.toLowerCase().trim();
    const filtered = window.currentOrders.filter(
      (o) =>
        (o.order_number || "").toLowerCase().includes(term) ||
        (o.customer_name || "").toLowerCase().includes(term),
    );
    renderOrders(filtered);
  });
});

// ================= FETCH ORDERS =================
function fetchOrders(isPolling = false) {
  fetch("../../api/admin_site/order_processes/fetch_orders.php")
    .then((res) => res.json())
    .then((response) => {
      if (response.status === "success") {
        isPolling
          ? updateTableLive(response.data)
          : renderOrders(response.data);
      }
    })
    .catch((err) => console.error("Error fetching orders:", err));
}

// ================= RENDER ORDERS =================
function renderOrders(orders) {
  const tbody = document.getElementById("ordersTableBody");
  tbody.innerHTML = "";
  window.currentOrders = orders;
  document.querySelector(".subtitle").textContent =
    `${orders.length} total orders`;
  orders.forEach((order) => tbody.appendChild(createRow(order)));
}

// ================= CREATE TABLE ROW =================
function createRow(order) {
  const tr = document.createElement("tr");
  tr.dataset.orderId = order.order_number;

  tr.innerHTML = `
    <td>${order.order_number}</td>
    <td>${order.customer_name}</td>
    <td>${formatDate(order.created_at)}</td>
    <td>₱${parseFloat(order.total_amount).toFixed(2)}</td>
    <td id="pay-${order.order_number}">${getPaymentBadge(order.payment_status, order.payment_method)}</td>
    <td>${getStatusDropdown(order.order_status)}</td>
    <td><i class="fa-regular fa-eye action-icon" title="View Order"></i></td>
  `;

  return tr;
}

// ================= PAYMENT BADGE =================
function getPaymentBadge(status, method) {
  if (status === "paid") return `<span class="badge verified">Paid</span>`;
  if (status === "pending") return `<span class="badge pending">Pending</span>`;
  if (status === "failed") return `<span class="badge rejected">Failed</span>`;
  // COD unpaid
  if (method === "cash")
    return `<span class="badge" style="background:#607D8B;color:white;">COD</span>`;
  return `<span class="badge rejected">Unknown</span>`;
}

// ================= STATUS DROPDOWN =================
function getStatusDropdown(currentStatus) {
  const statuses = [
    "pending",
    "processing",
    "packed",
    "shipped",
    "delivered",
    "cancelled",
  ];
  return `
    <select class="status-dropdown">
      ${statuses
        .map(
          (s) =>
            `<option value="${s}" ${s === currentStatus ? "selected" : ""}>${capitalize(s)}</option>`,
        )
        .join("")}
    </select>
  `;
}

// ================= STATUS CHANGE =================
document
  .getElementById("ordersTableBody")
  .addEventListener("change", function (e) {
    if (e.target.classList.contains("status-dropdown")) {
      const tr = e.target.closest("tr");
      const orderId = tr.dataset.orderId;
      const newStatus = e.target.value;
      updateOrder(orderId, newStatus);
    }
  });

// ================= UPDATE ORDER STATUS =================
function updateOrder(orderNumber, orderStatus) {
  fetch("../../api/admin_site/order_processes/update_order_status.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      order_number: orderNumber,
      order_status: orderStatus,
    }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        showToast("Status updated ✔");
        fetchOrders();
      } else {
        showToast(data.message || "Update failed ❌", true);
      }
    })
    .catch((err) => {
      console.error("Error:", err);
      showToast("Error updating status ❌", true);
    });
}

// ================= LIVE UPDATE TABLE =================
function updateTableLive(newOrders) {
  const tbody = document.getElementById("ordersTableBody");
  const map = {};
  window.currentOrders.forEach((o) => (map[o.order_number] = o));

  newOrders.forEach((order) => {
    const tr = tbody.querySelector(`tr[data-order-id="${order.order_number}"]`);

    if (!tr) {
      tbody.prepend(createRow(order));
    } else {
      if ((map[order.order_number] || {}).order_status !== order.order_status) {
        tr.cells[5].innerHTML = getStatusDropdown(order.order_status);
      }
      if (
        (map[order.order_number] || {}).payment_status !== order.payment_status
      ) {
        const cell = document.getElementById(`pay-${order.order_number}`);
        if (cell)
          cell.innerHTML = getPaymentBadge(
            order.payment_status,
            order.payment_method,
          );
      }
    }
  });

  window.currentOrders = newOrders;
}

// ================= OPEN ORDER MODAL =================
document
  .getElementById("ordersTableBody")
  .addEventListener("click", function (e) {
    if (e.target.classList.contains("action-icon")) {
      openOrderModal(e.target.closest("tr").dataset.orderId);
    }
  });

function openOrderModal(orderId) {
  const modal = document.getElementById("OrderItemModal");
  const body = document.getElementById("orderModalBody");

  body.innerHTML = "<p>Loading...</p>";
  modal.style.display = "flex";

  fetch(
    `../../api/admin_site/order_processes/fetch_order_details.php?order_id=${orderId}`,
  )
    .then((res) => res.json())
    .then((data) => {
      if (!data.success) {
        body.innerHTML = `<p style="color:red;">Error: ${data.message}</p>`;
        return;
      }

      window.currentOrderData = data.order;
      const o = data.order;

      // ── Order Info ──
      let html = `
        <div style="margin-bottom:20px;">
          <h3>Order Information</h3>
          <p><strong>Order ID:</strong> ${o.order_number}</p>
          <p><strong>Customer:</strong> ${o.customer_name}</p>
          <p><strong>Email:</strong> ${o.customer_email}</p>
          <p><strong>Phone:</strong> ${o.customer_phone}</p>
          <p><strong>Date:</strong> ${formatDate(o.created_at)}</p>
        </div>
      `;

      // ── Items Table ──
      html += `
        <div style="margin-bottom:20px;">
          <h3>Order Items</h3>
          <table style="width:100%;border-collapse:collapse;">
            <thead>
              <tr style="background:#f5f5f5;">
                <th style="padding:8px;text-align:left;border:1px solid #ddd;">Product</th>
                <th style="padding:8px;text-align:center;border:1px solid #ddd;">Qty</th>
                <th style="padding:8px;text-align:right;border:1px solid #ddd;">Price</th>
                <th style="padding:8px;text-align:right;border:1px solid #ddd;">Subtotal</th>
              </tr>
            </thead>
            <tbody>
      `;

      data.items.forEach((item) => {
        html += `
          <tr>
            <td style="padding:8px;border:1px solid #ddd;">${item.product_name}</td>
            <td style="padding:8px;text-align:center;border:1px solid #ddd;">${item.quantity}</td>
            <td style="padding:8px;text-align:right;border:1px solid #ddd;">₱${parseFloat(item.price).toFixed(2)}</td>
            <td style="padding:8px;text-align:right;border:1px solid #ddd;">₱${parseFloat(item.subtotal).toFixed(2)}</td>
          </tr>
        `;
      });

      html += `
            </tbody>
          </table>
          <p style="margin-top:10px;text-align:right;">
            <strong>Total: ₱${parseFloat(o.total_amount).toFixed(2)}</strong>
          </p>
        </div>
      `;

      // ── Payment Info ──
      html += `
        <div style="margin-bottom:20px;">
          <h3>Payment Information</h3>
          <p><strong>Method:</strong> <span style="text-transform:uppercase;">${o.payment_method}</span></p>
          <p><strong>Status:</strong> ${getPaymentBadgeHTML(o.payment_status)}</p>
          <p><strong>Order Status:</strong> <span style="text-transform:capitalize;">${o.order_status}</span></p>
      `;

      // Reference number
      if (o.resolved_reference) {
        html += `<p><strong>Reference #:</strong> ${o.resolved_reference}</p>`;
      }

      // COD note
      if (o.payment_method === "cash") {
        html += `<p style="color:#607D8B;margin-top:8px;">💵 Cash on Delivery — payment collected upon delivery.</p>`;
      }

      // GCash proof image
      if (o.payment_method === "gcash" && o.resolved_proof) {
        html += `
          <div style="margin-top:15px;">
            <p><strong>Payment Proof:</strong></p>
            <a href="${o.resolved_proof}" target="_blank">
              <img 
                src="${o.resolved_proof}" 
                alt="Payment Proof" 
                style="max-width:100%;max-height:300px;border:1px solid #ddd;border-radius:4px;margin-top:8px;display:block;"
                onerror="this.parentElement.innerHTML='<p style=\\'color:#999;\\'>Image not available</p>'"
              >
            </a>
          </div>
        `;
      } else if (
        o.payment_method === "gcash" &&
        o.payment_status === "pending" &&
        !o.resolved_proof
      ) {
        html += `<p style="color:#FF9800;margin-top:8px;">⚠️ Customer has not yet uploaded proof of payment.</p>`;
      }

      // Approve / Reject buttons — only for GCash pending
      if (
        o.payment_status === "pending" &&
        o.payment_method === "gcash" &&
        o.resolved_proof
      ) {
        html += `
          <div style="margin-top:15px;display:flex;gap:8px;">
            <button class="approve-btn" onclick="approvePayment('${o.order_number}')">Approve Payment</button>
            <button class="reject-btn"  onclick="rejectPayment('${o.order_number}')">Reject Payment</button>
          </div>
        `;
      }

      html += `</div>`;
      body.innerHTML = html;
    })
    .catch((err) => {
      console.error("Error:", err);
      body.innerHTML = "<p style='color:red;'>Error loading order details</p>";
    });
}

// ================= APPROVE PAYMENT =================
function approvePayment(orderNumber) {
  if (!confirm("Approve this payment?")) return;

  fetch("../../api/admin_site/order_processes/approve_payment.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ order_number: orderNumber }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        showToast("Payment approved ✔");
        fetchOrders();
        loadPendingPayments(); // refresh payments tab too
        setTimeout(() => closeOrderModal(), 1000);
      } else {
        showToast(data.message || "Failed to approve ❌", true);
      }
    })
    .catch(() => showToast("Error approving payment ❌", true));
}

// ================= REJECT PAYMENT =================
function rejectPayment(orderNumber) {
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
        fetchOrders();
        loadPendingPayments();
        setTimeout(() => closeOrderModal(), 1000);
      } else {
        showToast(data.message || "Failed to reject ❌", true);
      }
    })
    .catch(() => showToast("Error rejecting payment ❌", true));
}

// ================= CLOSE MODAL =================
function closeOrderModal() {
  document.getElementById("OrderItemModal").style.display = "none";
  window.currentOrderData = null;
}

// ================= TOAST =================
function showToast(msg, err = false) {
  const c = document.getElementById("toast-container");
  const t = document.createElement("div");
  t.className = `toast ${err ? "error" : "success"}`;
  t.textContent = msg;
  c.appendChild(t);
  setTimeout(() => t.classList.add("show"), 100);
  setTimeout(() => {
    t.classList.remove("show");
    setTimeout(() => t.remove(), 300);
  }, 2500);
}

// ================= HELPERS =================
function getPaymentBadgeHTML(status) {
  const map = {
    paid: { bg: "#4CAF50", label: "Paid" },
    pending: { bg: "#FF9800", label: "Pending" },
    failed: { bg: "#f44336", label: "Failed" },
  };
  const d = map[status] || { bg: "#9E9E9E", label: status };
  return `<span class="badge" style="background:${d.bg};color:white;padding:4px 8px;border-radius:4px;">${d.label}</span>`;
}

function formatDate(d) {
  return new Date(d).toISOString().split("T")[0];
}

function capitalize(w) {
  return w.charAt(0).toUpperCase() + w.slice(1);
}
