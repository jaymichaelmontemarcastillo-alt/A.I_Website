/**
 * product-actions.js
 * Shared add-to-cart / add-to-wishlist helpers used by index.php, shop.php,
 * and product.php.
 *
 * Key contract:
 *   - Always dispatches 'cartUpdated' or 'wishlistUpdated' on the document
 *     after a successful API call.  BadgeManager listens for these and
 *     re-fetches the real count from the server — so the payload is irrelevant.
 *
 * Place at: assets/js/customer-site-functions/product-actions.js
 */

/* ------------------------------------------------------------------ */
/*  TOAST                                                               */
/* ------------------------------------------------------------------ */
function showProductToast(message, isError = false) {
  let toast = document.getElementById("toast");
  if (!toast) {
    // Create one on the fly if the page hasn't included the markup
    toast = document.createElement("div");
    toast.id = "toast";
    toast.className = "toast";
    toast.innerHTML =
      '<i class="fa-solid fa-check-circle"></i><span id="toastMessage"></span>';
    document.body.appendChild(toast);
  }

  const msg = toast.querySelector("#toastMessage") || toast;
  const icon = toast.querySelector("i");

  if (msg.tagName) msg.textContent = message;
  toast.style.backgroundColor = isError ? "#ff4444" : "#4CAF50";
  if (icon)
    icon.className = isError
      ? "fa-solid fa-exclamation-circle"
      : "fa-solid fa-check-circle";

  toast.classList.add("show");
  clearTimeout(toast._hideTimer);
  toast._hideTimer = setTimeout(() => toast.classList.remove("show"), 3000);
}

/* Alias so existing inline calls still work */
window.showToast = showProductToast;

/* ------------------------------------------------------------------ */
/*  ADD TO CART  (card grid version — single qty)                      */
/* ------------------------------------------------------------------ */
window.addToCart = function (productId, productsMap) {
  const button = document.getElementById("addToCartBtn_" + productId);
  if (!button) return;

  const originalHTML = button.innerHTML;
  button.innerHTML = '<i class="fa-solid fa-spinner"></i>';
  button.disabled = true;

  // productsMap can be passed directly or resolved from window.productsMap
  const map = productsMap || window.productsMap || {};
  const product = map[productId];

  if (!product) {
    showProductToast("Product not found!", true);
    button.innerHTML = originalHTML;
    button.disabled = false;
    return;
  }

  fetch((window.__baseDir || "") + "api/add_to_cart.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      id: productId,
      name: product.name,
      price: product.price,
      category: product.category,
      image: product.image,
      quantity: 1,
    }),
  })
    .then((r) => r.json())
    .then((data) => {
      if (data.success) {
        showProductToast("✓ Added to cart!");
        // Fire event — BadgeManager will re-fetch the real count
        document.dispatchEvent(new CustomEvent("cartUpdated"));
      } else {
        showProductToast(
          "Error: " + (data.error || "Failed to add to cart"),
          true,
        );
      }
    })
    .catch(() => showProductToast("Failed to add to cart", true))
    .finally(() => {
      button.innerHTML = originalHTML;
      button.disabled = false;
    });
};

/* ------------------------------------------------------------------ */
/*  ADD TO WISHLIST  (card grid version)                               */
/* ------------------------------------------------------------------ */
window.addToWishlist = function (event, productId, productsMap) {
  if (event && event.stopPropagation) event.stopPropagation();

  const button = document.getElementById("wishlistBtn_" + productId);
  if (!button) return;

  const originalHTML = button.innerHTML;
  button.innerHTML = '<i class="fa-solid fa-spinner"></i>';
  button.disabled = true;

  const map = productsMap || window.productsMap || {};
  const product = map[productId];

  if (!product) {
    showProductToast("Product not found!", true);
    button.innerHTML = originalHTML;
    button.disabled = false;
    return;
  }

  fetch((window.__baseDir || "") + "api/add_to_wishlist.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      id: productId,
      name: product.name,
      price: product.price,
      category: product.category,
      image: product.image,
      description: product.description,
    }),
  })
    .then((r) => r.json())
    .then((data) => {
      if (data.success) {
        showProductToast("❤️ Added to wishlist!");
        _markWishlistBtn(button, true);
        // Fire event — BadgeManager will re-fetch the real count
        document.dispatchEvent(new CustomEvent("wishlistUpdated"));
      } else if (data.already_exists) {
        showProductToast("Already in wishlist", false);
        _markWishlistBtn(button, true);
      } else {
        showProductToast("Error: " + (data.error || "Failed"), true);
        button.innerHTML = originalHTML;
        button.disabled = false;
      }
    })
    .catch(() => {
      showProductToast("Failed to add to wishlist", true);
      button.innerHTML = originalHTML;
      button.disabled = false;
    })
    .finally(() => {
      button.disabled = false;
    });
};

function _markWishlistBtn(button, active) {
  if (active) {
    button.classList.add("in-wishlist");
    button.innerHTML = '<i class="fa-solid fa-heart"></i>';
  } else {
    button.classList.remove("in-wishlist");
    button.innerHTML = '<i class="fa-regular fa-heart"></i>';
  }
}

/* ------------------------------------------------------------------ */
/*  CHECK WISHLIST STATUS ON PAGE LOAD                                  */
/* ------------------------------------------------------------------ */
window.initWishlistStatus = function (productIds) {
  if (!productIds || !productIds.length) return;

  productIds.forEach((id) => {
    fetch((window.__baseDir || "") + "api/check_wishlist.php?id=" + id, {
      cache: "no-store",
    })
      .then((r) => r.json())
      .then((data) => {
        if (data.success && data.in_wishlist) {
          const btn = document.getElementById("wishlistBtn_" + id);
          if (btn) _markWishlistBtn(btn, true);
        }
      })
      .catch(() => {});
  });
};
