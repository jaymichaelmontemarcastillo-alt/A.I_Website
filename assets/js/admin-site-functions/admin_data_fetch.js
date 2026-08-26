document.addEventListener("DOMContentLoaded", () => {
  const btn = document.getElementById("userMenuBtn");
  const menu = document.getElementById("dropdownMenu");
  const modal = document.getElementById("accountModal");
  const toggle = document.getElementById("theme-toggle");
  const openAccountModal = document.getElementById("openAccountModal");
  const currentPasswordInput = document.getElementById("currentPasswordInput");

  const THEME_KEY = "theme";
  const LEGACY_THEME_KEY = "theme_preference";

  const getSavedTheme = () =>
    localStorage.getItem(THEME_KEY) || localStorage.getItem(LEGACY_THEME_KEY);

  const setSavedTheme = (value) => {
    localStorage.setItem(THEME_KEY, value);
    localStorage.setItem(LEGACY_THEME_KEY, value);
  };

  const applyThemeMode = (isDark) => {
    document.body.classList.toggle("dark-mode", isDark);
    document.documentElement.classList.toggle("dark-mode", isDark);
    document.documentElement.setAttribute("data-theme", isDark ? "dark" : "light");
    if (toggle) toggle.checked = isDark;
  };
  const passwordInput = document.getElementById("passwordInput");
  const toggleCurrentPassword = document.getElementById(
    "toggleCurrentPassword",
  );
  const togglePassword = document.getElementById("togglePassword");
  const profileInput = document.getElementById("profileInput");
  const profilePreview = document.getElementById("profilePreview");
  const saveProfileBtn = document.getElementById("saveProfileBtn");
  const messageBox = document.getElementById("accountModalMessage");

  // Email Notification Settings modal elements
  const openEmailSettingsModal = document.getElementById(
    "openEmailSettingsModal",
  );
  const emailSettingsModal = document.getElementById("emailSettingsModal");
  const gmailAddressInput = document.getElementById("gmailAddressInput");
  const appPasswordInput = document.getElementById("appPasswordInput");
  const toggleAppPassword = document.getElementById("toggleAppPassword");
  const appPasswordStatus = document.getElementById("appPasswordStatus");
  const emailCurrentPasswordInput = document.getElementById(
    "emailCurrentPasswordInput",
  );
  const saveEmailSettingsBtn = document.getElementById("saveEmailSettingsBtn");
  const emailSettingsMessage = document.getElementById("emailSettingsMessage");

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
    const savedTheme = getSavedTheme();
    const isDark = savedTheme === "dark";
    applyThemeMode(isDark);

    toggle.addEventListener("change", () => {
      const activeDark = toggle.checked;
      applyThemeMode(activeDark);
      setSavedTheme(activeDark ? "dark" : "light");
    });
  }

  window.addEventListener("storage", (e) => {
    if (e.key === THEME_KEY || e.key === LEGACY_THEME_KEY) {
      const isDark = e.newValue === "dark";
      applyThemeMode(isDark);
    }
  });

  window.addEventListener("pageshow", (e) => {
    if (e.persisted) {
      const isDark = getSavedTheme() === "dark";
      applyThemeMode(isDark);
    }
  });

  if (openAccountModal && modal) {
    openAccountModal.addEventListener("click", () => {
      modal.classList.add("show");
      clearMessage(messageBox);
      if (currentPasswordInput) currentPasswordInput.value = "";
      if (passwordInput) passwordInput.value = "";
      fetchAdminData();
      if (menu) menu.classList.remove("active");
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

  // ------------------------------------------------------------------
  // Email Notification Settings (shared Gmail address + app password
  // used by send_reset_code.php to send outgoing mail via PHPMailer)
  // ------------------------------------------------------------------

  const fetchEmailSettings = () => {
    if (appPasswordStatus)
      appPasswordStatus.textContent = "Checking current status…";

    fetch(API_BASE + "fetch_email_settings.php")
      .then((res) => res.json())
      .then((res) => {
        if (res.status === "success") {
          if (gmailAddressInput)
            gmailAddressInput.value = res.data.GmailAddress || "";
          if (appPasswordStatus) {
            appPasswordStatus.textContent = res.data.HasAppPassword
              ? "An app password is already set. Leave the field blank to keep it, or enter a new one to replace it."
              : "No app password has been set yet. Enter one below to enable outgoing email.";
          }
        } else {
          showMessage(emailSettingsMessage, res.message, "error");
        }
      })
      .catch((err) => {
        console.error("Error fetching email settings:", err);
        showMessage(
          emailSettingsMessage,
          "Unable to load email settings. Please refresh.",
          "error",
        );
      });
  };

  if (openEmailSettingsModal && emailSettingsModal) {
    openEmailSettingsModal.addEventListener("click", () => {
      emailSettingsModal.classList.add("show");
      clearMessage(emailSettingsMessage);
      if (appPasswordInput) appPasswordInput.value = "";
      if (emailCurrentPasswordInput) emailCurrentPasswordInput.value = "";
      fetchEmailSettings();
      if (menu) menu.classList.remove("active");
    });
  }

  window.closeEmailSettingsModal = () => {
    if (emailSettingsModal) emailSettingsModal.classList.remove("show");
  };

  if (toggleAppPassword && appPasswordInput) {
    toggleAppPassword.addEventListener("click", () => {
      const type =
        appPasswordInput.getAttribute("type") === "password"
          ? "text"
          : "password";
      appPasswordInput.setAttribute("type", type);
    });
  }

  if (saveEmailSettingsBtn) {
    saveEmailSettingsBtn.addEventListener("click", () => {
      const gmailAddress = gmailAddressInput
        ? gmailAddressInput.value.trim()
        : "";
      const appPassword = appPasswordInput ? appPasswordInput.value.trim() : "";
      const currentPassword = emailCurrentPasswordInput
        ? emailCurrentPasswordInput.value.trim()
        : "";

      clearMessage(emailSettingsMessage);

      if (!gmailAddress) {
        showMessage(
          emailSettingsMessage,
          "Gmail address is required.",
          "error",
        );
        return;
      }

      if (!currentPassword) {
        showMessage(
          emailSettingsMessage,
          "Please enter your admin password to confirm changes.",
          "error",
        );
        return;
      }

      saveEmailSettingsBtn.disabled = true;

      fetch(API_BASE + "update_email_settings.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          GmailAddress: gmailAddress,
          AppPassword: appPassword,
          CurrentPassword: currentPassword,
        }),
      })
        .then((res) => res.json())
        .then((res) => {
          if (res.status === "success") {
            showMessage(
              emailSettingsMessage,
              "Email settings updated successfully!",
              "success",
            );
            if (appPasswordInput) appPasswordInput.value = "";
            if (emailCurrentPasswordInput) emailCurrentPasswordInput.value = "";
            fetchEmailSettings();
            setTimeout(() => closeEmailSettingsModal(), 1000);
          } else {
            showMessage(
              emailSettingsMessage,
              res.message || "Unable to update email settings.",
              "error",
            );
          }
        })
        .catch((err) => {
          console.error("Error updating email settings:", err);
          showMessage(
            emailSettingsMessage,
            "Update failed. Check your connection and try again.",
            "error",
          );
        })
        .finally(() => {
          saveEmailSettingsBtn.disabled = false;
        });
    });
  }

  fetchAdminData();
});
//

//This file is responsible for handling the fetching and updating of the admin's profile data,
//and the shared Gmail/app-password used for outgoing system emails.
