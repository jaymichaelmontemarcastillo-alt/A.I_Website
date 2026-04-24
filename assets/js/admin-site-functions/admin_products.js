/* ============================= */
/* 📦 ADMIN PRODUCTS MANAGEMENT  */
/*      Full AJAX — No Reloads   */
/* ============================= */

let currentEditingId = null;

/* ─── DOM refs (resolved once on load) ──────────────────────────────────── */
let productModal,
  deleteConfirmModal,
  productForm,
  productId,
  productName,
  productCategory,
  productPrice,
  productStock,
  previewImg,
  modalTitle,
  deleteMessage,
  deleteConfirmBtn,
  productTableBody,
  productCountEl;

/* ============================= */
/* 🔔 NOTIFICATION               */
/* ============================= */
function showNotification(message, type = "success") {
  const notif = document.getElementById("notification");
  if (!notif) return;

  notif.className = `notification ${type}`;
  notif.textContent = message;
  notif.style.display = "block";
  notif.style.opacity = "0";
  notif.style.transform = "translateX(20px)";

  setTimeout(() => {
    notif.style.transition = "opacity .25s, transform .25s";
    notif.style.opacity = "1";
    notif.style.transform = "translateX(0)";
  }, 30);

  clearTimeout(notif._hideTimer);
  notif._hideTimer = setTimeout(() => {
    notif.style.opacity = "0";
    notif.style.transform = "translateX(20px)";
    setTimeout(() => {
      notif.style.display = "none";
    }, 300);
  }, 2800);
}

/* ============================= */
/* 🧼 FORM VALIDATION            */
/* ============================= */
function clearErrors() {
  document.querySelectorAll(".form-error").forEach((el) => {
    el.classList.remove("show");
    el.textContent = "";
  });
}

function showFieldError(fieldId, message) {
  const el = document.getElementById(fieldId);
  if (el) {
    el.textContent = message;
    el.classList.add("show");
  }
}

function validateForm() {
  clearErrors();
  let ok = true;

  const name = productName.value.trim();
  const category = productCategory.value.trim();
  const price = parseFloat(productPrice.value);
  const stock = parseInt(productStock.value, 10);

  if (name.length < 3) {
    showFieldError("nameError", "Name must be at least 3 characters");
    ok = false;
  }
  if (category.length < 2) {
    showFieldError("categoryError", "Category must be at least 2 characters");
    ok = false;
  }
  if (isNaN(price) || price < 0) {
    showFieldError("priceError", "Invalid price");
    ok = false;
  }
  if (isNaN(stock) || stock < 0) {
    showFieldError("stockError", "Invalid stock");
    ok = false;
  }

  return ok;
}

/* ============================= */
/* 📊 PRODUCT COUNT              */
/* ============================= */
function updateProductCount(delta) {
  if (!productCountEl) return;
  const current = parseInt(productCountEl.textContent, 10) || 0;
  const next = current + delta;
  productCountEl.textContent = `${next} product${next !== 1 ? "s" : ""}`;
}

/* ============================= */
/* 🏗  DOM ROW BUILDER           */
/* ============================= */
function buildRow(product) {
  const tr = document.createElement("tr");
  tr.dataset.productId = product.id;

  const imageSrc = product.image
    ? product.image.startsWith("http")
      ? product.image
      : `../../${product.image}`
    : "https://via.placeholder.com/60?text=No+Image";

  tr.innerHTML = `
    <td><img src="${imageSrc}" class="table-img" alt="${escHtml(product.name)}"></td>
    <td>${escHtml(product.name)}</td>
    <td><span class="category">${escHtml(product.category)}</span></td>
    <td><span class="price">₱${parseFloat(product.price).toLocaleString("en-PH", { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</span></td>
    <td><span class="stock">${parseInt(product.stock, 10)}</span></td>
    <td>
      <div class="action-buttons">
        <button class="action-btn edit" type="button" title="Edit product">
          <i class="fa-solid fa-pen"></i> Edit
        </button>
        <button class="action-btn delete" type="button" title="Delete product" data-product-id="${product.id}">
          <i class="fa-solid fa-trash"></i> Delete
        </button>
      </div>
    </td>`;

  /* Attach edit handler with the full product object */
  tr.querySelector(".edit").addEventListener("click", () =>
    openProductEditModal(product),
  );
  tr.querySelector(".delete").addEventListener("click", function () {
    showDeleteConfirm(this.dataset.productId);
  });

  return tr;
}

function escHtml(str) {
  const d = document.createElement("div");
  d.appendChild(document.createTextNode(str ?? ""));
  return d.innerHTML;
}

