/**
 * admin_dashboard.js — Refactored for Performance
 *
 * Loading Strategy:
 *   IMMEDIATE  → KPI cards (above the fold, critical)
 *   OBSERVED   → Charts, Orders table (IntersectionObserver triggers fetch)
 *   ON-DEMAND  → Product Insight tabs (click triggers fetch)
 *   DEFERRED   → Alerts, Activity Logs, Quotations (low-priority, observed)
 *
 * Key optimisations:
 *   • Split single ?action=all into focused endpoints
 *   • IntersectionObserver — sections only fetch when scrolled into view
 *   • Per-section result cache — no duplicate fetches on re-entry
 *   • Chart instances tracked — destroyed before re-render to avoid leaks
 *   • document.createDocumentFragment() used for list/table rendering
 *   • Skeleton loaders visible instantly; replaced when data arrives
 *   • Chart animations disabled on first paint (animation: false)
 *   • Dark-mode adapter preserved — patched onto new instance registry
 */

document.addEventListener("DOMContentLoaded", () => {
  // ─────────────────────────────────────────────
  //  CONFIG
  // ─────────────────────────────────────────────
  const API_BASE = "../../api/admin_site/dashboard_api.php";
  const endpoint = (action) => `${API_BASE}?action=${action}`;

  // ─────────────────────────────────────────────
  //  PALETTE & CHART SHARED DEFAULTS
  // ─────────────────────────────────────────────
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

  const hoverLine = {
    id: "hoverLine",
    afterDraw(chart) {
      if (chart.tooltip?._active?.length) {
        const ctx = chart.ctx;
        const x = chart.tooltip._active[0].element.x;
        ctx.save();
        ctx.beginPath();
        ctx.moveTo(x, chart.scales.y.top);
        ctx.lineTo(x, chart.scales.y.bottom);
        ctx.lineWidth = 1;
        ctx.strokeStyle = "#d1d5db";
        ctx.setLineDash([4, 4]);
        ctx.stroke();
        ctx.restore();
      }
    },
  };

  // ─────────────────────────────────────────────
  //  HELPERS
  // ─────────────────────────────────────────────
  const peso = (v) =>
    "₱" +
    parseFloat(v).toLocaleString("en-PH", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    });
  const el = (id) => document.getElementById(id);
  const cap = (s) => (s ? s.charAt(0).toUpperCase() + s.slice(1) : "—");
  const badge = (status) =>
    `<span class="badge ${status?.toLowerCase()}">${cap(status)}</span>`;
  const escHtml = (str) => {
    if (str == null) return "—";
    return String(str)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
  };

  // ─────────────────────────────────────────────
  //  RESULT CACHE  (prevents refetch on re-entry)
  // ─────────────────────────────────────────────
  const _cache = {};

  async function fetchSection(action) {
    if (_cache[action]) return _cache[action];
    try {
      const res = await fetch(endpoint(action));
      const data = await res.json();
      _cache[action] = data;
      return data;
    } catch (err) {
      console.error(`Dashboard fetch error [${action}]:`, err);
      return null;
    }
  }

  // ─────────────────────────────────────────────
  //  CHART INSTANCE REGISTRY
  //  Destroy before re-creating to avoid memory leaks
  // ─────────────────────────────────────────────
  const _charts = {};

  function safeChart(canvasId, config) {
    if (_charts[canvasId]) {
      _charts[canvasId].destroy();
    }
    const canvas = el(canvasId);
    if (!canvas) return null;
    const chart = new Chart(canvas, config);
    _charts[canvasId] = chart;
    return chart;
  }

  // ─────────────────────────────────────────────
  //  INTERSECTION OBSERVER FACTORY
  //  Calls loader() once when element becomes visible.
  //  After loading, unobserves so it never fires twice.
  // ─────────────────────────────────────────────
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
  //  SKELETON HELPERS
  // ─────────────────────────────────────────────
  function skeletonRows(cols, count = 5) {
    return Array.from(
      { length: count },
      () =>
        `<tr>${Array.from(
          { length: cols },
          () => `<td><span class="skeleton skeleton-text"></span></td>`,
        ).join("")}</tr>`,
    ).join("");
  }

  function skeletonListItems(count = 5) {
    return Array.from(
      { length: count },
      () =>
        `<li class="loading-item"><span class="skeleton skeleton-text"></span></li>`,
    ).join("");
  }

  // ══════════════════════════════════════════════
  //  1 · KPI — LOAD IMMEDIATELY (above the fold)
  // ══════════════════════════════════════════════
  (async () => {
    const data = await fetchSection("kpi");
    if (!data) return;
    renderKPI(data);
  })();

  // ══════════════════════════════════════════════
  //  2 · CHARTS — load when scrolled into view
  // ══════════════════════════════════════════════
  let chartsLoaded = false;
  observeOnce("chartsRow1", async () => {
    if (chartsLoaded) return;
    chartsLoaded = true;
    const data = await fetchSection("charts");
    if (!data) return;
    renderDailyChart(data.daily_sales);
    renderMonthlyChart(data.monthly_sales);
  });

  observeOnce("chartsRow2", async () => {
    const data = await fetchSection("charts"); // already cached
    if (!data) return;
    renderTopProductsChart(data.top_products);
    renderCategoryChart(data.sales_by_category);
  });

  // ══════════════════════════════════════════════
  //  3 · ORDERS TABLE — load when scrolled into view
  // ══════════════════════════════════════════════
  observeOnce("recentOrdersSection", async () => {
    const tbody = el("recentOrdersBody");
    if (tbody) tbody.innerHTML = skeletonRows(6);
    const data = await fetchSection("orders");
    if (!data) return;
    renderRecentOrders(data.recent_orders);
  });

  // ══════════════════════════════════════════════
  //  4 · PAYMENT OVERVIEW & CUSTOMER INSIGHTS
  //      (in the insights grid — observed together)
  // ══════════════════════════════════════════════
  let insightsBaseLoaded = false;
  observeOnce("insightsGrid", async () => {
    if (insightsBaseLoaded) return;
    insightsBaseLoaded = true;
    const data = await fetchSection("insights");
    if (!data) return;
    renderPaymentOverview(data.payment_overview);
    renderCustomerInsights(data.customer_insights);
    // Seed the default active tab; other tabs load on click
    renderProductInsightsTab("best", data.product_insights?.best_selling);
    renderProductInsightsTab("low", data.product_insights?.low_stock);
    renderProductInsightsTab("recent", data.product_insights?.recent);
  });

  // ══════════════════════════════════════════════
  //  5 · PRODUCT INSIGHTS TABS — ON DEMAND
  //      Tabs already seeded from insights fetch above.
  //      Wire up click switching without extra fetches.
  // ══════════════════════════════════════════════
  document.querySelectorAll(".tab-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      document
        .querySelectorAll(".tab-btn")
        .forEach((b) => b.classList.remove("active"));
      document
        .querySelectorAll(".tab-content")
        .forEach((c) => c.classList.remove("active"));
      btn.classList.add("active");
      el("tab-" + btn.dataset.tab)?.classList.add("active");
    });
  });

  // ══════════════════════════════════════════════
  //  6 · QUOTATIONS & REQUESTS — observed (low priority)
  // ══════════════════════════════════════════════
  observeOnce("quotationsSection", async () => {
    const data = await fetchSection("orders"); // quotations come from same endpoint
    if (!data) return;
    renderQuotationsRequests(data.quotations_requests);
  });

  // ══════════════════════════════════════════════
  //  7 · ALERTS & ACTIVITY LOGS — lowest priority
  // ══════════════════════════════════════════════
  observeOnce("alertsSection", async () => {
    const alertsList = el("alertsList");
    if (alertsList)
      alertsList.innerHTML = `<p class="loading-row"><span class="skeleton skeleton-text"></span></p>`;
    const data = await fetchSection("alerts");
    if (!data) return;
    renderAlerts(data.alerts);
  });

  observeOnce("activitySection", async () => {
    const list = el("activityLogList");
    if (list) list.innerHTML = skeletonListItems(6);
    const data = await fetchSection("activity");
    if (!data) return;
    renderActivityLogs(data.activity_logs);
  });

  // ══════════════════════════════════════════════
  //  RENDER FUNCTIONS  (unchanged logic, optimised DOM)
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
            borderRadius: 3,
            borderSkipped: false,
            categoryPercentage: 0.55,
            barPercentage: 1.3,
          },
        ],
      },
      options: {
        animation: false, // ← no animation on first paint
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
            tension: 0.4,
            fill: true,
            pointRadius: 0,
            borderWidth: 2,
          },
        ],
      },
      options: {
        animation: false,
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: "index", intersect: false },
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

  function renderTopProductsChart(rows) {
    if (!rows?.length) return;
    const labels = rows.map((r) =>
      r.product_name.length > 18
        ? r.product_name.substring(0, 16) + "…"
        : r.product_name,
    );
    safeChart("topProductsChart", {
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
        animation: false,
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

  function renderCategoryChart(rows) {
    if (!rows?.length) return;
    const total = rows.reduce((s, r) => s + parseFloat(r.total), 0);
    const colors = rows.map((_, i) => PALETTE[i % PALETTE.length]);

    safeChart("categoryChart", {
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
        animation: false,
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

    // Custom legend — built with fragment for one reflow
    const legend = el("categoryLegend");
    const frag = document.createDocumentFragment();
    rows.forEach((r, i) => {
      const pct = total > 0 ? ((r.total / total) * 100).toFixed(1) : 0;
      const item = document.createElement("div");
      item.className = "legend-item";
      item.innerHTML = `<span class="legend-dot" style="background:${colors[i]}"></span>
        <span class="legend-label">${escHtml(r.category)}</span>
        <span class="legend-pct">${pct}%</span>`;
      frag.appendChild(item);
    });
    legend.textContent = "";
    legend.appendChild(frag);
  }

  function renderRecentOrders(rows) {
    const tbody = el("recentOrdersBody");
    if (!rows?.length) {
      tbody.innerHTML = `<tr><td colspan="6" class="loading-row">No orders found.</td></tr>`;
      return;
    }
    const frag = document.createDocumentFragment();
    rows.forEach((o) => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td><strong>${escHtml(o.order_number)}</strong></td>
        <td>${escHtml(o.customer_name)}<br>
            <small style="color:var(--text-secondary)">${escHtml(o.customer_email)}</small></td>
        <td>${escHtml(o.order_date)}</td>
        <td><strong>${peso(o.total_amount)}</strong></td>
        <td>${badge(o.payment_status)}</td>
        <td>${badge(o.order_status)}</td>`;
      frag.appendChild(tr);
    });
    tbody.textContent = "";
    tbody.appendChild(frag);
  }

  // Product Insights — render one tab at a time
  function renderProductInsightsTab(tab, items) {
    if (tab === "best") {
      el("bestSellingList").innerHTML = items?.length
        ? items
            .map(
              (p, i) => `<li>
              <span class="item-name"><strong>${i + 1}. ${escHtml(p.product_name)}</strong>
                <small>${escHtml(p.category ?? "—")}</small></span>
              <span class="item-val">${Number(p.total_sold).toLocaleString()} sold</span>
            </li>`,
            )
            .join("")
        : "<li class='loading-item'>No data.</li>";
    }
    if (tab === "low") {
      el("lowStockList").innerHTML = items?.length
        ? items
            .map(
              (p) => `<li>
              <span class="item-name"><strong>${escHtml(p.name)}</strong>
                <small>${escHtml(p.category)}</small></span>
              <span class="stock-badge">${p.stock} left</span>
            </li>`,
            )
            .join("")
        : "<li class='loading-item'>All products are well-stocked.</li>";
    }
    if (tab === "recent") {
      el("recentProductsList").innerHTML = items?.length
        ? items
            .map(
              (p) => `<li>
              <span class="item-name"><strong>${escHtml(p.name)}</strong>
                <small>${escHtml(p.category)} · ${escHtml(p.added_date)}</small></span>
              <span class="item-val">${peso(p.price)}</span>
            </li>`,
            )
            .join("")
        : "<li class='loading-item'>No products found.</li>";
    }
  }

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
      ? `<p class="pay-methods-title">Method Distribution</p>` +
        d.methods
          .map((m) => {
            const pct = Math.round((parseInt(m.count) / maxCount) * 100);
            return `<div class="pay-method-row">
            <span class="pay-method-label">${cap(m.payment_method)}</span>
            <div class="pay-method-bar-wrap">
              <div class="pay-method-bar ${methodColors[m.payment_method] ?? ""}" style="width:${pct}%"></div>
            </div>
            <span class="pay-method-count">${m.count}</span>
          </div>`;
          })
          .join("")
      : "";
  }

  function renderCustomerInsights(d) {
    if (!d) return;
    const rankClass = (i) =>
      i === 0 ? "top1" : i === 1 ? "top2" : i === 2 ? "top3" : "";
    const frag = document.createDocumentFragment();
    (d.top_customers ?? []).forEach((c, i) => {
      const li = document.createElement("li");
      li.innerHTML = `<span class="customer-rank ${rankClass(i)}">${i + 1}</span>
        <span class="item-name"><strong>${escHtml(c.customer_name)}</strong>
          <small>${c.order_count} order${c.order_count !== 1 ? "s" : ""}</small></span>
        <span class="item-val">${peso(c.total_spent)}</span>`;
      frag.appendChild(li);
    });
    const list = el("topCustomersList");
    list.textContent = "";
    if (frag.childNodes.length) list.appendChild(frag);
    else list.innerHTML = "<li class='loading-item'>No customer data.</li>";
  }

  function renderQuotationsRequests(d) {
    if (!d) return;
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

    const rs = d.request_summary;
    el("requestStats").innerHTML = [
      ["Pending", rs.pending],
      ["Processed", rs.processed],
      ["Cancelled", rs.cancelled],
    ]
      .map(([l, v]) => `<span class="q-pill"><span>${v}</span>${l}</span>`)
      .join("");

    el("recentQuotationsBody").innerHTML = d.recent_quotations?.length
      ? d.recent_quotations
          .map(
            (q) => `<tr>
          <td><strong>${escHtml(q.quote_number)}</strong></td>
          <td>${escHtml(q.client_name)}</td>
          <td>${peso(q.total)}</td>
          <td>${badge(q.status)}</td>
        </tr>`,
          )
          .join("")
      : `<tr><td colspan="4" class="loading-row">No quotations found.</td></tr>`;

    el("recentRequestsBody").innerHTML = d.recent_requests?.length
      ? d.recent_requests
          .map(
            (r) => `<tr>
          <td><strong>${escHtml(r.request_number)}</strong></td>
          <td>${escHtml(r.client_name)}</td>
          <td>${escHtml(r.request_date)}</td>
          <td>${badge(r.status)}</td>
        </tr>`,
          )
          .join("")
      : `<tr><td colspan="4" class="loading-row">No requests found.</td></tr>`;
  }

  function renderAlerts(d) {
    if (!d) return;
    const items = [];
    if (d.out_of_stock?.length)
      items.push(`<div class="alert-item alert-danger">
        <i class="fa-solid fa-circle-xmark"></i>
        <div><strong>${d.out_of_stock.length} product(s) out of stock</strong>
          ${d.out_of_stock.map((p) => escHtml(p.name)).join(", ")}</div>
      </div>`);
    if (d.low_stock?.length)
      items.push(`<div class="alert-item alert-warning">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <div><strong>${d.low_stock.length} low-stock product(s)</strong>
          ${d.low_stock.map((p) => `${escHtml(p.name)} (${p.stock} left)`).join(", ")}</div>
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
    if (d.stale_pending > 0)
      items.push(`<div class="alert-item alert-info">
        <i class="fa-solid fa-info-circle"></i>
        <div><strong>${d.stale_pending} pending order(s) not updated in 24+ hours</strong></div>
      </div>`);

    el("alertsList").innerHTML = items.length
      ? items.join("")
      : `<div class="alert-item alert-info"><i class="fa-solid fa-circle-check"></i>
           <div>All systems normal — no active alerts.</div></div>`;
  }

  function renderActivityLogs(rows) {
    const list = el("activityLogList");
    if (!rows?.length) {
      list.innerHTML = "<li class='loading-item'>No recent activity.</li>";
      return;
    }
    const frag = document.createDocumentFragment();
    rows.forEach((log) => {
      const li = document.createElement("li");
      li.innerHTML = `
        <div class="log-icon ${log.Status === "Success" ? "success" : "failed"}">
          <i class="fa-solid ${actionIcon(log.ActionType)}"></i>
        </div>
        <div class="log-body">
          <div class="log-action">${escHtml(log.ActionDetails)}</div>
          <div class="log-meta">${escHtml(log.UserName)} · ${escHtml(log.log_time)}</div>
        </div>`;
      frag.appendChild(li);
    });
    list.textContent = "";
    list.appendChild(frag);
  }

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
    return map[(type ?? "").toLowerCase()] ?? "fa-circle-dot";
  }

  // ─────────────────────────────────────────────
  //  DARK MODE TOGGLE  (preserved from original)
  // ─────────────────────────────────────────────
  initDarkModeToggle();

  function initDarkModeToggle() {
    const toggleBtn = el("darkModeToggle");
    if (!toggleBtn) return;
    toggleBtn.addEventListener("click", () => {
      const isDark = document.body.classList.toggle("dark-mode");
      document.documentElement.classList.toggle("dark-mode");
      localStorage.setItem("theme", isDark ? "dark" : "light");
      localStorage.setItem("theme_preference", isDark ? "dark" : "light");
      updateToggleIcon(isDark);
      applyChartTheme(isDark);
    });
    updateToggleIcon(document.body.classList.contains("dark-mode"));
  }

  function updateToggleIcon(isDark) {
    const icon = el("darkModeToggle")?.querySelector("i");
    if (!icon) return;
    icon.classList.toggle("fa-moon", !isDark);
    icon.classList.toggle("fa-sun", isDark);
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
      if (chart.options.plugins?.tooltip) {
        Object.assign(chart.options.plugins.tooltip, {
          backgroundColor: isDark ? "#1e293b" : "#ffffff",
          titleColor: isDark ? "#e2e8f0" : "#111827",
          bodyColor: isDark ? "#e2e8f0" : "#111827",
          borderColor: isDark ? "#334155" : "#e5e7eb",
        });
      }
      chart.update("none");
    });
  }

  // Apply theme on load (handles page refresh in dark mode)
  requestAnimationFrame(() =>
    applyChartTheme(document.body.classList.contains("dark-mode")),
  );
}); // end DOMContentLoaded
