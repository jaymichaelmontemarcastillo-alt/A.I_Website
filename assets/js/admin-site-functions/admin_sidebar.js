document.addEventListener("DOMContentLoaded", function () {
  const toggleBtn = document.getElementById("toggle-btn");
  const wrapper = document.querySelector(".admin-wrapper");

  toggleBtn.addEventListener("click", () => {
    wrapper.classList.toggle("collapsed");

    const isCollapsed = wrapper.classList.contains("collapsed");
    localStorage.setItem("sidebar-collapsed", isCollapsed);
  });
});
