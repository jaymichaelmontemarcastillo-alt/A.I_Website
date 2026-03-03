// TOGGLE SEARCH BAR
const searchIcon = document.getElementById("search_icon");
const searchBar = document.querySelector(".search-bar");
const searchInput = document.getElementById("searchInput");

searchIcon.addEventListener("click", () => {
  if (searchBar.style.display === "block") {
    searchBar.style.display = "none";
  } else {
    searchBar.style.display = "block";
    searchInput.focus();
  }
});

// LIVE SEARCH FILTER
searchInput.addEventListener("keyup", function () {
  const searchValue = this.value.toLowerCase();
  const products = document.querySelectorAll(".gift-card");

  products.forEach((product) => {
    const name = product.dataset.name;
    const category = product.dataset.category;
    const description = product.dataset.description;

    if (
      name.includes(searchValue) ||
      category.includes(searchValue) ||
      description.includes(searchValue)
    ) {
      product.style.display = "block";
    } else {
      product.style.display = "none";
    }
  });
});
