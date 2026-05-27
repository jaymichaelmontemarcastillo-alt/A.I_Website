/**
 * Quotation Manager
 * Handles: listing, filtering, pagination, status updates, PDF generation, deletion
 */
class QuotationManager {
  constructor() {
    this.config = {
      tableBodyId: "quotationsTableBody",
      apiUrl: "../../api/admin_site/get_quotations.php",
      statusApiUrl: "../../api/admin_site/update_quotation_status.php",
      pdfApiUrl: "../../api/admin_site/generate_quotation_pdf.php",
      getSingleApiUrl: "../../api/admin_site/get_single_quotation.php",
      updateApiUrl: "../../api/admin_site/update_quotation.php",
      deleteApiUrl: "../../api/admin_site/delete_quotation.php",
      limit: 10,
    };
    this.currentPage = 1;
    this.currentFilter = "all";
    this.currentSearch = "";
    this.init();
  }

  init() {
    this.attachEventListeners();
    this.fetchQuotations();
  }

  attachEventListeners() {
    const searchInput = document.getElementById("quotationSearch");
    if (searchInput) {
      let timer;
      searchInput.addEventListener("input", (e) => {
        clearTimeout(timer);
        timer = setTimeout(() => {
          this.currentSearch = e.target.value.trim();
          this.currentPage = 1;
          this.fetchQuotations();
        }, 500);
      });
    }

    const statusFilter = document.getElementById("statusFilter");
    if (statusFilter) {
      statusFilter.addEventListener("change", (e) => {
        this.currentFilter = e.target.value;
        this.currentPage = 1;
        this.fetchQuotations();
      });
    }

    const prevBtn = document.getElementById("prevPage");
    const nextBtn = document.getElementById("nextPage");
    if (prevBtn) prevBtn.addEventListener("click", () => this.prevPage());
    if (nextBtn) nextBtn.addEventListener("click", () => this.nextPage());
  }

  startNewQuotationWorkflow() {
    if (window.bomManager) {
      window.bomManager.openNewBomModal();
    }
  }

  async fetchQuotations() {
    const tbody = document.getElementById(this.config.tableBodyId);
    if (tbody) {
      tbody.innerHTML = `<tr><td colspan="9" style="padding:40px;text-align:center;"><i class="fa-solid fa-spinner fa-spin"></i> Loading quotations...</td></tr>`;
    }

    try {
      const params = new URLSearchParams({
        page: this.currentPage,
        limit: this.config.limit,
        status: this.currentFilter !== "all" ? this.currentFilter : "",
        search: this.currentSearch,
      });
      const response = await fetch(`${this.config.apiUrl}?${params}`);
      const result = await response.json();

      if (result.success) {
        this.renderTable(result.data);
        this.renderPagination(result.pagination);
      } else {
        this.showNotification(result.message || "Failed to load", "error");
      }
    } catch (err) {
      console.error(err);
      this.showNotification("Error fetching quotations", "error");
    }
  }

