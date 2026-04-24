document.addEventListener("DOMContentLoaded", () => {
  fetchLogs();
});

function fetchLogs() {
  fetch("../../api/admin_site/fetch_activity_logs.php") // make sure this path is correct
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        renderLogs(data.data);
      } else {
        console.error("API Error:", data.message);
      }
    })
    .catch((error) => console.error("Fetch Error:", error));
}

function renderLogs(logs) {
  const tableBody = document.querySelector(".activity-table tbody");

  if (!tableBody) {
    console.error("Table body not found!");
    return;
  }

  tableBody.innerHTML = "";

  if (!logs || logs.length === 0) {
    tableBody.innerHTML = `<tr><td colspan="4">No activity logs found.</td></tr>`;
    return;
  }

  logs.forEach((log) => {
    const statusClass = log.Status === "Success" ? "success" : "error";

    const formattedDate = formatDate(log.CreatedAt);

    const profileImage = log.ProfilePicture
      ? `../../${log.ProfilePicture}` // make sure this path points to your uploads folder
      : "../../assets/images/default-avatar.png";

    const row = `
      <tr>
        <td>
          <div class="user-info">
            <div class="avatar">
              <img src="${profileImage}" alt="Profile">
            </div>
            <span>${log.UserName}</span>
          </div>
        </td>
        <td>
          <span class="action-text">${formatAction(log.ActionDetails, log.ReferenceID)}</span>
        </td>
        <td>
          <span class="timestamp">${formattedDate}</span>
        </td>
        <td>
          <span class="badge ${statusClass}">${log.Status}</span>
        </td>
      </tr>
    `;

    tableBody.innerHTML += row;
  });
}

function formatDate(dateString) {
  const date = new Date(dateString);
  return date
    .toLocaleString("en-US", {
      month: "short",
      day: "2-digit",
      year: "numeric",
      hour: "2-digit",
      minute: "2-digit",
      hour12: true,
    })
    .replace(",", " •")
    .replace("AM", "A.M.")
    .replace("PM", "P.M.");
}

function formatAction(action, ref) {
  if (!ref) return action;
  return action.replace(ref, `<strong class="ref">${ref}</strong>`);
}
