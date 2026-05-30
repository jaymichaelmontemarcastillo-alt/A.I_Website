/**
 * admin_dashboard.js - Based on Quotations Table
 */

document.addEventListener("DOMContentLoaded", () => {
  const API_BASE = "../../api/admin_site/dashboard_api.php";
  const endpoint = (action) => `${API_BASE}?action=${action}`;

  const COLORS = {
    gold: "#f4a100",
    navy: "#1f4e79",
    success: "#16a34a",
    warning: "#f59e0b",
    info: "#3b82f6",
    danger: "#ef4444",
  };

  const peso = (v) =>
    "₱" +
    parseFloat(v).toLocaleString("en-PH", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    });
  const el = (id) => document.getElementById(id);
  const cap = (s) => (s ? s.charAt(0).toUpperCase() + s.slice(1) : "—");
  const badge = (status) =>
    `<span class="badge ${(status || "").toLowerCase()}">${cap(status)}</span>`;
  const escHtml = (str) => {
    if (str == null) return "—";
    return String(str)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
  };

  const _cache = {};
  async function fetchSection(action) {
    if (_cache[action]) return _cache[action];
    try {
      const res = await fetch(endpoint(action));
      const data = await res.json();
      _cache[action] = data;
      return data;
    } catch (err) {
      console.error(`Fetch error [${action}]:`, err);
      return null;
    }
  }

  const _charts = {};
  function safeChart(canvasId, config) {
    if (_charts[canvasId]) _charts[canvasId].destroy();
    const canvas = el(canvasId);
    if (!canvas) return null;
    _charts[canvasId] = new Chart(canvas, config);
    return _charts[canvasId];
  }

  function observeOnce(elementId, loader) {
    const target = el(elementId);
    if (!target) return;
    const io = new IntersectionObserver(
      (entries, observer) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            observer.unobserve(entry.target);
            loader();
          }
        });
      },
      { threshold: 0.1, rootMargin: "0px 0px 100px 0px" },
    );
    io.observe(target);
  }

  // ─────────────────────────────────────────────
  // KPI - Load Immediately
  // ─────────────────────────────────────────────
  (async () => {
    const data = await fetchSection("kpi");
    if (data) renderKPI(data);
  })();

  // ─────────────────────────────────────────────
  // CHARTS
  // ─────────────────────────────────────────────
  let chartsLoaded = false;
  observeOnce("chartsRow1", async () => {
    if (chartsLoaded) return;
    chartsLoaded = true;
    const data = await fetchSection("charts");
    if (!data) return;
    renderDailyChart(data.daily_sales);
    renderMonthlyChart(data.monthly_sales);
  });

  // ─────────────────────────────────────────────
  // RECENT ORDERS & QUOTATIONS
  // ─────────────────────────────────────────────
  observeOnce("recentOrdersSection", async () => {
    const data = await fetchSection("orders");
    if (!data) return;
    renderRecentOrders(data.recent_orders);
    renderQuotations(data.recent_quotations);
    renderRequests(data.recent_requests);
  });

  // ─────────────────────────────────────────────
  // PAYMENTS & ALERTS
  // ─────────────────────────────────────────────
  observeOnce("insightsGrid", async () => {
    const data = await fetchSection("insights");
    if (!data) return;
    renderPaymentOverview(data.payment_overview);
    renderAlerts(data.alerts);
  });

  // ─────────────────────────────────────────────
  // ACTIVITY LOGS
  // ─────────────────────────────────────────────
  observeOnce("activitySection", async () => {
    const data = await fetchSection("activity");
    if (!data) return;
    renderActivityLogs(data.activity_logs);
  });

  // ══════════════════════════════════════════════
  // RENDER FUNCTIONS
  // ══════════════════════════════════════════════

  function renderKPI(d) {
    if (!d) return;
    const r = d.revenue;
    el("kpi-revenue-alltime").textContent = peso(r.all_time);
    const change = d.revenue_change;
    const changeEl = el("kpi-revenue-change");
    changeEl.textContent =
      (change >= 0 ? "+" : "") + change + "% vs last month";
    changeEl.className = change >= 0 ? "positive" : "negative";
    el("kpi-revenue-today").textContent = "Today: " + peso(r.today);

    const o = d.orders;
    el("kpi-orders-total").textContent = Number(o.total).toLocaleString();
    el("kpi-orders-pending").innerHTML =
      `<span title="Draft: ${o.draft}, Sent: ${o.sent}, Accepted: ${o.accepted}, Expired: ${o.expired}">
        ${o.completed} converted
       </span>`;

    const c = d.customers;
    el("kpi-customers-total").textContent = Number(c.total).toLocaleString();
    el("kpi-customers-new").textContent =
      "+" + c.new_this_month + " new this month";

    const s = d.stock;
    el("kpi-stock-low").textContent = s.low_stock || 0;
    el("kpi-stock-out").textContent = (s.out_of_stock || 0) + " out of stock";
  }

  function renderDailyChart(rows) {
    if (!rows?.length) return;
    safeChart("dailyChart", {
      type: "bar",
      data: {
        labels: rows.map((r) => r.label),
        datasets: [
          {
            data: rows.map((r) => r.total),
            backgroundColor: COLORS.gold,
            borderRadius: 4,
            borderSkipped: false,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: { label: (ctx) => " " + peso(ctx.parsed.y) },
          },
        },
        scales: {
          x: { grid: { display: false }, ticks: { color: "#6b7280" } },
          y: {
            beginAtZero: true,
            ticks: {
              color: "#6b7280",
              callback: (v) => (v >= 1000 ? (v / 1000).toFixed(1) + "k" : v),
            },
          },
        },
      },
    });
  }

  function renderMonthlyChart(rows) {
    if (!rows?.length) return;
    safeChart("monthlyChart", {
      type: "line",
      data: {
        labels: rows.map((r) => r.label),
        datasets: [
          {
            data: rows.map((r) => r.total),
            borderColor: COLORS.navy,
            backgroundColor: "rgba(31,78,121,0.08)",
            tension: 0.3,
            fill: true,
            pointRadius: 0,
            borderWidth: 2,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: { label: (ctx) => " " + peso(ctx.parsed.y) },
          },
        },
        scales: {
          x: { grid: { display: false }, ticks: { color: "#6b7280" } },
          y: {
            beginAtZero: true,
            ticks: {
              color: "#6b7280",
              callback: (v) => (v >= 1000 ? (v / 1000).toFixed(1) + "k" : v),
            },
          },
        },
      },
    });
  }

  function renderRecentOrders(rows) {
    const tbody = el("recentOrdersBody");
    if (!rows?.length) {
      tbody.innerHTML = `<tr><td colspan="6" class="loading-row">No converted quotations found.</td></tr>`;
      return;
    }
    tbody.innerHTML = rows
      .map(
        (o) => `
      <tr>
        <td><strong>${escHtml(o.order_number)}</strong></td>
        <td>${escHtml(o.customer_name)}<br>
            <small style="color:var(--text-secondary)">${escHtml(o.contact_person || "")} | ${escHtml(o.phone || "")}</small></td>
        <td>${escHtml(o.order_date)}</td>
        <td><strong>${peso(o.total_amount)}</strong></td>
        <td>${o.audited ? '<span class="badge success">Audited</span>' : '<span class="badge warning">Pending Audit</span>'}</td>
        <td>${badge(o.status)}</td>
      </tr>
    `,
      )
      .join("");
  }

  function renderQuotations(data) {
    if (!data) return;
    const s = data.summary;
    el("quoteStats").innerHTML = [
      ["Total", s.total],
      ["Draft", s.draft],
      ["Sent", s.sent],
      ["Accepted", s.accepted],
      ["Expired", s.expired],
      ["Converted", s.converted],
    ]
      .map(([l, v]) => `<span class="q-pill"><span>${v || 0}</span>${l}</span>`)
      .join("");

    const tbody = el("recentQuotationsBody");
    if (!data.recent?.length) {
      tbody.innerHTML = `<tr><td colspan="4" class="loading-row">No quotations found.</td></tr>`;
      return;
    }
    tbody.innerHTML = data.recent
      .map(
        (q) => `
      <tr>
        <td><strong>${escHtml(q.quote_number)}</strong></td>
        <td>${escHtml(q.client_name)}</td>
        <td>${peso(q.total)}</td>
        <td>${badge(q.status)}</td>
      </tr>
    `,
      )
      .join("");
  }

  function renderRequests(data) {
    if (!data) return;
    const s = data.summary;
    el("requestStats").innerHTML = [
      ["Total", s.total],
      ["Pending", s.pending],
      ["Processed", s.processed],
      ["Cancelled", s.cancelled],
    ]
      .map(([l, v]) => `<span class="q-pill"><span>${v || 0}</span>${l}</span>`)
      .join("");

    const tbody = el("recentRequestsBody");
    if (!data.recent?.length) {
      tbody.innerHTML = `<tr><td colspan="4" class="loading-row">No requests found.</td></tr>`;
      return;
    }
    tbody.innerHTML = data.recent
      .map(
        (r) => `
      <tr>
        <td><strong>${escHtml(r.request_number)}</strong></td>
        <td>${escHtml(r.client_name)}</td>
        <td>${escHtml(r.request_date)}</td>
        <td>${badge(r.status)}</td>
      </tr>
    `,
      )
      .join("");
  }

  function renderPaymentOverview(d) {
    if (!d) return;
    const s = d.summary;
    el("pay-received").textContent = peso(s.total_received ?? 0);
    el("pay-pending").textContent = s.pending ?? 0;
    el("pay-verified").textContent = s.verified ?? 0;
    el("pay-paid").textContent = s.paid ?? 0;
    el("pay-failed").textContent = s.failed ?? 0;

    const methodColors = { gcash: "#00a65a", cash: "#f39c12", card: "#3b82f6" };
    el("payMethods").innerHTML = d.methods?.length
      ? `<p class="pay-methods-title">Method Distribution</p>` +
        d.methods
          .map((m) => {
            const maxCount = Math.max(
              ...d.methods.map((m) => parseInt(m.count)),
              1,
            );
            const pct = Math.min(
              100,
              Math.round((parseInt(m.count) / maxCount) * 100),
            );
            return `<div class="pay-method-row">
            <span class="pay-method-label">${cap(m.payment_method)}</span>
            <div class="pay-method-bar-wrap">
              <div class="pay-method-bar" style="width:${pct}%;background:${methodColors[m.payment_method] || "#64748b"}"></div>
            </div>
            <span class="pay-method-count">${m.count}</span>
          </div>`;
          })
          .join("")
      : "<p class='no-data'>No payment data available</p>";
  }

  function renderAlerts(d) {
    if (!d) return;
    const items = [];

    if (d.out_of_stock?.length)
      items.push(`<div class="alert-item alert-danger">
        <i class="fa-solid fa-circle-xmark"></i>
        <div><strong>${d.out_of_stock.length} material(s) out of stock</strong>
          ${d.out_of_stock.map((p) => escHtml(p.material_name)).join(", ")}</div>
      </div>`);

    if (d.low_stock?.length)
      items.push(`<div class="alert-item alert-warning">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <div><strong>${d.low_stock.length} low-stock material(s)</strong>
          ${d.low_stock.map((p) => `${escHtml(p.material_name)} (${p.total_stock} left)`).join(", ")}</div>
      </div>`);

    if (d.pending_payments > 0)
      items.push(`<div class="alert-item alert-warning">
        <i class="fa-solid fa-clock"></i>
        <div><strong>${d.pending_payments} payment(s) awaiting verification</strong></div>
      </div>`);

    if (d.failed_payments > 0)
      items.push(`<div class="alert-item alert-danger">
        <i class="fa-solid fa-xmark-circle"></i>
        <div><strong>${d.failed_payments} failed payment(s) in the last 7 days</strong></div>
      </div>`);

    if (d.failed_logins > 0)
      items.push(`<div class="alert-item alert-danger">
        <i class="fa-solid fa-shield-halved"></i>
        <div><strong>${d.failed_logins} failed login attempt(s) in the last 24 hours</strong></div>
      </div>`);

    if (d.pending_conversion > 0)
      items.push(`<div class="alert-item alert-info">
        <i class="fa-solid fa-file-signature"></i>
        <div><strong>${d.pending_conversion} accepted quotation(s) pending conversion to order</strong></div>
      </div>`);

    if (d.pending_audit > 0)
      items.push(`<div class="alert-item alert-warning">
        <i class="fa-solid fa-clipboard-list"></i>
        <div><strong>${d.pending_audit} converted quotation(s) need inventory audit</strong></div>
      </div>`);

    el("alertsList").innerHTML = items.length
      ? items.join("")
      : `<div class="alert-item alert-success"><i class="fa-solid fa-circle-check"></i>
           <div>All systems normal — no active alerts.</div></div>`;
  }

  function renderActivityLogs(rows) {
    const list = el("activityLogList");
    if (!rows?.length) {
      list.innerHTML = "<li class='loading-item'>No recent activity.</li>";
      return;
    }
    list.innerHTML = rows
      .map(
        (log) => `
      <li>
        <div class="log-icon ${log.Status === "Success" ? "success" : "failed"}">
          <i class="fa-solid ${actionIcon(log.ActionType)}"></i>
        </div>
        <div class="log-body">
          <div class="log-action">${escHtml(log.ActionDetails)}</div>
          <div class="log-meta">${escHtml(log.UserName)} · ${escHtml(log.log_time)}</div>
        </div>
      </li>
    `,
      )
      .join("");
  }

  function actionIcon(type) {
    const map = {
      Logins: "fa-right-to-bracket",
      login: "fa-right-to-bracket",
      logout: "fa-right-from-bracket",
      create: "fa-plus",
      update: "fa-pen",
      delete: "fa-trash",
      view: "fa-eye",
      export: "fa-file-export",
      payment: "fa-credit-card",
      order: "fa-cart-shopping",
      "Add Product": "fa-plus-circle",
      "Update Product": "fa-pen",
      "Delete Product": "fa-trash",
    };
    return map[type] ?? "fa-circle-dot";
  }

  // Dark mode toggle
  function initDarkModeToggle() {
    const toggleBtn = el("darkModeToggle");
    if (!toggleBtn) return;
    toggleBtn.addEventListener("click", () => {
      const isDark = document.body.classList.toggle("dark-mode");
      localStorage.setItem("theme", isDark ? "dark" : "light");
      updateToggleIcon(isDark);
      applyChartTheme(isDark);
    });
    updateToggleIcon(document.body.classList.contains("dark-mode"));
  }

  function updateToggleIcon(isDark) {
    const icon = el("darkModeToggle")?.querySelector("i");
    if (icon) {
      icon.classList.toggle("fa-moon", !isDark);
      icon.classList.toggle("fa-sun", isDark);
    }
  }

  function applyChartTheme(isDark) {
    if (typeof Chart === "undefined") return;
    Chart.defaults.color = isDark ? "#94a3b8" : "#6b7280";
    Chart.defaults.borderColor = isDark ? "#334155" : "#e5e7eb";
    Object.values(_charts).forEach((chart) => {
      if (!chart) return;
      if (chart.options.scales) {
        Object.values(chart.options.scales).forEach((scale) => {
          if (scale?.grid) scale.grid.color = isDark ? "#334155" : "#e5e7eb";
          if (scale?.ticks) scale.ticks.color = isDark ? "#94a3b8" : "#6b7280";
        });
      }
      chart.update("none");
    });
  }

  initDarkModeToggle();
});
