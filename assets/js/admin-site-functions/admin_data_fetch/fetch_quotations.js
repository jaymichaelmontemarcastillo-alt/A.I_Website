class QuotationManager {
  constructor(config = {}) {
    this.config = {
      tableBodyId: "quotationsTableBody",
      apiUrl: "../../api/admin_site/get_quotations.php",
      statusApiUrl: "../../api/admin_site/update_quotation_status.php",
      limit: 10,
      ...config,
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
    // Search functionality
    const searchInput = document.getElementById("quotationSearch");
    if (searchInput) {
      searchInput.addEventListener("debounce", (e) => {
        this.currentSearch = e.target.value;
        this.currentPage = 1;
        this.fetchQuotations();
      });
      this.addDebounceListener(searchInput);
    }

    // Filter by status
    const statusFilter = document.getElementById("statusFilter");
    if (statusFilter) {
      statusFilter.addEventListener("change", (e) => {
        this.currentFilter = e.target.value;
        this.currentPage = 1;
        this.fetchQuotations();
      });
    }

    // Pagination
    const prevBtn = document.getElementById("prevPage");
    const nextBtn = document.getElementById("nextPage");
    if (prevBtn) prevBtn.addEventListener("click", () => this.prevPage());
    if (nextBtn) nextBtn.addEventListener("click", () => this.nextPage());
  }

  addDebounceListener(input) {
    let debounceTimer;
    input.addEventListener("input", (e) => {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(() => {
        this.currentSearch = e.target.value;
        this.currentPage = 1;
        this.fetchQuotations();
      }, 500);
    });
  }

  async fetchQuotations() {
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
        this.showNotification("Quotations loaded successfully", "success");
      } else {
        this.showNotification("Failed to load quotations", "error");
      }
    } catch (error) {
      console.error("Error fetching quotations:", error);
      this.showNotification("Error fetching quotations", "error");
    }
  }

  renderTable(quotations) {
    const tableBody = document.getElementById(this.config.tableBodyId);
    if (!tableBody) return;

    if (quotations.length === 0) {
      tableBody.innerHTML =
        '<tr><td colspan="9" class="text-center">No quotations found</td></tr>';
      return;
    }

    tableBody.innerHTML = quotations
      .map(
        (quote) => `
            <tr>
                <td>${this.escapeHtml(quote.quote_number)}</td>
                <td>${this.escapeHtml(quote.client_name)}</td>
                <td>${this.escapeHtml(quote.contact_person || "")}</td>
                <td>${this.escapeHtml(quote.email || "")}</td>
                <td>${this.escapeHtml(quote.phone || "")}</td>
                <td>$${parseFloat(quote.total).toFixed(2)}</td>
                <td>${new Date(quote.created_at).toLocaleDateString()}</td>
                <td>
                    <select class="status-select" data-id="${quote.id}" onchange="quotationManager.updateStatus(${quote.id}, this.value)">
                        <option value="draft" ${quote.status === "draft" ? "selected" : ""}>Draft</option>
                        <option value="sent" ${quote.status === "sent" ? "selected" : ""}>Sent</option>
                        <option value="accepted" ${quote.status === "accepted" ? "selected" : ""}>Accepted</option>
                        <option value="expired" ${quote.status === "expired" ? "selected" : ""}>Expired</option>
                        <option value="converted" ${quote.status === "converted" ? "selected" : ""}>Converted</option>
                    </select>
                </td>
                <td>
                    <div class="action-buttons">
                        <button class="btn-view" onclick="quotationManager.viewQuotation(${quote.id})" title="View">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <button class="btn-edit" onclick="quotationManager.editQuotation(${quote.id})" title="Edit">
                            <i class="fa-solid fa-edit"></i>
                        </button>
                        <button class="btn-delete" onclick="quotationManager.deleteQuotation(${quote.id})" title="Delete">
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
        body: JSON.stringify({
          id: id,
          status: status,
        }),
      });

      const result = await response.json();

      if (result.success) {
        this.showNotification(`Status updated to "${status}"`, "success");
        this.fetchQuotations();
      } else {
        this.showNotification(
          result.message || "Failed to update status",
          "error",
        );
        this.fetchQuotations();
      }
    } catch (error) {
      console.error("Error updating status:", error);
      this.showNotification("Error updating status", "error");
      this.fetchQuotations();
    }
  }

  renderPagination(pagination) {
    const pageInfo = document.getElementById("pageInfo");
    const prevBtn = document.getElementById("prevPage");
    const nextBtn = document.getElementById("nextPage");

    if (pageInfo) {
      pageInfo.textContent = `Page ${pagination.page} of ${pagination.totalPages} (Total: ${pagination.total})`;
    }

    if (prevBtn) {
      prevBtn.disabled = pagination.page <= 1;
    }

    if (nextBtn) {
      nextBtn.disabled = pagination.page >= pagination.totalPages;
    }
  }

  nextPage() {
    this.currentPage++;
    this.fetchQuotations();
    this.scrollToTop();
  }

  prevPage() {
    if (this.currentPage > 1) {
      this.currentPage--;
      this.fetchQuotations();
      this.scrollToTop();
    }
  }

  scrollToTop() {
    document
      .querySelector(".content-body")
      .scrollIntoView({ behavior: "smooth", block: "start" });
  }

  viewQuotation(id) {
    window.location.href = `quotation-view.php?id=${id}`;
  }

  editQuotation(id) {
    window.location.href = `quotation-edit.php?id=${id}`;
  }

  async deleteQuotation(id) {
    if (!confirm("Are you sure you want to delete this quotation?")) {
      return;
    }

    try {
      const response = await fetch("../api/delete-quotation.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ id: id }),
      });

      const result = await response.json();

      if (result.success) {
        this.showNotification("Quotation deleted successfully", "success");
        this.fetchQuotations();
      } else {
        this.showNotification(
          result.message || "Failed to delete quotation",
          "error",
        );
      }
    } catch (error) {
      console.error("Error deleting quotation:", error);
      this.showNotification("Error deleting quotation", "error");
    }
  }

  showNotification(message, type = "info") {
    // Create notification element
    const notification = document.createElement("div");
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 4px;
            z-index: 10000;
            animation: slideIn 0.3s ease-in-out;
            ${type === "success" ? "background-color: #4caf50; color: white;" : ""}
            ${type === "error" ? "background-color: #f44336; color: white;" : ""}
            ${type === "info" ? "background-color: #2196f3; color: white;" : ""}
        `;

    document.body.appendChild(notification);

    setTimeout(() => {
      notification.style.animation = "slideOut 0.3s ease-in-out";
      setTimeout(() => notification.remove(), 300);
    }, 3000);
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

  // Reload quotations
  refresh() {
    this.currentPage = 1;
    this.currentFilter = "all";
    this.currentSearch = "";
    this.fetchQuotations();
  }
}

// Initialize on page load
let quotationManager;
document.addEventListener("DOMContentLoaded", () => {
  quotationManager = new QuotationManager();
});
