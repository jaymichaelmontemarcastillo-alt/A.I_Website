/**
 * admin_products.js - WITH FULL DEBUGGING
 */

document.addEventListener("DOMContentLoaded", function () {
  // ==========================================
  // API PATH - TRY THESE OPTIONS
  // ==========================================
  // Option 1: Relative from admin page
  const API = "../../api/admin_site/products";
  // Option 2: Absolute from root
  // const API = "/api/admin_site/products";
  // Option 3: Full URL (for testing)
  // const API = "http://localhost/api/admin_site/products";

  console.log("=== ADMIN PRODUCTS DEBUG ===");
  console.log("API Path:", API);
  console.log("Current URL:", window.location.href);
  console.log("Pathname:", window.location.pathname);

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
  const importModal = $("#importModal");
  const importForm = $("#importForm");
  const excelFile = $("#excelFile");
  const importSubmitBtn = $("#importSubmitBtn");
  const importCloseBtn = $("#importCloseBtn");
  const importCancelBtn = $("#importCancelBtn");
  const importProgress = $("#importProgress");
  const progressFill = $("#progressFill");
  const progressStatus = $("#progressStatus");
  const importResult = $("#importResult");

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
  // TEST CONNECTION FIRST
  // ==========================================
  function testConnection() {
    console.log("🔍 Testing API connection...");
    console.log("📡 URL:", `${API}/get_products.php?limit=1`);

    fetch(`${API}/get_products.php?limit=1`)
      .then((response) => {
        console.log("📥 Response status:", response.status);
        console.log("📥 Response statusText:", response.statusText);
        console.log("📥 Response headers:", [...response.headers.entries()]);
        return response.text(); // Get raw text first
      })
      .then((text) => {
        console.log("📄 Raw response:", text);
        try {
          const data = JSON.parse(text);
          console.log("✅ Parsed JSON:", data);
          if (data.success) {
            console.log("✅ Connection successful!");
            toast("Connected to API successfully", "success");
          } else {
            console.error("❌ API returned error:", data.message);
            toast("API Error: " + data.message, "error");
          }
        } catch (e) {
          console.error("❌ Failed to parse JSON:", e);
          console.error("Raw response was:", text);
          toast("Invalid JSON response from server", "error");
        }
      })
      .catch((error) => {
        console.error("❌ NETWORK ERROR:", error);
        console.error("Error details:", {
          message: error.message,
          stack: error.stack,
          type: error.constructor.name,
        });
        toast("Network error: " + error.message, "error");
      });
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

    console.log("📡 Fetching products from:", url);

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
        console.log("📥 Products response status:", res.status);
        if (!res.ok) {
          throw new Error(`HTTP ${res.status}: ${res.statusText}`);
        }
        return res.json();
      })
      .then((data) => {
        isLoading = false;
        console.log("📄 Products data:", data);
        if (data.success) {
          renderTable(data.data);
          renderPagination(data.pagination);
          updateCount(data.pagination);
          currentPage = page;
        } else {
          console.error("❌ API error:", data.message);
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
        console.error("❌ FETCH ERROR:", err);
        console.error("Error details:", {
          message: err.message,
          stack: err.stack,
        });
        tableBody.innerHTML = `
                    <tr><td colspan="6">
                        <div class="error-state">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            Network error: ${err.message}
                            <br><small>Check console for details</small>
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
                            <span class="sub">Try adjusting your filters or add a new product</span>
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
                    <td><strong>${escape(p.name)}</strong></td>
                    <td><span class="badge-type">${escape(p.product_type_name || "N/A")}</span></td>
                    <td class="tbl-price">₱${parseFloat(p.price).toFixed(2)}</td>
                    <td class="tbl-stock">${p.stock || 0}</td>
                    <td>${statusMap[status] || statusMap.in_stock}</td>
                    <td>
                        <div class="tbl-actions">
                            <button class="btn-action edit" data-id="${p.id}">
                                <i class="fa-solid fa-pen"></i> Edit
                            </button>
                            <button class="btn-action delete" data-id="${p.id}">
                                <i class="fa-solid fa-trash"></i> Delete
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
    console.log("📡 Loading filters from:", `${API}/get_products.php?limit=0`);

    fetch(`${API}/get_products.php?limit=0`)
      .then((res) => {
        console.log("📥 Filters response status:", res.status);
        return res.json();
      })
      .then((data) => {
        console.log("📄 Filters data:", data);
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

          console.log("✅ Filters loaded successfully");
        } else {
          console.error("❌ Failed to load filters:", data.message);
        }
      })
      .catch((err) => {
        console.error("❌ Filter load error:", err);
      });

    // Load materials
    console.log("📡 Loading materials from:", `${API}/get_materials.php`);
    fetch(`${API}/get_materials.php`)
      .then((res) => {
        console.log("📥 Materials response status:", res.status);
        return res.json();
      })
      .then((data) => {
        console.log("📄 Materials data:", data);
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
    console.log("📡 Loading product for edit:", id);
    modalTitle.innerHTML = '<i class="fa-solid fa-pen"></i> Edit Product';
    submitBtn.innerHTML =
      '<i class="fa-solid fa-floppy-disk"></i> Update Product';

    fetch(`${API}/get_product.php?id=${id}`)
      .then((res) => {
        console.log("📥 Get product response status:", res.status);
        return res.json();
      })
      .then((data) => {
        console.log("📄 Product data:", data);
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

    console.log("📡 Saving product to:", endpoint);
    console.log("📤 Form data:", Object.fromEntries(formData));

    fetch(endpoint, {
      method: "POST",
      body: formData,
    })
      .then((res) => {
        console.log("📥 Save response status:", res.status);
        return res.text().then((text) => ({ res, text }));
      })
      .then(({ res, text }) => {
        let data;
        try {
          data = JSON.parse(text);
        } catch (e) {
          console.error("❌ Non-JSON save response:", text);
          throw new Error(
            `Server returned HTTP ${res.status} (non-JSON response). Check that ${endpoint} exists and has no PHP errors.`,
          );
        }
        console.log("📄 Save response:", data);
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
    console.log("📡 Deleting product:", payload);
    console.log("📡 URL:", `${API}/delete_products.php`);

    fetch(`${API}/delete_products.php`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    })
      .then((res) => {
        console.log("📥 Delete response status:", res.status);
        return res.text().then((text) => ({ res, text }));
      })
      .then(({ res, text }) => {
        let data;
        try {
          data = JSON.parse(text);
        } catch (e) {
          console.error("❌ Non-JSON delete response:", text);
          throw new Error(
            `Server returned HTTP ${res.status} (non-JSON response). Check that delete_products.php exists and has no PHP errors.`,
          );
        }
        console.log("📄 Delete response:", data);
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
    exportBtn.disabled = true;
    exportBtn.innerHTML =
      '<i class="fa-solid fa-spinner fa-spin"></i> Exporting...';

    const iframe = document.createElement("iframe");
    iframe.style.display = "none";
    iframe.src = `${API}/export_products.php`;
    document.body.appendChild(iframe);

    setTimeout(() => {
      exportBtn.disabled = false;
      exportBtn.innerHTML = '<i class="fa-solid fa-file-export"></i> Export';
      document.body.removeChild(iframe);
      toast("Export started! Download should begin shortly.", "success");
    }, 3000);
  }

  // ==========================================
  // IMPORT
  // ==========================================
  function openImportModal() {
    importForm.reset();
    importProgress.style.display = "none";
    importResult.style.display = "none";
    importSubmitBtn.disabled = false;
    importSubmitBtn.innerHTML =
      '<i class="fa-solid fa-upload"></i> Import Products';
    openModal(importModal);
  }

  function closeImportModal() {
    closeModal(importModal);
  }

  function handleImport(e) {
    e.preventDefault();

    const file = excelFile.files[0];
    if (!file) {
      toast("Please select an Excel file", "error");
      return;
    }

    const ext = file.name.split(".").pop().toLowerCase();
    if (!["xlsx", "xls"].includes(ext)) {
      toast("Please upload a valid Excel file (.xlsx or .xls)", "error");
      return;
    }

    importProgress.style.display = "block";
    importResult.style.display = "none";
    importSubmitBtn.disabled = true;
    importSubmitBtn.innerHTML =
      '<i class="fa-solid fa-spinner fa-spin"></i> Importing...';

    let progress = 0;
    const interval = setInterval(() => {
      progress += Math.random() * 10;
      if (progress > 90) progress = 90;
      progressFill.style.width = progress + "%";
    }, 200);

    const formData = new FormData();
    formData.append("excel_file", file);

    fetch(`${API}/import_products.php`, {
      method: "POST",
      body: formData,
    })
      .then((res) => {
        console.log("📥 Import response status:", res.status);
        return res.json();
      })
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
                                    <div class="lbl">Updated Products</div>
                                </div>
                                <div class="stat">
                                    <div class="num">${data.details.errors.length}</div>
                                    <div class="lbl">Errors</div>
                                </div>
                            </div>
                    `;

            if (data.details.errors.length > 0) {
              html += `
                            <div class="errors">
                                <p><strong>Errors:</strong></p>
                                <ul>
                                    ${data.details.errors.map((err) => `<li>${err}</li>`).join("")}
                                </ul>
                            </div>
                        `;
            }

            html += "</div>";
            importResult.innerHTML = html;

            setTimeout(() => {
              closeImportModal();
              fetchProducts(currentPage);
              toast("Import completed successfully!", "success");
            }, 1500);
          } else {
            progressStatus.textContent = "❌ " + data.message;
            importResult.style.display = "block";
            importResult.innerHTML = `
                        <div class="import-result">
                            <p class="error"><i class="fa-solid fa-circle-exclamation"></i> ${data.message}</p>
                        </div>
                    `;
            importSubmitBtn.disabled = false;
            importSubmitBtn.innerHTML =
              '<i class="fa-solid fa-upload"></i> Import Products';
          }
        }, 500);
      })
      .catch((err) => {
        clearInterval(interval);
        console.error("❌ Import error:", err);
        progressStatus.textContent = "❌ Network error during import";
        importResult.style.display = "block";
        importResult.innerHTML = `
                <div class="import-result">
                    <p class="error"><i class="fa-solid fa-circle-exclamation"></i> Network error: ${err.message}</p>
                </div>
            `;
        importSubmitBtn.disabled = false;
        importSubmitBtn.innerHTML =
          '<i class="fa-solid fa-upload"></i> Import Products';
      });
  }

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
      if (productModal.classList.contains("show")) closeProductModal();
      if (importModal.classList.contains("show")) closeImportModal();
    }
  });

  // ==========================================
  // INIT
  // ==========================================
  console.log("🚀 Initializing...");

  // Test connection first
  testConnection();

  // Then load data
  setTimeout(() => {
    loadFilters();
    fetchProducts(1);
  }, 500);
});
