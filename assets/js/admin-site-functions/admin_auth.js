document.addEventListener("DOMContentLoaded", function () {
  const loginForm = document.getElementById("loginForm");

  if (!loginForm) return;

  loginForm.addEventListener("submit", function (e) {
    e.preventDefault();

    const email = document.getElementById("email").value.trim();
    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value.trim();

    const message = document.getElementById("loginMessage");

    if (email === "" || username === "" || password === "") {
      message.textContent = "Please fill in all fields.";
      message.style.color = "red";
      return;
    }

    /* Placeholder Authentication */
    if (
      email === "admin@giftshop.com" &&
      username === "admin" &&
      password === "admin123"
    ) {
      const signInBtn = document.querySelector(".sign-in-btn");
      const btnText = signInBtn.textContent.trim();

      // Replace icon and text with spinner
      signInBtn.innerHTML = '<div class="spinner"></div> Signing In...';
      signInBtn.disabled = true; // prevent multiple clicks

      // Redirect after delay
      setTimeout(function () {
        window.location.href = "Pages/Dashboard.php";
      }, 1500);
    } else {
      showModal("Invalid credentials.", "error");
    }
  });
});

function showModal(message, type = "info") {
  const modal = document.getElementById("authModal");
  const modalMessage = document.getElementById("modalMessage");
  const modalIcon = document.getElementById("modalIcon");

  modalMessage.textContent = message;

  if (type === "success") {
    modalIcon.innerHTML = '<i class="fa-solid fa-circle-check"></i>';
    modalIcon.style.color = "green";
  } else if (type === "error") {
    modalIcon.innerHTML = '<i class="fa-solid fa-circle-xmark"></i>';
    modalIcon.style.color = "red";
  } else {
    modalIcon.innerHTML = '<i class="fa-solid fa-circle-info"></i>';
    modalIcon.style.color = "#0d3b56";
  }

  modal.style.display = "flex";
}

/* Close modal*/
document.getElementById("modalClose").onclick = function () {
  document.getElementById("authModal").style.display = "none";
};
