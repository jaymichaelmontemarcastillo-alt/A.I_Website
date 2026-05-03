/**
 * dashboard.js — Anything Inside Admin Dashboard
 * Fetches data from api/dashboard_api.php and renders all sections.
 * Preserves the existing Chart.js style (hoverLine plugin, color scheme).
 */

document.addEventListener("DOMContentLoaded", () => {
  const API = "../../api/admin_site/dashboard_api.php?action=all";

  // ── Palette ──
  const COLORS = {
    gold: "#f4a100",
    navy: "#1f4e79",
    success: "#16a34a",
    warning: "#f59e0b",
    info: "#3b82f6",
    danger: "#ef4444",
    purple: "#7c3aed",
    teal: "#0d9488",
    rose: "#e11d48",
    slate: "#64748b",
  };
  const PALETTE = Object.values(COLORS);

  // ── Hover-line plugin (preserved from original) ──
  const hoverLine = {
    id: "hoverLine",
    afterDraw(chart) {
      if (chart.tooltip?._active?.length) {
        const ctx = chart.ctx;
        const x = chart.tooltip._active[0].element.x;
        const topY = chart.scales.y.top;
        const bottomY = chart.scales.y.bottom;
        ctx.save();
        ctx.beginPath();
        ctx.moveTo(x, topY);
        ctx.lineTo(x, bottomY);
        ctx.lineWidth = 1;
        ctx.strokeStyle = "#d1d5db";
        ctx.setLineDash([4, 4]);
        ctx.stroke();
        ctx.restore();
      }
    },
  };

  // ── Shared axis/tooltip defaults ──
  const baseScales = {
    x: {
      grid: { color: "#e5e7eb", borderDash: [4, 4] },
      ticks: { color: "#6b7280" },
    },
    y: {
      beginAtZero: true,
      grid: { color: "#e5e7eb", borderDash: [4, 4] },
      ticks: {
        color: "#6b7280",
        callback: (v) =>
          v >= 1000 ? (v / 1000).toFixed(v % 1000 ? 1 : 0) + "k" : v,
      },
    },
  };
  const tooltipDefaults = {
    backgroundColor: "#fff",
    titleColor: "#111",
    bodyColor: "#111",
    borderColor: "#e5e7eb",
    borderWidth: 1,
    padding: 10,
    displayColors: false,
    cornerRadius: 6,
    caretSize: 6,
    animation: { duration: 200 },
  };

  // ── Helpers ──
  const peso = (v) =>
    "₱" +
    parseFloat(v).toLocaleString("en-PH", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    });
  const el = (id) => document.getElementById(id);
  const badge = (status) =>
    `<span class="badge ${status?.toLowerCase()}">${cap(status)}</span>`;
  const cap = (s) => (s ? s.charAt(0).toUpperCase() + s.slice(1) : "—");

  // ══════════════════════════════════════════════
  //  FETCH ALL
  // ══════════════════════════════════════════════
  fetch(API)
    .then((r) => r.json())
    .then((data) => {
      renderKPI(data.kpi);
      renderDailyChart(data.daily_sales);
      renderMonthlyChart(data.monthly_sales);
      renderTopProductsChart(data.top_products);
      renderCategoryChart(data.sales_by_category);
      renderRecentOrders(data.recent_orders);
      renderProductInsights(data.product_insights);
      renderPaymentOverview(data.payment_overview);
      renderCustomerInsights(data.customer_insights);
      renderQuotationsRequests(data.quotations_requests);
      renderAlerts(data.alerts);
      renderActivityLogs(data.activity_logs);
    })
    .catch((err) => console.error("Dashboard API error:", err));

  // ══════════════════════════════════════════════
  //  KPI CARDS
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
    el("kpi-orders-pending").textContent =
      o.pending + " pending · " + o.completed + " delivered";

    const c = d.customers;
    el("kpi-customers-total").textContent = Number(c.total).toLocaleString();
    el("kpi-customers-new").textContent =
      "+" + c.new_this_month + " new this month";

    const s = d.stock;
    el("kpi-stock-low").textContent = s.low_stock;
    el("kpi-stock-out").textContent = s.out_of_stock + " out of stock";
  }

  // ══════════════════════════════════════════════
  //  DAILY SALES CHART
  // ══════════════════════════════════════════════
  function renderDailyChart(rows) {
    if (!rows?.length) return;
    new Chart(el("dailyChart"), {
      type: "bar",
      data: {
        labels: rows.map((r) => r.label),
        datasets: [
          {
            data: rows.map((r) => r.total),
            backgroundColor: COLORS.gold,
            borderRadius: 3,
            borderSkipped: false,
            categoryPercentage: 0.55,
            barPercentage: 1.3,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            ...tooltipDefaults,
            callbacks: { label: (ctx) => " " + peso(ctx.parsed.y) },
          },
        },
        scales: baseScales,
      },
    });
  }

  // ══════════════════════════════════════════════
  //  MONTHLY SALES CHART
  // ══════════════════════════════════════════════
  function renderMonthlyChart(rows) {
    if (!rows?.length) return;
    new Chart(el("monthlyChart"), {
      type: "line",
      data: {
        labels: rows.map((r) => r.label),
        datasets: [
          {
            data: rows.map((r) => r.total),
            borderColor: COLORS.navy,
            backgroundColor: "rgba(31,78,121,0.08)",
            tension: 0.4,
            fill: true,
            pointRadius: 0,
            borderWidth: 2,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: "index", intersect: false },
        animations: {
          tension: {
            duration: 800,
            easing: "easeOutCubic",
            from: 0.2,
            to: 0.4,
          },
        },
        plugins: {
          legend: { display: false },
          tooltip: {
            ...tooltipDefaults,
            callbacks: { label: (ctx) => " " + peso(ctx.parsed.y) },
          },
        },
        scales: baseScales,
        elements: {
          point: {
            radius: 0,
            hoverRadius: 6,
            backgroundColor: COLORS.navy,
            borderWidth: 3,
            borderColor: "#fff",
          },
        },
      },
      plugins: [hoverLine],
    });
  }

  // ══════════════════════════════════════════════
  //  TOP PRODUCTS CHART
  // ══════════════════════════════════════════════
  function renderTopProductsChart(rows) {
    if (!rows?.length) return;
    const labels = rows.map((r) =>
      r.product_name.length > 18
        ? r.product_name.substring(0, 16) + "…"
        : r.product_name,
    );
    new Chart(el("topProductsChart"), {
      type: "bar",
      data: {
        labels,
        datasets: [
          {
            data: rows.map((r) => r.total_qty),
            backgroundColor: rows.map((_, i) => PALETTE[i % PALETTE.length]),
            borderRadius: 4,
            borderSkipped: false,
          },
        ],
      },
      options: {
        indexAxis: "y",
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            ...tooltipDefaults,
            callbacks: {
              label: (ctx) =>
                ` ${ctx.parsed.x} sold · ${peso(rows[ctx.dataIndex].total_revenue)}`,
            },
          },
        },
        scales: {
          x: baseScales.x,
          y: {
            grid: { display: false },
            ticks: { color: "#6b7280", font: { size: 11 } },
          },
        },
      },
    });
  }

  // ══════════════════════════════════════════════
  //  SALES BY CATEGORY (Donut)
  // ══════════════════════════════════════════════
  function renderCategoryChart(rows) {
    if (!rows?.length) return;
    const total = rows.reduce((s, r) => s + parseFloat(r.total), 0);
    const colors = rows.map((_, i) => PALETTE[i % PALETTE.length]);

    new Chart(el("categoryChart"), {
      type: "doughnut",
      data: {
        labels: rows.map((r) => r.category),
        datasets: [
          {
            data: rows.map((r) => r.total),
            backgroundColor: colors,
            borderWidth: 2,
            borderColor: "#fff",
            hoverOffset: 6,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: "65%",
        plugins: {
          legend: { display: false },
          tooltip: {
            ...tooltipDefaults,
            callbacks: {
              label: (ctx) =>
                ` ${peso(ctx.parsed)} (${((ctx.parsed / total) * 100).toFixed(1)}%)`,
            },
          },
        },
      },
    });

    // Custom legend
    const legend = el("categoryLegend");
    legend.innerHTML = rows
      .map((r, i) => {
        const pct = total > 0 ? ((r.total / total) * 100).toFixed(1) : 0;
        return `<div class="legend-item">
                <span class="legend-dot" style="background:${colors[i]}"></span>
                <span class="legend-label">${r.category}</span>
                <span class="legend-pct">${pct}%</span>
            </div>`;
      })
      .join("");
  }

  // ══════════════════════════════════════════════
  //  RECENT ORDERS TABLE
  // ══════════════════════════════════════════════
  function renderRecentOrders(rows) {
    const tbody = el("recentOrdersBody");
    if (!rows?.length) {
      tbody.innerHTML = `<tr><td colspan="6" class="loading-row">No orders found.</td></tr>`;
      return;
    }
    tbody.innerHTML = rows
      .map(
        (o) => `
            <tr>
                <td><strong>${escHtml(o.order_number)}</strong></td>
                <td>
                    ${escHtml(o.customer_name)}<br>
                    <small style="color:var(--text-secondary)">${escHtml(o.customer_email)}</small>
                </td>
                <td>${escHtml(o.order_date)}</td>
                <td><strong>${peso(o.total_amount)}</strong></td>
                <td>${badge(o.payment_status)}</td>
                <td>${badge(o.order_status)}</td>
            </tr>
        `,
      )
      .join("");
  }

  // ══════════════════════════════════════════════
  //  PRODUCT INSIGHTS (tabs)
  // ══════════════════════════════════════════════
  function renderProductInsights(d) {
    if (!d) return;

    // Best Sellers
    el("bestSellingList").innerHTML = d.best_selling.length
      ? d.best_selling
          .map(
            (p, i) => `
                <li>
                    <span class="item-name">
                        <strong>${i + 1}. ${escHtml(p.product_name)}</strong>
                        <small>${escHtml(p.category ?? "—")}</small>
                    </span>
                    <span class="item-val">${Number(p.total_sold).toLocaleString()} sold</span>
                </li>`,
          )
          .join("")
      : "<li class='loading-item'>No data.</li>";

    // Low Stock
    el("lowStockList").innerHTML = d.low_stock.length
      ? d.low_stock
          .map(
            (p) => `
                <li>
                    <span class="item-name"><strong>${escHtml(p.name)}</strong><small>${escHtml(p.category)}</small></span>
                    <span class="stock-badge">${p.stock} left</span>
                </li>`,
          )
          .join("")
      : "<li class='loading-item'>All products are well-stocked.</li>";

    // Recent Products
    el("recentProductsList").innerHTML = d.recent.length
      ? d.recent
          .map(
            (p) => `
                <li>
                    <span class="item-name">
                        <strong>${escHtml(p.name)}</strong>
                        <small>${escHtml(p.category)} · ${escHtml(p.added_date)}</small>
                    </span>
                    <span class="item-val">${peso(p.price)}</span>
                </li>`,
          )
          .join("")
      : "<li class='loading-item'>No products found.</li>";

    // Tab switching
    document.querySelectorAll(".tab-btn").forEach((btn) => {
      btn.addEventListener("click", () => {
        document
          .querySelectorAll(".tab-btn")
          .forEach((b) => b.classList.remove("active"));
        document
          .querySelectorAll(".tab-content")
          .forEach((c) => c.classList.remove("active"));
        btn.classList.add("active");
        el("tab-" + btn.dataset.tab).classList.add("active");
      });
    });
  }

  // ══════════════════════════════════════════════
  //  PAYMENT OVERVIEW
  // ══════════════════════════════════════════════
  function renderPaymentOverview(d) {
    if (!d) return;
    const s = d.summary;
    el("pay-received").textContent = peso(s.total_received ?? 0);
    el("pay-pending").textContent = s.pending ?? 0;
    el("pay-failed").textContent = s.failed ?? 0;

    const maxCount = Math.max(
      ...(d.methods ?? []).map((m) => parseInt(m.count) || 0),
      1,
    );
    const methodColors = { gcash: "gcash", cash: "cash", card: "card" };

    el("payMethods").innerHTML = d.methods?.length
      ? `
            <p class="pay-methods-title">Method Distribution</p>
            ${d.methods
              .map((m) => {
                const pct = Math.round((parseInt(m.count) / maxCount) * 100);
                const colorClass = methodColors[m.payment_method] ?? "";
                return `<div class="pay-method-row">
                    <span class="pay-method-label">${cap(m.payment_method)}</span>
                    <div class="pay-method-bar-wrap">
                        <div class="pay-method-bar ${colorClass}" style="width:${pct}%"></div>
                    </div>
                    <span class="pay-method-count">${m.count}</span>
                </div>`;
              })
              .join("")}
        `
      : "";
  }

  // ══════════════════════════════════════════════
  //  CUSTOMER INSIGHTS
  // ══════════════════════════════════════════════
  function renderCustomerInsights(d) {
    if (!d) return;
    const rankClass = (i) =>
      i === 0 ? "top1" : i === 1 ? "top2" : i === 2 ? "top3" : "";
    el("topCustomersList").innerHTML = d.top_customers?.length
      ? d.top_customers
          .map(
            (c, i) => `
                <li>
                    <span class="customer-rank ${rankClass(i)}">${i + 1}</span>
                    <span class="item-name">
                        <strong>${escHtml(c.customer_name)}</strong>
                        <small>${c.order_count} order${c.order_count !== 1 ? "s" : ""}</small>
                    </span>
                    <span class="item-val">${peso(c.total_spent)}</span>
                </li>`,
          )
          .join("")
      : "<li class='loading-item'>No customer data.</li>";
  }

  // ══════════════════════════════════════════════
  //  QUOTATIONS & REQUESTS
  // ══════════════════════════════════════════════
  function renderQuotationsRequests(d) {
    if (!d) return;

    // Quote pills
    const qs = d.quote_summary;
    el("quoteStats").innerHTML = [
      ["Draft", qs.draft],
      ["Sent", qs.sent],
      ["Accepted", qs.accepted],
      ["Expired", qs.expired],
      ["Converted", qs.converted],
    ]
      .map(([l, v]) => `<span class="q-pill"><span>${v}</span>${l}</span>`)
      .join("");

    // Request pills
    const rs = d.request_summary;
    el("requestStats").innerHTML = [
      ["Pending", rs.pending],
      ["Processed", rs.processed],
      ["Cancelled", rs.cancelled],
    ]
      .map(([l, v]) => `<span class="q-pill"><span>${v}</span>${l}</span>`)
      .join("");

    // Recent Quotations
    el("recentQuotationsBody").innerHTML = d.recent_quotations.length
      ? d.recent_quotations
          .map(
            (q) => `
                <tr>
                    <td><strong>${escHtml(q.quote_number)}</strong></td>
                    <td>${escHtml(q.client_name)}</td>
                    <td>${peso(q.total)}</td>
                    <td>${badge(q.status)}</td>
                </tr>`,
          )
          .join("")
      : `<tr><td colspan="4" class="loading-row">No quotations found.</td></tr>`;

    // Recent Requests
    el("recentRequestsBody").innerHTML = d.recent_requests.length
      ? d.recent_requests
          .map(
            (r) => `
                <tr>
                    <td><strong>${escHtml(r.request_number)}</strong></td>
                    <td>${escHtml(r.client_name)}</td>
                    <td>${escHtml(r.request_date)}</td>
                    <td>${badge(r.status)}</td>
                </tr>`,
          )
          .join("")
      : `<tr><td colspan="4" class="loading-row">No requests found.</td></tr>`;
  }

  // ══════════════════════════════════════════════
  //  ALERTS PANEL
  // ══════════════════════════════════════════════
  function renderAlerts(d) {
    if (!d) return;
    const items = [];

    if (d.out_of_stock?.length) {
      items.push(`<div class="alert-item alert-danger">
                <i class="fa-solid fa-circle-xmark"></i>
                <div><strong>${d.out_of_stock.length} product(s) out of stock</strong>
                ${d.out_of_stock.map((p) => escHtml(p.name)).join(", ")}</div>
            </div>`);
    }

    if (d.low_stock?.length) {
      items.push(`<div class="alert-item alert-warning">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <div><strong>${d.low_stock.length} low-stock product(s)</strong>
                ${d.low_stock.map((p) => `${escHtml(p.name)} (${p.stock} left)`).join(", ")}</div>
            </div>`);
    }

    if (d.pending_payments > 0) {
      items.push(`<div class="alert-item alert-warning">
                <i class="fa-solid fa-clock"></i>
                <div><strong>${d.pending_payments} payment(s) awaiting verification</strong></div>
            </div>`);
    }

    if (d.failed_payments > 0) {
      items.push(`<div class="alert-item alert-danger">
                <i class="fa-solid fa-xmark-circle"></i>
                <div><strong>${d.failed_payments} failed payment(s) in the last 7 days</strong></div>
            </div>`);
    }

    if (d.failed_logins > 0) {
      items.push(`<div class="alert-item alert-danger">
                <i class="fa-solid fa-shield-halved"></i>
                <div><strong>${d.failed_logins} failed login attempt(s) in the last 24 hours</strong></div>
            </div>`);
    }

    if (d.stale_pending > 0) {
      items.push(`<div class="alert-item alert-info">
                <i class="fa-solid fa-info-circle"></i>
                <div><strong>${d.stale_pending} pending order(s) not updated in 24+ hours</strong></div>
            </div>`);
    }

    el("alertsList").innerHTML = items.length
      ? items.join("")
      : `<div class="alert-item alert-info"><i class="fa-solid fa-circle-check"></i><div>All systems normal — no active alerts.</div></div>`;
  }

  // ══════════════════════════════════════════════
  //  ACTIVITY LOGS
  // ══════════════════════════════════════════════
  function renderActivityLogs(rows) {
    const list = el("activityLogList");
    if (!rows?.length) {
      list.innerHTML = "<li class='loading-item'>No recent activity.</li>";
      return;
    }

    list.innerHTML = rows
      .map((log) => {
        const isSuccess = log.Status === "Success";
        const icon = actionIcon(log.ActionType);
        return `<li>
                <div class="log-icon ${isSuccess ? "success" : "failed"}">
                    <i class="fa-solid ${icon}"></i>
                </div>
                <div class="log-body">
                    <div class="log-action">${escHtml(log.ActionDetails)}</div>
                    <div class="log-meta">${escHtml(log.UserName)} · ${escHtml(log.log_time)}</div>
                </div>
            </li>`;
      })
      .join("");
  }

  // ── Map action types to FA icons ──
  function actionIcon(type) {
    const map = {
      login: "fa-right-to-bracket",
      logout: "fa-right-from-bracket",
      create: "fa-plus",
      update: "fa-pen",
      delete: "fa-trash",
      view: "fa-eye",
      export: "fa-file-export",
      payment: "fa-credit-card",
      order: "fa-cart-shopping",
    };
    const t = (type ?? "").toLowerCase();
    return map[t] ?? "fa-circle-dot";
  }

  // ── XSS-safe HTML escape ──
  function escHtml(str) {
    if (str == null) return "—";
    return String(str)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
  }
}); // end DOMContentLoaded

