/**
 * admin_products.js - WITH TYPE MANAGEMENT
 */

document.addEventListener("DOMContentLoaded", function () {
  // ==========================================
  // API PATH
  // ==========================================
  const API = "../../api/admin_site/products";

  console.log("=== ADMIN PRODUCTS DEBUG ===");
  console.log("API Path:", API);

  // ==========================================
  // DOM REFS
  // ==========================================
  const $ = (sel) => document.querySelector(sel);
  const $$ = (sel) => document.querySelectorAll(sel);

  const tableBody = $("#tableBody");
  const paginationWrap = $("#paginationWrap");
  const totalCount = $("#totalCount");
  const searchField = $("#searchField");
  const typeFilter = $("#typeFilter");
  const stockFilter = $("#stockFilter");
  const addBtn = $("#addBtn");
  const exportBtn = $("#exportBtn");
  const importBtn = $("#importBtn");
  const deleteAllBtn = $("#deleteAllBtn");

  // Product Modal
  const productModal = $("#productModal");
  const modalTitle = $("#modalTitle");
  const productForm = $("#productForm");
  const editId = $("#editId");
  const productName = $("#productName");
  const productSku = $("#productSku");
  const productType = $("#productType");
  const productPrice = $("#productPrice");
  const productStock = $("#productStock");
  const productUnit = $("#productUnit");
  const productMaterialType = $("#productMaterialType");
  const productDesc = $("#productDesc");
  const submitBtn = $("#submitBtn");
  const modalCloseBtn = $("#modalCloseBtn");
  const modalCancelBtn = $("#modalCancelBtn");

  const matContainer = $("#matContainer");
  const matEmpty = $("#matEmpty");
  const addMatBtn = $("#addMatBtn");

  // Delete Modal
  const deleteModal = $("#deleteModal");
  const deleteMsg = $("#deleteMsg");
  const deleteConfirmBtn = $("#deleteConfirmBtn");
  const deleteCancelBtn = $("#deleteCancelBtn");
  const deleteCloseBtn = $("#deleteCloseBtn");

  // Import Modal
  const importModal      = $("#importModal");
  const importForm       = $("#importForm");
  const importFile       = $("#importFile");      // unified file input (was: excelFile)
  const importFileHint   = $("#importFileHint");  // live format hint paragraph
  const importSubmitBtn  = $("#importSubmitBtn");
  const importCloseBtn   = $("#importCloseBtn");
  const importCancelBtn  = $("#importCancelBtn");
  const importProgress   = $("#importProgress");
  const progressFill     = $("#progressFill");
  const progressStatus   = $("#progressStatus");
  const importResult     = $("#importResult");

  // Import Error Modal
  const importErrorModal = $("#importErrorModal");
  const importErrorTitle = $("#importErrorTitle");
  const importErrorMessage = $("#importErrorMessage");
  const importErrorCloseBtn = $("#importErrorCloseBtn");
  const importErrorCancelBtn = $("#importErrorCancelBtn");
  const importErrorRetryBtn = $("#importErrorRetryBtn");

  const deleteAllModal = $("#deleteAllModal");
  const deleteAllCloseBtn = $("#deleteAllCloseBtn");
  const deleteAllCancelBtn = $("#deleteAllCancelBtn");
  const deleteAllConfirmBtn = $("#deleteAllConfirmBtn");
  const deleteAllPassword = $("#deleteAllPassword");
  const deleteAllFeedback = $("#deleteAllFeedback");

  // ==========================================
  // STATE
  // ==========================================
  let currentPage = 1;
  const LIMIT = 12;
  let deleteId = null;
  let materialsList = [];
  let isLoading = false;

  // ==========================================
  // UTILITY
  // ==========================================
  function escape(text) {
    if (!text) return "";
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
  }

  function toast(message, type = "info") {
    const existing = document.querySelector(".toast");
    if (existing) existing.remove();

    const el = document.createElement("div");
    el.className = `toast toast-${type}`;
    const icons = {
      success: "fa-check-circle",
      error: "fa-circle-exclamation",
      info: "fa-info-circle",
    };
    el.innerHTML = `<i class="fa-solid ${icons[type] || icons.info}"></i><span>${message}</span>`;
    document.body.appendChild(el);

    requestAnimationFrame(() => {
      el.classList.add("show");
    });

    setTimeout(() => {
      el.classList.remove("show");
      setTimeout(() => el.remove(), 400);
    }, 4000);

    el.addEventListener("click", () => {
      el.classList.remove("show");
      setTimeout(() => el.remove(), 400);
    });
  }

  // ==========================================
  // MODAL HELPERS
  // ==========================================
  function openModal(modal) {
    modal.classList.add("show");
    modal.style.display = "flex";
    document.body.style.overflow = "hidden";
  }

  function closeModal(modal) {
    modal.classList.remove("show");
    modal.style.display = "none";
    document.body.style.overflow = "";
  }

  // ==========================================
  // FETCH PRODUCTS
  // ==========================================
  function fetchProducts(page = 1) {
    if (isLoading) return;
    isLoading = true;

    const search = searchField ? searchField.value.trim() : "";
    const type = typeFilter ? typeFilter.value : "";
    const stock = stockFilter ? stockFilter.value : "";

    let url = `${API}/get_products.php?page=${page}&limit=${LIMIT}`;
    if (search) url += `&search=${encodeURIComponent(search)}`;
    if (type) url += `&product_type_id=${type}`;
    if (stock) url += `&stock_status=${encodeURIComponent(stock)}`;

    tableBody.innerHTML = `
            <tr class="tbl-loading">
                <td colspan="6">
                    <div class="spinner">
                        <i class="fa-solid fa-spinner fa-spin"></i> Loading products...
                    </div>
                </td>
            </tr>
        `;

    fetch(url)
      .then((res) => {
        if (!res.ok) {
          throw new Error(`HTTP ${res.status}: ${res.statusText}`);
        }
        return res.json();
      })
      .then((data) => {
        isLoading = false;
        if (data.success) {
          renderTable(data.data);
          renderPagination(data.pagination);
          updateCount(data.pagination);
          currentPage = page;
        } else {
          tableBody.innerHTML = `
                        <tr><td colspan="6">
                            <div class="error-state">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                ${data.message || "Failed to load products"}
                            </div>
                        </td></tr>
                    `;
        }
      })
      .catch((err) => {
        isLoading = false;
        tableBody.innerHTML = `
                    <tr><td colspan="6">
                        <div class="error-state">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            Network error: ${err.message}
                        </div>
                    </td></tr>
                `;
        toast("Network error: " + err.message, "error");
      });
  }

  // ==========================================
  // RENDER TABLE
  // ==========================================
  function renderTable(products) {
    if (!products || products.length === 0) {
      tableBody.innerHTML = `
                <tr class="tbl-empty">
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="fa-regular fa-box-open"></i>
                            <p>No products found</p>
                        </div>
                    </td>
                </tr>
            `;
      return;
    }

    let html = "";
    const statusMap = {
      in_stock:
        '<span class="badge-status in-stock"><i class="fa-solid fa-check"></i> In Stock</span>',
      low_stock:
        '<span class="badge-status low-stock"><i class="fa-solid fa-triangle-exclamation"></i> Low Stock</span>',
      out_of_stock:
        '<span class="badge-status out-of-stock"><i class="fa-solid fa-xmark"></i> Out of Stock</span>',
    };

    products.forEach((p) => {
      const status = p.stock_status || "in_stock";
      html += `
                <tr data-id="${p.id}">
                    <td class="col-name"><p>${escape(p.name)}</p></td>
                    <td class="col-type"><span class="badge-type">${escape(p.product_type_name || "N/A")}</span></td>
                    <td class="col-num">₱${parseFloat(p.price).toFixed(2)}</td>
                    <td class="col-num">${p.stock || 0}</td>
                    <td class="col-status">${statusMap[status] || statusMap.in_stock}</td>
                    <td class="col-action tbl-actions">
                        <div style="display:flex; align-items:center; justify-content:center; gap:6px;">
                            <button class="btn-action edit" data-id="${p.id}" title="Edit Product">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class="btn-action delete" data-id="${p.id}" title="Delete Product" style="color:#ef4444;">
                                <i class="fa-solid fa-trash"></i> 
                            </button>
                        </div>
                    </td>
                </tr>
            `;
    });

    tableBody.innerHTML = html;

    $$(".btn-action.edit").forEach((btn) => {
      btn.addEventListener("click", () =>
        openEditModal(parseInt(btn.dataset.id)),
      );
    });
    $$(".btn-action.delete").forEach((btn) => {
      btn.addEventListener("click", () =>
        openDeleteModal(parseInt(btn.dataset.id)),
      );
    });
  }

  // ==========================================
  // PAGINATION
  // ==========================================
  function renderPagination(pagination) {
    if (!pagination || pagination.pages <= 1) {
      paginationWrap.innerHTML = "";
      return;
    }

    const current = pagination.page;
    const total = pagination.pages;

    let html = "";
    html += `<button class="btn-page" data-page="${current - 1}" ${current <= 1 ? "disabled" : ""}>
            <i class="fa-solid fa-chevron-left"></i>
        </button>`;

    let start = Math.max(1, current - 2);
    let end = Math.min(total, current + 2);

    if (start > 1) {
      html += `<button class="btn-page" data-page="1">1</button>`;
      if (start > 2) html += `<span class="pg-dots">…</span>`;
    }

    for (let i = start; i <= end; i++) {
      html += `<button class="btn-page ${i === current ? "active" : ""}" data-page="${i}">${i}</button>`;
    }

    if (end < total) {
      if (end < total - 1) html += `<span class="pg-dots">…</span>`;
      html += `<button class="btn-page" data-page="${total}">${total}</button>`;
    }

    html += `<button class="btn-page" data-page="${current + 1}" ${current >= total ? "disabled" : ""}>
            <i class="fa-solid fa-chevron-right"></i>
        </button>`;

    paginationWrap.innerHTML = html;

    $$(".btn-page").forEach((btn) => {
      btn.addEventListener("click", function () {
        if (this.disabled) return;
        const page = parseInt(this.dataset.page);
        if (page > 0 && page <= total) {
          fetchProducts(page);
          window.scrollTo({ top: 0, behavior: "smooth" });
        }
      });
    });
  }

  function updateCount(pagination) {
    if (totalCount) {
      totalCount.textContent = pagination
        ? `${pagination.total} product${pagination.total !== 1 ? "s" : ""}`
        : "Loading...";
    }
  }

  // ==========================================
  // LOAD FILTERS
  // ==========================================
  function loadFilters() {
    fetch(`${API}/get_products.php?limit=0`)
      .then((res) => res.json())
      .then((data) => {
        if (data.success && data.filters) {
          // Type filter (main)
          typeFilter.innerHTML = '<option value="">All Types</option>';
          data.filters.product_types.forEach((t) => {
            const opt = document.createElement("option");
            opt.value = t.id;
            opt.textContent = t.name;
            typeFilter.appendChild(opt);
          });

          // Type dropdown (modal)
          productType.innerHTML = '<option value="">— Select type —</option>';
          data.filters.product_types.forEach((t) => {
            const opt = document.createElement("option");
            opt.value = t.id;
            opt.textContent = t.name;
            productType.appendChild(opt);
          });

          // After loading filters, add the type management buttons
          addTypeManagementButtons();

          console.log("✅ Filters loaded successfully");
        } else {
          console.error("❌ Failed to load filters:", data.message);
        }
      })
      .catch((err) => {
        console.error("❌ Filter load error:", err);
      });

    // Load materials
    fetch(`${API}/get_materials.php`)
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          materialsList = data.data;
          console.log("✅ Materials loaded:", materialsList.length);
        }
      })
      .catch((err) => {
        console.error("❌ Materials load error:", err);
      });
  }

  // ==========================================
  // MODAL: ADD PRODUCT
  // ==========================================
  function openAddModal() {
    modalTitle.innerHTML = '<i class="fa-solid fa-box"></i> Add Product';
    submitBtn.innerHTML =
      '<i class="fa-solid fa-floppy-disk"></i> Save Product';
    productForm.reset();
    editId.value = "";
    matContainer.innerHTML = "";
    matEmpty.style.display = "block";
    openModal(productModal);
  }

  // ==========================================
  // MODAL: EDIT PRODUCT
  // ==========================================
  function openEditModal(id) {
    modalTitle.innerHTML = '<i class="fa-solid fa-pen"></i> Edit Product';
    submitBtn.innerHTML =
      '<i class="fa-solid fa-floppy-disk"></i> Update Product';

    fetch(`${API}/get_product.php?id=${id}`)
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          const p = data.data;
          editId.value = p.id;
          productName.value = p.name || "";
          productSku.value = p.sku || "";
          productType.value = p.product_type_id || "";
          productPrice.value = p.price || "";
          productStock.value = p.stock || 0;
          productUnit.value = p.unit || "piece";
          productMaterialType.value = p.material_type || "assembled_product";
          productDesc.value = p.description || "";

          if (p.materials && p.materials.length > 0) {
            renderMaterials(p.materials);
          } else {
            matContainer.innerHTML = "";
            matEmpty.style.display = "block";
          }

          openModal(productModal);
        } else {
          toast("Error loading product: " + data.message, "error");
        }
      })
      .catch((err) => {
        console.error("❌ Load product error:", err);
        toast("Network error loading product: " + err.message, "error");
      });
  }

  // ==========================================
  // CLOSE PRODUCT MODAL
  // ==========================================
  function closeProductModal() {
    closeModal(productModal);
    productForm.reset();
    editId.value = "";
  }

  // ==========================================
  // MATERIALS
  // ==========================================
  function renderMaterials(materials) {
    if (!materials || materials.length === 0) {
      matContainer.innerHTML = "";
      matEmpty.style.display = "block";
      return;
    }

    matEmpty.style.display = "none";
    let html = "";
    materials.forEach((mat, idx) => {
      html += createMatRow(mat, idx);
    });
    matContainer.innerHTML = html;
  }

  function createMatRow(mat, idx) {
    const matId = mat.material_id || mat.id || "";
    const qty = mat.quantity || 1;

    return `
            <div class="mat-row" data-idx="${idx}">
                <div class="mat-fields">
                    <div class="form-group">
                        <select class="mat-select" name="material_ids[]">
                            <option value="">— Select material —</option>
                            ${materialsList
        .map(
          (m) => `
                                <option value="${m.id}" ${m.id == matId ? "selected" : ""}>
                                    ${escape(m.material_name)} (${escape(m.type || "N/A")})
                                </option>
                            `,
        )
        .join("")}
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="number" class="mat-qty" name="material_quantities[]" 
                               value="${qty}" min="0.01" step="0.01" placeholder="Qty">
                    </div>
                    <button type="button" class="mat-remove" title="Remove">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>
        `;
  }

  function addMatRow() {
    matEmpty.style.display = "none";
    const idx = matContainer.children.length;
    const row = document.createElement("div");
    row.className = "mat-row";
    row.dataset.idx = idx;
    row.innerHTML = createMatRow({}, idx);
    matContainer.appendChild(row);

    row.querySelector(".mat-remove").addEventListener("click", function () {
      row.remove();
      if (matContainer.children.length === 0) {
        matEmpty.style.display = "block";
      }
    });
  }

  // ==========================================
  // SAVE PRODUCT
  // ==========================================
  function saveProduct(e) {
    e.preventDefault();

    const name = productName.value.trim();
    const type = productType.value;
    const price = productPrice.value;
    const stock = productStock.value;

    if (!name) {
      toast("Product name is required", "error");
      productName.focus();
      return;
    }
    if (!type) {
      toast("Please select a product type", "error");
      productType.focus();
      return;
    }
    if (price === "" || parseFloat(price) < 0) {
      toast("Please enter a valid price", "error");
      productPrice.focus();
      return;
    }
    if (stock === "" || parseInt(stock) < 0) {
      toast("Please enter a valid stock quantity", "error");
      productStock.focus();
      return;
    }

    submitBtn.disabled = true;
    submitBtn.innerHTML =
      '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';

    const formData = new FormData();
    if (editId.value) formData.append("id", editId.value);
    formData.append("name", name);
    formData.append("sku", productSku.value.trim());
    formData.append("product_type_id", type);
    formData.append("price", price);
    formData.append("stock", stock);
    formData.append("unit", productUnit.value);
    formData.append("material_type", productMaterialType.value);
    formData.append("description", productDesc.value.trim());

    const selects = $$(".mat-select");
    const qtys = $$(".mat-qty");
    const materials = [];
    selects.forEach((sel, i) => {
      if (sel.value) {
        materials.push({
          material_id: parseInt(sel.value),
          quantity: parseFloat(qtys[i]?.value) || 1,
        });
      }
    });
    formData.append("materials", JSON.stringify(materials));

    const endpoint = editId.value
      ? `${API}/update_products.php`
      : `${API}/add_products.php`;

    fetch(endpoint, {
      method: "POST",
      body: formData,
    })
      .then((res) => res.text().then((text) => ({ res, text })))
      .then(({ res, text }) => {
        let data;
        try {
          data = JSON.parse(text);
        } catch (e) {
          throw new Error(
            `Server returned HTTP ${res.status} (non-JSON response)`,
          );
        }
        if (data.success) {
          toast(data.message || "Product saved successfully", "success");
          closeProductModal();
          fetchProducts(currentPage);
        } else {
          toast("Error: " + data.message, "error");
        }
      })
      .catch((err) => {
        console.error("❌ Save error:", err);
        toast("Network error saving product: " + err.message, "error");
      })
      .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML =
          '<i class="fa-solid fa-floppy-disk"></i> ' +
          (editId.value ? "Update Product" : "Save Product");
      });
  }

  // ==========================================
  // DELETE
  // ==========================================
  function openDeleteModal(id) {
    deleteId = id;
    const row = document.querySelector(`tr[data-id="${id}"]`);
    const name = row
      ? row.querySelector("td:first-child").textContent.trim()
      : "this product";
    deleteMsg.textContent = `Are you sure you want to delete "${name}"?`;
    openModal(deleteModal);
  }

  function closeDeleteModal() {
    closeModal(deleteModal);
    deleteId = null;
  }

  function confirmDelete() {
    if (!deleteId) return;

    deleteConfirmBtn.disabled = true;
    deleteConfirmBtn.innerHTML =
      '<i class="fa-solid fa-spinner fa-spin"></i> Deleting...';

    const payload = { id: deleteId };

    fetch(`${API}/delete_products.php`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    })
      .then((res) => res.text().then((text) => ({ res, text })))
      .then(({ res, text }) => {
        let data;
        try {
          data = JSON.parse(text);
        } catch (e) {
          throw new Error(
            `Server returned HTTP ${res.status} (non-JSON response)`,
          );
        }
        if (data.success) {
          toast("Product deleted successfully", "success");
          closeDeleteModal();
          fetchProducts(currentPage);
        } else {
          toast("Error: " + data.message, "error");
          closeDeleteModal();
        }
      })
      .catch((err) => {
        console.error("❌ Delete error:", err);
        toast("Network error deleting product: " + err.message, "error");
        closeDeleteModal();
      })
      .finally(() => {
        deleteConfirmBtn.disabled = false;
        deleteConfirmBtn.innerHTML =
          '<i class="fa-solid fa-trash"></i> Delete Product';
      });
  }

  // ==========================================
  // EXPORT
  // ==========================================
  function exportProducts() {
    if (!exportBtn) return;

    exportBtn.disabled = true;
    exportBtn.innerHTML =
      '<i class="fa-solid fa-spinner fa-spin"></i> Exporting...';

    const downloadUrl = `${API}/export_products.php?ts=${Date.now()}`;
    const link = document.createElement("a");
    link.href = downloadUrl;
    link.rel = "noopener";
    link.style.display = "none";
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    setTimeout(() => {
      exportBtn.disabled = false;
      exportBtn.innerHTML = '<i class="fa-solid fa-file-export"></i> Export';
      toast("Export started! Download should begin shortly.", "success");
    }, 1200);
  }

  // ==========================================
  // IMPORT
  // ==========================================

  /**
   * Show an error in the import error modal with a human-readable title and message.
   * Used when the server returns a structured error (error_type + message).
   */
  function openImportErrorModal(errorType, message) {
    const titleMap = {
      column_mismatch: '<i class="fa-solid fa-file-circle-xmark" style="color: var(--clr-danger);"></i> File Format Problem',
      invalid_type:    '<i class="fa-solid fa-file-circle-xmark" style="color: var(--clr-danger);"></i> Unsupported File Type',
      empty_file:      '<i class="fa-solid fa-file-circle-xmark" style="color: var(--clr-danger);"></i> Empty File',
    };

    importErrorTitle.innerHTML =
      titleMap[errorType] ||
      '<i class="fa-solid fa-triangle-exclamation" style="color: var(--clr-danger);"></i> Import Error';

    importErrorMessage.innerHTML =
      '<div style="text-align:left; background:#fff0f0; border:1px solid #ffcdd2; color:#b71c1c;' +
      'padding:12px; border-radius:6px; font-size:13px; word-break:break-all;' +
      'white-space:pre-wrap; max-height:250px; overflow-y:auto;">' +
      (message || 'No error message was captured.') + '</div>';

    openModal(importErrorModal);
  }

  function closeImportErrorModal() {
    closeModal(importErrorModal);
  }

  importErrorCloseBtn?.addEventListener("click", closeImportErrorModal);
  importErrorCancelBtn?.addEventListener("click", closeImportErrorModal);
  importErrorRetryBtn?.addEventListener("click", () => {
    closeImportErrorModal();
    openImportModal();
  });

  /** Reset and open the import modal */
  function openImportModal() {
    importForm.reset();
    importProgress.style.display  = "none";
    importResult.style.display    = "none";
    importSubmitBtn.disabled      = false;
    importSubmitBtn.innerHTML     = '<i class="fa-solid fa-upload"></i> Import Products';
    if (importFileHint) importFileHint.textContent = "";
    openModal(importModal);
  }

  function closeImportModal() {
    closeModal(importModal);
  }

  /**
   * Show a live hint below the file input describing the chosen file format.
   * Wired to the input's "change" event.
   */
  function onImportFileChange() {
    if (!importFile || !importFileHint) return;

    const file = importFile.files[0];
    if (!file) { importFileHint.textContent = ""; return; }

    const ext = file.name.split(".").pop().toLowerCase();

    if (ext === "xlsx") {
      importFileHint.innerHTML = '<i class="fa-solid fa-file-excel" style="color:#16a34a;"></i>&nbsp;Excel file selected — will be imported as XLSX';
    } else if (ext === "csv") {
      importFileHint.innerHTML = '<i class="fa-solid fa-file-csv" style="color:#0ea5e9;"></i>&nbsp;CSV file selected — will be imported as CSV';
    } else {
      importFileHint.innerHTML = '<span style="color:#dc2626;"><i class="fa-solid fa-triangle-exclamation"></i>&nbsp;Unsupported format — please choose .csv or .xlsx</span>';
    }
  }

  // Attach the hint listener
  importFile?.addEventListener("change", onImportFileChange);

  // Wire up Product Dropzone drag & drop
  const productDropzone = $("#productDropzone");
  if (productDropzone && importFile) {
    ['dragenter', 'dragover'].forEach(eventName => {
      productDropzone.addEventListener(eventName, (e) => {
        e.preventDefault();
        e.stopPropagation();
        productDropzone.style.borderColor = '#2563eb';
        productDropzone.style.background  = '#eff6ff';
      }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
      productDropzone.addEventListener(eventName, (e) => {
        e.preventDefault();
        e.stopPropagation();
        productDropzone.style.borderColor = '#cbd5e1';
        productDropzone.style.background  = '#f8fafc';
      }, false);
    });

    productDropzone.addEventListener('drop', (e) => {
      const dt = e.dataTransfer;
      const files = dt.files;
      if (files && files.length > 0) {
        importFile.files = files;
        onImportFileChange();
      }
    }, false);
  }

  /**
   * Handle the import form submit.
   *
   * Accepted formats: .csv and .xlsx
   * The form field name is set per format so the PHP endpoint can detect it:
   *   CSV  -> field "csv_file"
   *   XLSX -> field "xlsx_file"
   */
  function handleImport(e) {
    e.preventDefault();

    const file = importFile ? importFile.files[0] : null;
    if (!file) {
      toast("Please select a CSV (.csv) or Excel (.xlsx) file", "error");
      return;
    }

    const ext = file.name.split(".").pop().toLowerCase();
    if (ext !== "csv" && ext !== "xlsx") {
      toast(`Unsupported file type ".${ext}" — please upload a .csv or .xlsx file.`, "error");
      return;
    }

    importProgress.style.display = "block";
    importResult.style.display   = "none";
    importSubmitBtn.disabled     = true;
    importSubmitBtn.innerHTML    = '<i class="fa-solid fa-spinner fa-spin"></i> Importing...';

    // Animate progress bar
    let progress = 0;
    const interval = setInterval(() => {
      progress += Math.random() * 10;
      if (progress > 90) progress = 90;
      progressFill.style.width = progress + "%";
    }, 200);

    // Build FormData with the correct field name for the server to identify the format
    const formData = new FormData();
    if (ext === "xlsx") {
      formData.append("xlsx_file", file);   // XLSX field
    } else {
      formData.append("csv_file", file);    // CSV field
    }
    formData.append("file", file);           // generic fallback

    /**
     * POST to the import endpoint with one automatic retry if an
     * InfinityFree/iFastNet bot-protection challenge intercepts the request.
     */
    const sendRequest = async (isRetry = false) => {
      const res  = await fetch(`${API}/import_products.php`, {
        method: "POST",
        credentials: "same-origin",
        body: formData,
      });

      let text = await res.text();
      text = text.trim().replace(/^\uFEFF/, ""); // strip BOM

      let data;
      try {
        data = JSON.parse(text);
      } catch (parseErr) {
        // Detect InfinityFree security challenge response
        if (text.includes("aes.js") || text.includes("__test=")) {
          if (!isRetry) {
            toast("Authenticating with server security system...", "info");
            await fetch(window.location.href, { credentials: "same-origin" }).catch(() => {});
            await new Promise((r) => setTimeout(r, 800));
            return sendRequest(true);
          }
          throw new Error(
            "InfinityFree Security System (iFastNet Bot Protection) blocked this request. " +
            "Please refresh the page (F5) once and try again."
          );
        }
        const preview = text ? text.substring(0, 300) : "EMPTY RESPONSE (0 BYTES)";
        throw new Error("Server returned a non-JSON response:\n\n" + preview);
      }

      return data;
    };

    sendRequest()
      .then((data) => {
        clearInterval(interval);
        progressFill.style.width = "100%";

        setTimeout(() => {
          if (data.success) {
            progressStatus.textContent = "✅ Import completed!";
            importResult.style.display = "block";

            let html = `
              <div class="import-result">
                <p class="success"><i class="fa-solid fa-check-circle"></i> ${data.message}</p>
                <div class="summary">
                  <div class="stat">
                    <div class="num">${data.details.imported}</div>
                    <div class="lbl">New Products</div>
                  </div>
                  <div class="stat">
                    <div class="num">${data.details.updated}</div>
                    <div class="lbl">Updated</div>
                  </div>
                  <div class="stat">
                    <div class="num">${data.details.errors.length}</div>
                    <div class="lbl">Errors</div>
                  </div>
                </div>`;

            if (data.details.errors.length > 0) {
              html += `<div class="errors">
                <p><strong>Errors:</strong></p>
                <ul>${data.details.errors.map((err) => `<li>${err}</li>`).join("")}</ul>
              </div>`;
            }

            html += "</div>";
            importResult.innerHTML = html;

            setTimeout(() => {
              closeImportModal();
              fetchProducts(currentPage);
              toast("Import completed successfully!", "success");
            }, 1500);

          } else {
            // Structured error from the server
            console.error(
              "Import failed [" + (data.error_type || "general") + "]:",
              data.technical_message || data.message,
            );
            closeImportModal();
            openImportErrorModal(data.error_type, data.message);
            importSubmitBtn.disabled = false;
            importSubmitBtn.innerHTML = '<i class="fa-solid fa-upload"></i> Import Products';
          }
        }, 500);
      })
      .catch((err) => {
        clearInterval(interval);
        console.error("Import error:", err);
        closeImportModal();
        openImportErrorModal("debug", "NETWORK / RESPONSE ERROR:\n" + (err.message || err));
        importSubmitBtn.disabled = false;
        importSubmitBtn.innerHTML = '<i class="fa-solid fa-upload"></i> Import Products';
      });
  }

  // ==========================================
  // DELETE ALL PRODUCTS
  // ==========================================
  function openDeleteAllModal() {
    if (!deleteAllModal) return;
    deleteAllPassword.value = "";
    deleteAllFeedback.style.display = "none";
    deleteAllFeedback.textContent = "";
    deleteAllConfirmBtn.disabled = false;
    deleteAllConfirmBtn.innerHTML =
      '<i class="fa-solid fa-trash"></i> Delete All Products';
    openModal(deleteAllModal);
    deleteAllPassword.focus();
  }

  function closeDeleteAllModal() {
    if (!deleteAllModal) return;
    closeModal(deleteAllModal);
    deleteAllPassword.value = "";
    deleteAllFeedback.style.display = "none";
    deleteAllFeedback.textContent = "";
  }

  function confirmDeleteAll() {
    if (!deleteAllModal) return;

    const password = deleteAllPassword.value.trim();
    if (!password) {
      deleteAllFeedback.style.display = "block";
      deleteAllFeedback.className = "text-danger";
      deleteAllFeedback.textContent = "Please enter your admin password.";
      return;
    }

    deleteAllConfirmBtn.disabled = true;
    deleteAllConfirmBtn.innerHTML =
      '<i class="fa-solid fa-spinner fa-spin"></i> Deleting...';

    fetch(`${API}/delete_all_products.php`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ password }),
    })
      .then((res) => res.text().then((text) => ({ res, text })))
      .then(({ res, text }) => {
        let data;
        try {
          data = JSON.parse(text);
        } catch (e) {
          throw new Error(
            `Server returned HTTP ${res.status} (non-JSON response)`,
          );
        }

        if (data.success) {
          deleteAllFeedback.style.display = "block";
          deleteAllFeedback.className = "text-success";
          deleteAllFeedback.textContent =
            data.message || "All products deleted successfully.";
          toast(
            data.message || "All products deleted successfully.",
            "success",
          );
          setTimeout(() => {
            closeDeleteAllModal();
            fetchProducts(1);
          }, 800);
        } else {
          deleteAllFeedback.style.display = "block";
          deleteAllFeedback.className = "text-danger";
          deleteAllFeedback.textContent =
            data.message || "Unable to delete all products.";
          toast(data.message || "Unable to delete all products.", "error");
        }
      })
      .catch((err) => {
        deleteAllFeedback.style.display = "block";
        deleteAllFeedback.className = "text-danger";
        deleteAllFeedback.textContent =
          "Unable to delete all products right now.";
        toast("Network error deleting products: " + err.message, "error");
      })
      .finally(() => {
        deleteAllConfirmBtn.disabled = false;
        deleteAllConfirmBtn.innerHTML =
          '<i class="fa-solid fa-trash"></i> Delete All Products';
      });
  }

  // ==========================================
  // TYPE MANAGEMENT - MODALS
  // ==========================================

  // Add type modal overlay dynamically
  const typeModalHTML = `
        <div id="typeModal" class="modal-overlay">
            <div class="modal-box modal-box-sm">
                <div class="modal-head">
                    <h2 id="typeModalTitle"><i class="fa-solid fa-tag"></i> Add Product Type</h2>
                    <button class="modal-close" id="typeModalCloseBtn"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <form id="typeForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="typeName">Type Name <span class="req">*</span></label>
                            <input type="text" id="typeName" name="name" required minlength="2" maxlength="100" placeholder="Enter type name">
                        </div>
                    </div>
                    <div class="modal-foot">
                        <button type="button" class="btn btn-cancel" id="typeModalCancelBtn">Cancel</button>
                        <button type="submit" class="btn btn-save" id="typeSubmitBtn">
                            <i class="fa-solid fa-floppy-disk"></i> Save Type
                        </button>
                    </div>
                </form>
            </div>
        </div>
    `;

  document.body.insertAdjacentHTML("beforeend", typeModalHTML);

  const typeModal = document.getElementById("typeModal");
  const typeModalTitle = document.getElementById("typeModalTitle");
  const typeForm = document.getElementById("typeForm");
  const typeName = document.getElementById("typeName");
  const typeSubmitBtn = document.getElementById("typeSubmitBtn");
  const typeModalCloseBtn = document.getElementById("typeModalCloseBtn");
  const typeModalCancelBtn = document.getElementById("typeModalCancelBtn");

  let deleteTypeId = null;
  let isDeletingType = false;

  // ==========================================
  // TYPE MANAGEMENT - FUNCTIONS
  // ==========================================

  function openTypeModal(editData = null) {
    if (editData) {
      typeModalTitle.innerHTML =
        '<i class="fa-solid fa-pen"></i> Edit Product Type';
      typeName.value = editData.name;
      typeSubmitBtn.innerHTML =
        '<i class="fa-solid fa-floppy-disk"></i> Update Type';
      typeForm.dataset.editId = editData.id;
    } else {
      typeModalTitle.innerHTML =
        '<i class="fa-solid fa-tag"></i> Add Product Type';
      typeName.value = "";
      typeSubmitBtn.innerHTML =
        '<i class="fa-solid fa-floppy-disk"></i> Save Type';
      delete typeForm.dataset.editId;
    }
    openModal(typeModal);
  }

  function closeTypeModal() {
    closeModal(typeModal);
    typeForm.reset();
    delete typeForm.dataset.editId;
  }

  function saveProductType(e) {
    e.preventDefault();

    const name = typeName.value.trim();
    if (!name) {
      toast("Please enter a type name", "error");
      typeName.focus();
      return;
    }

    const editId = typeForm.dataset.editId;
    const endpoint = editId
      ? `${API}/update_product_type.php`
      : `${API}/add_product_type.php`;

    typeSubmitBtn.disabled = true;
    typeSubmitBtn.innerHTML =
      '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';

    const formData = new FormData();
    formData.append("name", name);
    if (editId) formData.append("id", editId);

    fetch(endpoint, {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          toast(data.message, "success");
          closeTypeModal();
          loadFilters();
          fetchProducts(currentPage);
        } else {
          toast("Error: " + data.message, "error");
        }
      })
      .catch((err) => {
        console.error("❌ Save type error:", err);
        toast("Network error: " + err.message, "error");
      })
      .finally(() => {
        typeSubmitBtn.disabled = false;
        typeSubmitBtn.innerHTML =
          '<i class="fa-solid fa-floppy-disk"></i> ' +
          (editId ? "Update Type" : "Save Type");
      });
  }

  function confirmDeleteType() {
    if (!deleteTypeId || isDeletingType) return;

    isDeletingType = true;
    deleteConfirmBtn.innerHTML =
      '<i class="fa-solid fa-spinner fa-spin"></i> Deleting...';
    deleteConfirmBtn.disabled = true;

    fetch(`${API}/delete_product_type.php`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id: deleteTypeId }),
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          toast(data.message, "success");
          closeDeleteModal();
          deleteTypeId = null;
          loadFilters();
          fetchProducts(currentPage);
        } else {
          toast("Error: " + data.message, "error");
          closeDeleteModal();
          deleteTypeId = null;
        }
      })
      .catch((err) => {
        console.error("❌ Delete type error:", err);
        toast("Network error: " + err.message, "error");
        closeDeleteModal();
        deleteTypeId = null;
      })
      .finally(() => {
        isDeletingType = false;
        deleteConfirmBtn.disabled = false;
        deleteConfirmBtn.innerHTML = '<i class="fa-solid fa-trash"></i> Delete';
      });
  }

  // Override delete confirm to handle type deletion
  const originalConfirmDelete = confirmDelete;
  confirmDelete = function () {
    if (deleteTypeId) {
      confirmDeleteType();
    } else {
      originalConfirmDelete();
    }
  };

  // Override closeDeleteModal to reset type flag
  const originalCloseDeleteModal = closeDeleteModal;
  closeDeleteModal = function () {
    originalCloseDeleteModal();
    deleteTypeId = null;
    isDeletingType = false;
  };

  // ==========================================
  // TYPE MANAGEMENT - UI BUTTONS
  // ==========================================

  function addTypeManagementButtons() {
    console.log("🔧 Adding type management buttons...");

    // === 1. Add button next to the filter dropdown ===
    const filterBar = document.querySelector(".filter-bar");
    if (!filterBar) {
      console.warn("Filter bar not found");
      return;
    }

    // Find the type filter wrapper
    const typeFilterWrapper = typeFilter.parentElement;
    if (!typeFilterWrapper) {
      console.warn("Type filter wrapper not found");
      return;
    }

    // Remove existing manage button if present
    const existingBtn = typeFilterWrapper.querySelector(".manage-types-btn");
    if (existingBtn) existingBtn.remove();

    // Create a wrapper div for the filter + button
    let filterGroup = typeFilterWrapper.querySelector(".filter-group-with-btn");
    if (!filterGroup) {
      filterGroup = document.createElement("div");
      filterGroup.className = "filter-group-with-btn";
      filterGroup.style.cssText =
        "display:flex;align-items:center;gap:6px;flex:1;min-width:200px;";

      // Move the typeFilter into the wrapper
      const parent = typeFilter.parentElement;
      parent.insertBefore(filterGroup, typeFilter);
      filterGroup.appendChild(typeFilter);
    }

    // Add the manage button
    const manageBtn = document.createElement("button");
    manageBtn.className = "manage-types-btn";
    manageBtn.type = "button";
    manageBtn.innerHTML = '<i class="fa-solid fa-cog"></i>';
    manageBtn.title = "Manage Product Types";
    manageBtn.style.cssText = `
            padding: 6px 8px !important;
            font-size: 14px !important;
            border-radius: 6px !important;
            border: 1px solid #ccc !important;
            background: white !important;
            cursor: pointer !important;
            color: #555 !important;
            transition: all 0.2s !important;
            line-height: 1 !important;
            height: 38px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 36px !important;
        `;

    manageBtn.addEventListener("mouseenter", function () {
      this.style.background = "#2C3E50";
      this.style.color = "white";
      this.style.borderColor = "#2C3E50";
    });
    manageBtn.addEventListener("mouseleave", function () {
      this.style.background = "white";
      this.style.color = "#555";
      this.style.borderColor = "#ccc";
    });

    manageBtn.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      openTypeManager();
    });

    // Remove old button if exists and append new one
    const oldBtn = filterGroup.querySelector(".manage-types-btn");
    if (oldBtn) oldBtn.remove();
    filterGroup.appendChild(manageBtn);

    console.log("✅ Filter type button added");

    // === 2. Add buttons in the modal type dropdown ===
    const modalTypeWrapper = productType.parentElement;
    if (!modalTypeWrapper) {
      console.warn("Modal type wrapper not found");
      return;
    }

    // Remove existing buttons
    const existingModalBtns =
      modalTypeWrapper.querySelectorAll(".type-manage-btn");
    existingModalBtns.forEach((btn) => btn.remove());

    // Create button group
    const btnGroup = document.createElement("div");
    btnGroup.className = "type-manage-buttons";
    btnGroup.style.cssText =
      "display:flex;gap:6px;margin-top:6px;flex-wrap:wrap;";

    // Add Type button
    const addTypeBtn = document.createElement("button");
    addTypeBtn.className = "type-manage-btn btn-success";
    addTypeBtn.type = "button";
    addTypeBtn.innerHTML = '<i class="fa-solid fa-plus"></i> New Type';
    addTypeBtn.style.cssText = `
            padding: 4px 10px !important;
            font-size: 12px !important;
            border: none !important;
            border-radius: 4px !important;
            cursor: pointer !important;
            transition: all 0.2s !important;
            line-height: 1.5 !important;
            background: #28a745 !important;
            color: white !important;
        `;

    addTypeBtn.addEventListener("mouseenter", function () {
      this.style.background = "#218838";
    });
    addTypeBtn.addEventListener("mouseleave", function () {
      this.style.background = "#28a745";
    });

    addTypeBtn.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      openTypeModal(null);
    });

    // Delete Type button
    const deleteTypeBtn = document.createElement("button");
    deleteTypeBtn.className = "type-manage-btn btn-danger";
    deleteTypeBtn.type = "button";
    deleteTypeBtn.innerHTML = '<i class="fa-solid fa-trash"></i> Delete';
    deleteTypeBtn.style.cssText = `
            padding: 4px 10px !important;
            font-size: 12px !important;
            border: none !important;
            border-radius: 4px !important;
            cursor: pointer !important;
            transition: all 0.2s !important;
            line-height: 1.5 !important;
            background: #dc3545 !important;
            color: white !important;
        `;

    deleteTypeBtn.addEventListener("mouseenter", function () {
      this.style.background = "#c82333";
    });
    deleteTypeBtn.addEventListener("mouseleave", function () {
      this.style.background = "#dc3545";
    });

    deleteTypeBtn.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      const selectedValue = productType.value;
      if (!selectedValue) {
        toast("Please select a type to delete", "error");
        return;
      }
      deleteTypeId = parseInt(selectedValue);
      const typeName =
        productType.options[productType.selectedIndex]?.textContent ||
        "this type";
      deleteMsg.textContent = `Are you sure you want to delete "${typeName}"? This action cannot be undone.`;
      openModal(deleteModal);
    });

    btnGroup.appendChild(addTypeBtn);
    btnGroup.appendChild(deleteTypeBtn);
    modalTypeWrapper.appendChild(btnGroup);

    console.log("✅ Modal type buttons added");
  }

  // ==========================================
  // TYPE MANAGEMENT - MANAGER MODAL
  // ==========================================

  function openTypeManager() {
    const existingManager = document.getElementById("typeManagerModal");
    if (existingManager) existingManager.remove();

    const managerHTML = `
            <div id="typeManagerModal" class="modal-overlay show" style="display:flex;">
                <div class="modal-box modal-box-md">
                    <div class="modal-head">
                        <h2><i class="fa-solid fa-tags"></i> Manage Product Types</h2>
                        <button class="modal-close" id="typeManagerCloseBtn"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <div class="modal-body">
                        <div style="margin-bottom:16px;">
                            <button class="btn btn-primary" id="typeManagerAddBtn">
                                <i class="fa-solid fa-plus"></i> Add New Type
                            </button>
                        </div>
                        <div id="typeListContainer">
                            <div class="tbl-loading">
                                <i class="fa-solid fa-spinner fa-spin"></i> Loading types...
                            </div>
                        </div>
                    </div>
                    <div class="modal-foot">
                        <button type="button" class="btn btn-cancel" id="typeManagerCloseBtn2">Close</button>
                    </div>
                </div>
            </div>
        `;

    document.body.insertAdjacentHTML("beforeend", managerHTML);
    const managerModal = document.getElementById("typeManagerModal");

    document
      .getElementById("typeManagerCloseBtn")
      .addEventListener("click", () => {
        managerModal.remove();
      });
    document
      .getElementById("typeManagerCloseBtn2")
      .addEventListener("click", () => {
        managerModal.remove();
      });
    managerModal.addEventListener("click", (e) => {
      if (e.target === managerModal) managerModal.remove();
    });

    document
      .getElementById("typeManagerAddBtn")
      .addEventListener("click", () => {
        managerModal.remove();
        openTypeModal(null);
      });

    loadTypesForManager();
  }

  function loadTypesForManager() {
    const container = document.getElementById("typeListContainer");

    fetch(`${API}/get_product_types.php`)
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          let html = `
                        <table class="tbl">
                            <thead>
                                <tr>
                                    <th>Type Name</th>
                                    <th>Products</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

          if (data.data.length === 0) {
            html += `
                            <tr>
                                <td colspan="4" style="text-align:center;padding:40px; border: solid red 1px;">
                                    <i class="fa-regular fa-tag" style="font-size:24px;color:#aaa;"></i>
                                    <p style="margin-top:8px;">No product types found</p>
                                </td>
                            </tr>
                        `;
          } else {
            // Get product counts for each type
            const typePromises = data.data.map((type) => {
              return fetch(
                `${API}/get_products.php?product_type_id=${type.id}&limit=0`,
              )
                .then((res) => res.json())
                .then((data) => ({
                  ...type,
                  count: data.pagination?.total || 0,
                }));
            });

            Promise.all(typePromises).then((typesWithCount) => {
              typesWithCount.forEach((type) => {
                html += `
                                    <tr>
                                        <td><strong>${escape(type.name)}</strong></td>
                                        <td>${type.count}</td>
                                        <td><span class="badge-status ${type.status === "active" ? "in-stock" : "out-of-stock"}">${type.status || "active"}</span></td>
                                        <td>
                                            <div class="tbl-actions" style="gap:4px;">
                                                <button class="btn-action edit" onclick="window.editTypeFromManager(${type.id}, '${escape(type.name)}')">
                                                    <i class="fa-solid fa-pen"></i>
                                                </button>
                                                <button class="btn-action delete" onclick="window.deleteTypeFromManager(${type.id})" ${type.count > 0 ? 'disabled title="Has products"' : ""}>
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                `;
              });

              container.innerHTML = html;
            });
          }
        } else {
          container.innerHTML = `<div class="error-state">${data.message}</div>`;
        }
      })
      .catch((err) => {
        container.innerHTML = `<div class="error-state">Network error: ${err.message}</div>`;
      });
  }

  // Global functions for type manager
  window.editTypeFromManager = function (id, name) {
    document.getElementById("typeManagerModal")?.remove();
    openTypeModal({ id, name });
  };

  window.deleteTypeFromManager = function (id) {
    document.getElementById("typeManagerModal")?.remove();
    deleteTypeId = id;
    // Check if type has products
    fetch(`${API}/get_products.php?product_type_id=${id}&limit=0`)
      .then((res) => res.json())
      .then((data) => {
        const count = data.pagination?.total || 0;
        if (count > 0) {
          toast(
            `Cannot delete: This type has ${count} product(s) associated with it`,
            "error",
          );
          deleteTypeId = null;
          return;
        }
        const typeName =
          document.querySelector(`#typeFilter option[value="${id}"]`)
            ?.textContent || "this type";
        deleteMsg.textContent = `Are you sure you want to delete "${typeName}"?`;
        openModal(deleteModal);
      })
      .catch((err) => {
        toast("Error checking product count: " + err.message, "error");
        deleteTypeId = null;
      });
  };

  // ==========================================
  // TYPE MODAL EVENT LISTENERS
  // ==========================================

  typeModalCloseBtn.addEventListener("click", closeTypeModal);
  typeModalCancelBtn.addEventListener("click", closeTypeModal);
  typeModal.addEventListener("click", function (e) {
    if (e.target === this) closeTypeModal();
  });
  typeForm.addEventListener("submit", saveProductType);

  // ==========================================
  // EVENT LISTENERS
  // ==========================================

  let searchTimeout;
  searchField.addEventListener("input", function () {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => fetchProducts(1), 400);
  });

  typeFilter.addEventListener("change", () => fetchProducts(1));
  stockFilter.addEventListener("change", () => fetchProducts(1));

  addBtn.addEventListener("click", openAddModal);
  if (deleteAllBtn) {
    deleteAllBtn.addEventListener("click", openDeleteAllModal);
  }

  modalCloseBtn.addEventListener("click", closeProductModal);
  modalCancelBtn.addEventListener("click", closeProductModal);
  productModal.addEventListener("click", function (e) {
    if (e.target === this) closeProductModal();
  });
  productForm.addEventListener("submit", saveProduct);

  addMatBtn.addEventListener("click", addMatRow);

  deleteCloseBtn.addEventListener("click", closeDeleteModal);
  deleteCancelBtn.addEventListener("click", closeDeleteModal);
  deleteModal.addEventListener("click", function (e) {
    if (e.target === this) closeDeleteModal();
  });
  deleteConfirmBtn.addEventListener("click", confirmDelete);

  if (deleteAllCloseBtn)
    deleteAllCloseBtn.addEventListener("click", closeDeleteAllModal);
  if (deleteAllCancelBtn)
    deleteAllCancelBtn.addEventListener("click", closeDeleteAllModal);
  if (deleteAllModal) {
    deleteAllModal.addEventListener("click", function (e) {
      if (e.target === this) closeDeleteAllModal();
    });
  }
  if (deleteAllConfirmBtn)
    deleteAllConfirmBtn.addEventListener("click", confirmDeleteAll);

  importBtn.addEventListener("click", openImportModal);
  importCloseBtn.addEventListener("click", closeImportModal);
  importCancelBtn.addEventListener("click", closeImportModal);
  importModal.addEventListener("click", function (e) {
    if (e.target === this) closeImportModal();
  });
  importForm.addEventListener("submit", handleImport);

  exportBtn.addEventListener("click", exportProducts);

  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
      if (deleteModal.classList.contains("show")) closeDeleteModal();
      if (deleteAllModal && deleteAllModal.classList.contains("show"))
        closeDeleteAllModal();
      if (productModal.classList.contains("show")) closeProductModal();
      if (importModal.classList.contains("show")) closeImportModal();
    }
  });

  // ==========================================
  // INIT
  // ==========================================

  console.log("🚀 Initializing...");
  loadFilters();
  fetchProducts(1);
});
