function openModal() {
  const modal = document.getElementById("productModal");
  modal.classList.add("show");

  document.getElementById("productForm").reset();
  document.getElementById("previewImg").src = "https://via.placeholder.com/100";
}
function closeModal() {
  document.getElementById("productModal").classList.remove("show");
}
// CLOSE WHEN CLICK OUTSIDE
window.onclick = function (e) {
  let modal = document.getElementById("productModal");
  if (e.target === modal) {
    modal.classList.remove("show");
  }
};
function openModal() {
  const modal = document.getElementById("productModal");

  modal.classList.add("show");

  document.getElementById("modalTitle").innerText = "Add Product";
  document.getElementById("productForm").reset();

  document.getElementById("previewImg").src = "https://via.placeholder.com/100";
}

function openEditModal(product) {
  const modal = document.getElementById("productModal");

  modal.classList.add("show");

  document.getElementById("modalTitle").innerText = "Edit Product";

  document.getElementById("productId").value = product.id;
  document.getElementById("productName").value = product.name;
  document.getElementById("productCategory").value = product.category;
  document.getElementById("productPrice").value = product.price;
  document.getElementById("productStock").value = product.stock;

  document.getElementById("previewImg").src = "../../" + product.image;
}
document
  .getElementById("productImage")
  .addEventListener("change", function (e) {
    const file = e.target.files[0];

    if (file) {
      const reader = new FileReader();

      reader.onload = function (e) {
        document.getElementById("previewImg").src = e.target.result;
      };

      reader.readAsDataURL(file);
    }
  });
