document.addEventListener("DOMContentLoaded", function () {
  const passwordInput = document.getElementById("passwordInput");
  const confirmPasswordInput = document.getElementById("confirmPasswordInput");
  const signupBtn = document.querySelector(".sign-up-btn");
  const passwordWarning = document.getElementById("passwordWarning");
  const confirmPasswordWarning = document.getElementById(
    "confirmPasswordWarning",
  );

  // Disable button initially
  signupBtn.disabled = true;

  function validatePassword() {
    const password = passwordInput.value;
    let message = "";

    // Check length
    if (password.length < 8) {
      message = "Password must be at least 8 characters.";
    }
    // Check for number
    else if (!/\d/.test(password)) {
      message = "Password must include at least 1 number.";
    }
    // Check for special character
    else if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
      message = "Password must include at least 1 special character.";
    } else {
      message = "Password looks good!";
    }

    // Update UI
    if (message === "Password looks good!") {
      passwordWarning.textContent = message;
      passwordWarning.classList.add("valid");
    } else {
      passwordWarning.textContent = message;
      passwordWarning.classList.remove("valid");
    }
  }

  function validateConfirmPassword() {
    const password = passwordInput.value;
    const confirm = confirmPasswordInput.value;

    if (confirm === "") {
      confirmPasswordWarning.textContent = "";
      signupBtn.disabled = true;
      return;
    }

    if (password !== confirm) {
      confirmPasswordWarning.textContent = "Passwords do not match!";
      confirmPasswordWarning.classList.remove("valid");
      signupBtn.disabled = true;
    } else {
      confirmPasswordWarning.textContent = "Passwords match!";
      confirmPasswordWarning.classList.add("valid");
      // Enable button if password is strong
      if (passwordWarning.classList.contains("valid")) {
        signupBtn.disabled = false;
      }
    }
  }

  // Listen to typing
  passwordInput.addEventListener("input", () => {
    validatePassword();
    validateConfirmPassword(); // in case user edits after confirm
  });

  confirmPasswordInput.addEventListener("input", validateConfirmPassword);
});