  renderTable(quotations) {
    const tbody = document.getElementById(this.config.tableBodyId);
    if (!tbody) return;

    if (!quotations || quotations.length === 0) {
      tbody.innerHTML = `<tr><td colspan="9" class="text-center">No quotations found.</td></tr>`;
      return;
    }

    tbody.innerHTML = quotations
      .map(
        (q) => `
            <tr>
                <td>${this.escapeHtml(q.quote_number)}</td>
                <td>${this.escapeHtml(q.client_name)}</td>
                <td>${this.escapeHtml(q.contact_person || "")}</td>
                <td>${this.escapeHtml(q.email || "")}</td>
                <td>${this.escapeHtml(q.phone || "")}</td>
                <td>₱ ${parseFloat(q.total || 0).toFixed(2)}</td>
                <td>${new Date(q.created_at).toLocaleDateString()}</td>
                <td>
                    <select class="status-select" data-id="${q.id}" onchange="quotationManager.updateStatus(${q.id}, this.value)">
                        <option value="draft" ${q.status === "draft" ? "selected" : ""}>Draft</option>
                        <option value="sent" ${q.status === "sent" ? "selected" : ""}>Sent</option>
                        <option value="accepted" ${q.status === "accepted" ? "selected" : ""}>Accepted</option>
                        <option value="expired" ${q.status === "expired" ? "selected" : ""}>Expired</option>
                        <option value="converted" ${q.status === "converted" ? "selected" : ""}>Converted</option>
                    </select>
                </td>
                <td>
                    <div class="action-buttons">
                        <button class="btn-edit" onclick="quotationViewManager.openModal(${q.id})" title="View/Edit Quotation">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <button class="btn-pdf" onclick="quotationManager.generatePDF(${q.id})" title="Generate PDF">
                            <i class="fa-solid fa-file-pdf"></i>
                        </button>
                        <button class="btn-delete" onclick="quotationManager.deleteQuotation(${q.id}, '${this.escapeHtml(q.quote_number)}')" title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `,
      )
      .join("");
  }

  async updateStatus(id, status) {
    try {
      const response = await fetch(this.config.statusApiUrl, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ id, status }),
      });
      const result = await response.json();
      if (result.success) {
        this.showNotification(`Status updated to "${status}"`, "success");
      } else {
        this.showNotification(result.message || "Failed", "error");
      }
    } catch (err) {
      this.showNotification("Error updating status", "error");
    }
    this.fetchQuotations();
  }

  async generatePDF(id) {
    if (!id) return;
    this.showNotification("Generating PDF...", "info");
    try {
      const response = await fetch(this.config.pdfApiUrl, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ id }),
      });
      const result = await response.json();
      if (result.success) {
        this.showNotification("PDF generated!", "success");
        window.open(result.pdf_url, "_blank");
      } else {
        this.showNotification(result.message || "Failed", "error");
      }
    } catch (err) {
      this.showNotification("Error generating PDF", "error");
    }
  }

  deleteQuotation(id, quoteNumber) {
    if (!confirm(`Delete quotation ${quoteNumber}? This cannot be undone.`))
      return;
    fetch(this.config.deleteApiUrl, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ id }),
    })
      .then((res) => res.json())
      .then((result) => {
        if (result.success) {
          this.showNotification("Quotation deleted", "success");
          this.fetchQuotations();
        } else {
          this.showNotification(result.message || "Failed", "error");
        }
      })
      .catch((err) => this.showNotification("Error deleting", "error"));
  }

  renderPagination(pagination) {
    const pageInfo = document.getElementById("pageInfo");
    const prevBtn = document.getElementById("prevPage");
    const nextBtn = document.getElementById("nextPage");
    if (pageInfo)
      pageInfo.textContent = `Page ${pagination.page} of ${pagination.totalPages} (Total: ${pagination.total})`;
    if (prevBtn) prevBtn.disabled = pagination.page <= 1;
    if (nextBtn) nextBtn.disabled = pagination.page >= pagination.totalPages;
  }

  nextPage() {
    this.currentPage++;
    this.fetchQuotations();
  }

  prevPage() {
    if (this.currentPage > 1) {
      this.currentPage--;
      this.fetchQuotations();
    }
  }

  escapeHtml(text) {
    const map = {
      "&": "&amp;",
      "<": "&lt;",
      ">": "&gt;",
      '"': "&quot;",
      "'": "&#039;",
    };
    return text ? String(text).replace(/[&<>"']/g, (m) => map[m]) : "";
  }

  showNotification(message, type = "info") {
    const colors = {
      success: "#10b981",
      error: "#ef4444",
      info: "#3b82f6",
      warning: "#f59e0b",
    };
    const notification = document.createElement("div");
    notification.style.cssText = `position:fixed; top:20px; right:20px; z-index:100001; background:${colors[type]}; color:white; padding:12px 20px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.15); animation: slideInRight 0.3s ease;`;
    notification.innerHTML = message;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
  }
}