/* ─────────────────────────────────────────────────────────────
   CHART DARK MODE ADAPTER
   Drop this at the bottom of admin_dashboard.js (inside the
   DOMContentLoaded callback, after all renderXxx calls).

   It patches Chart.js global defaults and re-applies them
   whenever the user toggles .dark-mode on <body>.
   ───────────────────────────────────────────────────────────── */

(function initChartDarkMode() {
  // ── Resolve CSS-variable colours so Chart.js can use them ──
  function getCSSVar(name) {
    return getComputedStyle(document.documentElement)
      .getPropertyValue(name)
      .trim();
  }

  // ── Build a theme snapshot from current CSS variables ──
  function buildTheme() {
    const dark = document.body.classList.contains("dark-mode");
    return {
      dark,
      gridColor: dark ? "#1e293b" : "#e5e7eb",
      tickColor: dark ? "#94a3b8" : "#6b7280",
      tooltipBg: dark ? "#1e293b" : "#ffffff",
      tooltipText: dark ? "#e2e8f0" : "#111827",
      tooltipBorder: dark ? "#334155" : "#e5e7eb",
    };
  }

  // ── Apply theme to Chart.js global defaults ──
  function applyChartTheme(theme) {
    if (typeof Chart === "undefined") return;

    // Global scale defaults
    Chart.defaults.color = theme.tickColor;
    Chart.defaults.borderColor = theme.gridColor;

    // Update all live chart instances
    Chart.instances &&
      Object.values(Chart.instances).forEach((chart) => {
        // Scales
        if (chart.options.scales) {
          Object.values(chart.options.scales).forEach((scale) => {
            if (scale.grid) {
              scale.grid.color = theme.gridColor;
            }
            if (scale.ticks) {
              scale.ticks.color = theme.tickColor;
            }
          });
        }

        // Tooltip
        if (chart.options.plugins?.tooltip) {
          Object.assign(chart.options.plugins.tooltip, {
            backgroundColor: theme.tooltipBg,
            titleColor: theme.tooltipText,
            bodyColor: theme.tooltipText,
            borderColor: theme.tooltipBorder,
          });
        }

        chart.update("none"); // silent redraw — no animation
      });
  }

  // ── Watch for dark-mode class changes on <body> ──
  const observer = new MutationObserver((mutations) => {
    mutations.forEach((m) => {
      if (m.attributeName === "class") {
        applyChartTheme(buildTheme());
      }
    });
  });

  observer.observe(document.body, { attributes: true });

  // Apply once on load (handles page refresh in dark mode)
  // Wait a tick so charts have time to initialise first
  requestAnimationFrame(() => applyChartTheme(buildTheme()));
})();