/* ============================= */
/* 🚫 EMPTY STATE                */
/* ============================= */
function checkEmptyState() {
  const rows = productTableBody.querySelectorAll("tr[data-product-id]");
  if (rows.length === 0) {
    const emptyRow = productTableBody.querySelector(".empty-row");
    if (!emptyRow) {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td colspan="6" class="empty-row">
          <div class="empty-state">
            <i class="fa-regular fa-box-open fa-2x"></i>
            <p>No products found</p>
            <p>Click "Add Product" to create your first product.</p>
          </div>
        </td>`;
      productTableBody.appendChild(tr);
    }
  } else {
    const emptyRow = productTableBody.querySelector(".empty-row");
    if (emptyRow) emptyRow.parentElement.remove();
  }
}

/* ============================= */
/* 📦 MODAL OPEN / CLOSE         */
/* ============================= */
function openProductModal() {
  currentEditingId = null;
  productForm.reset();
  previewImg.src = "https://via.placeholder.com/300?text=No+Image";
  productId.value = "";
  modalTitle.innerHTML = '<i class="fa-solid fa-box"></i> Add Product';
  clearErrors();
  productModal.classList.add("show");
}

function openProductEditModal(product) {
  currentEditingId = product.id;
  productId.value = product.id;
  productName.value = product.name;
  productCategory.value = product.category;
  productPrice.value = product.price;
  productStock.value = product.stock;

  const imageSrc = product.image
    ? product.image.startsWith("http")
      ? product.image
      : `../../${product.image}`
    : "https://via.placeholder.com/300?text=No+Image";
  previewImg.src = imageSrc;

  modalTitle.innerHTML =
    '<i class="fa-solid fa-pen-to-square"></i> Edit Product';
  clearErrors();
  productModal.classList.add("show");
}

function closeProductModal() {
  productModal.classList.remove("show");
  productForm.reset();
  clearErrors();
}

/* ============================= */
/* 🖼  IMAGE PREVIEW             */
/* ============================= */
function setupImagePreview() {
  const imgInput = document.getElementById("productImage");
  if (!imgInput) return;
  imgInput.addEventListener("change", function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (e) => {
      previewImg.src = e.target.result;
    };
    reader.readAsDataURL(file);
  });
}

/* ============================= */
/* 🚀 AJAX FORM SUBMIT           */
/* ============================= */
function setupFormSubmission() {
  if (!productForm) return;

  productForm.addEventListener("submit", function (e) {
    e.preventDefault();
    if (!validateForm()) {
      showNotification("Please fix the errors above", "error");
      return;
    }

    const formData = new FormData(this);
    const id = productId.value;
    const endpoint = id
      ? "../../api/admin_site/products/update_products.php"
      : "../../api/admin_site/products/add_products.php";

    const submitBtn = document.getElementById("submitBtn");
    const originalHTML = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML =
      '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';

    /* ⚠️ Snapshot form values NOW — before closeProductModal() resets the form */
    const snapshot = {
      name: productName.value.trim(),
      category: productCategory.value.trim(),
      price: parseFloat(productPrice.value),
      stock: parseInt(productStock.value, 10),
    };

    fetch(endpoint, { method: "POST", body: formData })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          const action = id ? "updated" : "added";
          showNotification(
            `✓ Product "${snapshot.name}" ${action} successfully!`,
            "success",
          );
          closeProductModal(); /* safe to reset form now — we already have snapshot */

          if (id) {
            /* ── UPDATE existing row in-place ────────────────────────────── */
            const row = productTableBody.querySelector(
              `tr[data-product-id="${id}"]`,
            );
            if (row) {
              const existingImgSrc = row.querySelector(".table-img")?.src ?? "";

              const updatedProduct = {
                id,
                name: snapshot.name,
                category: snapshot.category,
                price: snapshot.price,
                stock: snapshot.stock,
                /* Server returns new image path only when a new file was uploaded */
                image: data.image ?? null,
              };

              const newRow = buildRow(updatedProduct);

              /* If no new image was uploaded, restore the existing img src directly */
              if (!data.image && existingImgSrc) {
                newRow.querySelector(".table-img").src = existingImgSrc;
              }

              row.replaceWith(newRow);
            }
          } else {
            /* ── INSERT new row at top ────────────────────────────────────── */
            if (data.id) {
              const newProduct = {
                id: data.id,
                name: snapshot.name,
                category: snapshot.category,
                price: snapshot.price,
                stock: snapshot.stock,
                image: data.image ?? "",
              };
              const newRow = buildRow(newProduct);

              /* Animate in */
              newRow.style.opacity = "0";
              newRow.style.transition = "opacity .3s";

              /* Remove empty-state row if present */
              const emptyRow = productTableBody.querySelector(".empty-row");
              if (emptyRow) emptyRow.parentElement.remove();

              productTableBody.insertBefore(
                newRow,
                productTableBody.firstChild,
              );
              requestAnimationFrame(() => {
                newRow.style.opacity = "1";
              });

              updateProductCount(1);
            }
          }
        } else {
          showNotification(data.message || "Error occurred", "error");
        }
      })
      .catch(() => showNotification("Server error. Please try again.", "error"))
      .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalHTML;
      });
  });
}

/* ============================= */
/* ❌ DELETE FLOW                */
/* ============================= */
function showDeleteConfirm(id) {
  const row = productTableBody.querySelector(`tr[data-product-id="${id}"]`);
  if (!row) return;

  const name =
    row.querySelector("td:nth-child(2)")?.textContent.trim() ?? "this product";
  deleteMessage.innerHTML = `
    <strong>Are you sure you want to delete 
    <span style="color:#dc3545;">"${escHtml(name)}"</span>?</strong>`;

  deleteConfirmModal.classList.add("show");

  /* Replace button to clear any previous listener */
  const freshBtn = deleteConfirmBtn.cloneNode(true);
  deleteConfirmBtn.parentNode.replaceChild(freshBtn, deleteConfirmBtn);
  deleteConfirmBtn = freshBtn; /* keep reference in sync */

  freshBtn.addEventListener("click", () => performDelete(id, name));
}

function performDelete(id, name) {
  const formData = new FormData();
  formData.append("id", id);

  const btn = document.getElementById("deleteConfirmBtn");
  if (btn) {
    btn.disabled = true;
    btn.textContent = "Deleting...";
  }

  fetch("../../api/admin_site/products/delete_products.php", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      closeProductDeleteModal();

      if (data.success) {
        showNotification(`✓ Product "${name}" deleted!`, "success");

        const row = productTableBody.querySelector(
          `tr[data-product-id="${id}"]`,
        );
        if (row) {
          row.style.transition = "opacity .3s, transform .3s";
          row.style.opacity = "0";
          row.style.transform = "translateX(-10px)";
          setTimeout(() => {
            row.remove();
            updateProductCount(-1);
            checkEmptyState();
          }, 300);
        }
      } else {
        showNotification(data.message || "Delete failed", "error");
      }
    })
    .catch(() => showNotification("Delete failed. Please try again.", "error"))
    .finally(() => {
      if (btn) {
        btn.disabled = false;
        btn.textContent = "Delete Product";
      }
    });
}

function closeProductDeleteModal() {
  deleteConfirmModal.classList.remove("show");
}

/* ============================= */
/* 🔍 LIVE SEARCH (optional)     */
/* Keep URL-based search working */
/* ============================= */
function setupLiveSearch() {
  const searchInput = document.querySelector(
    ".search-form input[name='search']",
  );
  const searchForm = document.querySelector(".search-form");
  if (!searchInput || !searchForm) return;

  /* Allow existing server-side search to still work.
     Optionally wire up a debounced AJAX search here if needed. */
}

/* ============================= */
/* 🧠 INIT                       */
/* ============================= */
function initProducts() {
  /* Resolve all DOM refs */
  productModal = document.getElementById("productModal");
  deleteConfirmModal = document.getElementById("deleteConfirmModal");
  productForm = document.getElementById("productForm");
  productId = document.getElementById("productId");
  productName = document.getElementById("productName");
  productCategory = document.getElementById("productCategory");
  productPrice = document.getElementById("productPrice");
  productStock = document.getElementById("productStock");
  previewImg = document.getElementById("previewImg");
  modalTitle = document.getElementById("modalTitle");
  deleteMessage = document.getElementById("deleteMessage");
  deleteConfirmBtn = document.getElementById("deleteConfirmBtn");
  productTableBody = document.getElementById("productTableBody");
  productCountEl = document.querySelector(".product-count");

  /* Re-attach delete listeners for server-rendered rows */
  productTableBody?.querySelectorAll(".delete").forEach((btn) => {
    btn.addEventListener("click", function () {
      showDeleteConfirm(this.closest("tr").dataset.productId);
    });
  });

  /* Re-attach edit listeners for server-rendered rows.
     The PHP passes data via onclick="openProductEditModal(...)" — those still work.
     We additionally set up the pattern for dynamically inserted rows via buildRow(). */

  setupImagePreview();
  setupFormSubmission();
  setupLiveSearch();
}

document.addEventListener("DOMContentLoaded", initProducts);
