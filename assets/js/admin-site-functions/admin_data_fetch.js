document.addEventListener("DOMContentLoaded", () => {
  const btn = document.getElementById("userMenuBtn");
  const menu = document.getElementById("dropdownMenu");
  const modal = document.getElementById("accountModal");
  const toggle = document.getElementById("theme-toggle");
  const openAccountModal = document.getElementById("openAccountModal");
  const currentPasswordInput = document.getElementById("currentPasswordInput");
  const passwordInput = document.getElementById("passwordInput");
  const toggleCurrentPassword = document.getElementById(
    "toggleCurrentPassword",
  );
  const togglePassword = document.getElementById("togglePassword");
  const profileInput = document.getElementById("profileInput");
  const profilePreview = document.getElementById("profilePreview");
  const saveProfileBtn = document.getElementById("saveProfileBtn");
  const messageBox = document.getElementById("accountModalMessage");

  const API_BASE = "../../api/admin_site/";

  const showMessage = (element, message, type = "error") => {
    if (!element) return;
    element.textContent = message;
    element.classList.remove("success", "error", "show");
    element.classList.add(type, "show");
    if (type === "success") {
      setTimeout(() => element.classList.remove("show"), 6000);
    }
  };

  const clearMessage = (element) => {
    if (!element) return;
    element.textContent = "";
    element.classList.remove("success", "error", "show");
  };

  const validatePassword = (password) => {
    if (!password) return true;
    const passwordRules = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
    return passwordRules.test(password);
  };

  const populateAdminData = (data) => {
    const adminID = document.getElementById("adminID");
    const adminName = document.getElementById("adminName");
    const adminEmail = document.getElementById("adminEmail");
    const createdAt = document.getElementById("createdAt");
    const userName = document.getElementById("userName");
    const userRole = document.getElementById("userRole");
    const userAvatar = document.getElementById("userAvatar");

    if (adminID) adminID.value = data.AdminID || "";
    if (adminName) adminName.value = data.FullName || "";
    if (adminEmail) adminEmail.value = data.Email || "";
    if (createdAt) createdAt.value = data.CreatedAt || "";
    if (userName) userName.textContent = data.FullName || "Username";
    if (userRole) userRole.textContent = data.Role || "Super Admin";
    if (userAvatar)
      userAvatar.src = data.ProfilePicture
        ? "../../" + data.ProfilePicture
        : "../../uploads/admins/default-avatar.png";
  };

  const fetchAdminData = () => {
    fetch(API_BASE + "fetch_admin_data.php")
      .then((res) => res.json())
      .then((res) => {
        if (res.status === "success") {
          populateAdminData(res.data);
        } else {
          showMessage(messageBox, res.message, "error");
        }
      })
      .catch((err) => {
        console.error("Error fetching admin data:", err);
        showMessage(
          messageBox,
          "Unable to load admin data. Please refresh.",
          "error",
        );
      });
  };

  if (btn && menu) {
    btn.addEventListener("click", () => {
      menu.classList.toggle("active");
    });

    document.addEventListener("click", (e) => {
      if (!btn.contains(e.target) && !menu.contains(e.target)) {
        menu.classList.remove("active");
      }
    });
  }

  if (toggle) {
    if (localStorage.getItem("theme") === "dark") {
      document.body.classList.add("dark-mode");
      toggle.checked = true;
    }

    toggle.addEventListener("change", () => {
      document.body.classList.toggle("dark-mode");
      localStorage.setItem("theme", toggle.checked ? "dark" : "light");
    });
  }

  if (openAccountModal && modal) {
    openAccountModal.addEventListener("click", () => {
      modal.classList.add("show");
      clearMessage(messageBox);
      if (currentPasswordInput) currentPasswordInput.value = "";
      if (passwordInput) passwordInput.value = "";
      fetchAdminData();
    });
  }

  window.closeModal = () => {
    if (modal) modal.classList.remove("show");
  };

  if (toggleCurrentPassword && currentPasswordInput) {
    toggleCurrentPassword.addEventListener("click", () => {
      const type =
        currentPasswordInput.getAttribute("type") === "password"
          ? "text"
          : "password";
      currentPasswordInput.setAttribute("type", type);
    });
  }

  if (togglePassword && passwordInput) {
    togglePassword.addEventListener("click", () => {
      const type =
        passwordInput.getAttribute("type") === "password" ? "text" : "password";
      passwordInput.setAttribute("type", type);
    });
  }

  if (profileInput && profilePreview) {
    profileInput.addEventListener("change", (e) => {
      const file = e.target.files[0];
      if (file) profilePreview.src = URL.createObjectURL(file);
    });
  }

  if (saveProfileBtn) {
    saveProfileBtn.addEventListener("click", () => {
      const adminID = document.getElementById("adminID").value.trim();
      const adminName = document.getElementById("adminName").value.trim();
      const adminEmail = document.getElementById("adminEmail").value.trim();
      const currentPassword = document
        .getElementById("currentPasswordInput")
        .value.trim();
      const password = document.getElementById("passwordInput").value;

      clearMessage(messageBox);

      if (!adminName || !adminEmail) {
        showMessage(messageBox, "Username and email are required.", "error");
        return;
      }

      if (!currentPassword) {
        showMessage(
          messageBox,
          "Please enter your current password to confirm changes.",
          "error",
        );
        return;
      }

      if (!validatePassword(password)) {
        showMessage(
          messageBox,
          "Password must have at least 8 characters, one uppercase letter, one lowercase letter, and one number.",
          "error",
        );
        return;
      }

      const formData = new FormData();
      formData.append("AdminID", adminID);
      formData.append("FullName", adminName);
      formData.append("Email", adminEmail);
      formData.append("CurrentPassword", currentPassword);
      formData.append("Password", password);
      if (profileInput && profileInput.files[0]) {
        formData.append("ProfilePicture", profileInput.files[0]);
      }

      fetch(API_BASE + "update_admin_profile.php", {
        method: "POST",
        body: formData,
      })
        .then((res) => res.json())
        .then((res) => {
          if (res.status === "success") {
            showMessage(messageBox, "Profile updated successfully!", "success");
            fetchAdminData();
            setTimeout(() => closeModal(), 1000);
          } else {
            showMessage(
              messageBox,
              res.message || "Unable to update profile.",
              "error",
            );
          }
        })
        .catch((err) => {
          console.error("Error updating profile:", err);
          showMessage(
            messageBox,
            "Update failed. Check your connection and try again.",
            "error",
          );
        });
    });
  }

  fetchAdminData();
});
//

//This file is responsible for handling the fetching and updating of the admin's profile data.
