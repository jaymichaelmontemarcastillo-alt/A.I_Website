// LIVE SEARCH FILTER - Works on pages that have .gift-card elements
// Supports both mobile (#searchInput) and desktop (#searchInputDesktop) search inputs
document.addEventListener("DOMContentLoaded", function () {
  const mobileSearchInput = document.getElementById("searchInput");
  const desktopSearchInput = document.getElementById("searchInputDesktop");
  const searchInputs = [mobileSearchInput, desktopSearchInput].filter(
    (input) => input !== null,
  );

  if (searchInputs.length === 0) return;

  // Function to perform search filtering
  function performSearch(searchValue) {
    const products = document.querySelectorAll(".gift-card");

    if (products.length === 0) return;

    products.forEach((product) => {
      const name = product.dataset.name
        ? product.dataset.name.toLowerCase()
        : "";
      const category = product.dataset.category
        ? product.dataset.category.toLowerCase()
        : "";
      const description = product.dataset.description
        ? product.dataset.description.toLowerCase()
        : "";

      const matches =
        name.includes(searchValue) ||
        category.includes(searchValue) ||
        description.includes(searchValue);

      product.style.display = matches ? "block" : "none";
    });
  }

  // Add event listeners to all available search inputs
  searchInputs.forEach((searchInput) => {
    searchInput.addEventListener("keyup", function () {
      const searchValue = this.value.toLowerCase();

      // Sync both search inputs
      searchInputs.forEach((input) => {
        if (input.value !== this.value) {
          input.value = this.value;
        }
      });

      performSearch(searchValue);
    });
  });
});
