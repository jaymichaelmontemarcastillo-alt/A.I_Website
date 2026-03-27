document.addEventListener("DOMContentLoaded", function () {
  const notification = document.getElementById("topNotification");

  if (notification && notification.textContent.trim() !== "") {
    // Show notification
    setTimeout(() => {
      notification.classList.add("show");
    }, 100);

    // Hide after 4 seconds
    setTimeout(() => {
      notification.classList.remove("show");
    }, 4000);
  }
});
