document.addEventListener("DOMContentLoaded", function () {
  // Check if global variable 'category' is defined before evaluation
  if (typeof category !== 'undefined') {
    if (category === "User") {
      hideElements();
      setTimeout(hideElements, 1000); // Dynamic DOM adjustment fallback
    }
    if (category === "Manager" || category === "User") {
      hideManagerTimestamp();
    }
  }
});

function hideManagerTimestamp() {
  var managerTimestamp = document.getElementById("managerLoginTimestamp");
  if (managerTimestamp) {
    managerTimestamp.style.display = "none";
  }
}

function hideElements() {
  var uploadButton = document.getElementById("uploadButton");
  var quickAccess = document.getElementById("quickAccess");
  var performanceTab = document.getElementById("performanceTab");
  var customize = document.getElementById("customize");
  var users = document.getElementById("users");
  var leave = document.getElementById("leave");
  var bo_dashboard = document.getElementById("bo_dashboard");
  var bo_search = document.getElementById("bo_search");

  var accountExecutiveSearch = document.getElementById("accountExecutiveSearch");
  var accountExecutiveLabelSearch = document.getElementById("accountExecutiveLabelSearch");
  var action = document.getElementById("action");

  var adminButtons = document.getElementsByClassName("adminButton");
  var editButtons = document.getElementsByClassName("editButton");
  var deleteButtons = document.getElementsByClassName("deleteButton");

  // Hiding ID elements
  if (uploadButton) uploadButton.style.display = "none";
  if (quickAccess) quickAccess.style.display = "none";
  if (performanceTab) performanceTab.style.display = "none";
  if (users) users.style.display = "none";
  if (leave) leave.style.display = "none";
  if (customize) customize.style.display = "none";
  if (bo_dashboard) bo_dashboard.style.display = "none";
  if (bo_search) bo_search.style.display = "none";
  if (accountExecutiveSearch) accountExecutiveSearch.style.display = "none";
  if (accountExecutiveLabelSearch) accountExecutiveLabelSearch.style.display = "none";
  if (action) action.style.display = "none";

  // Hiding Class collections
  hideElementsWithClass(adminButtons);
  hideElementsWithClass(editButtons);
  hideElementsWithClass(deleteButtons);
}

function hideElementsWithClass(elements) {
  for (var i = 0; i < elements.length; i++) {
    elements[i].style.display = "none";
  }
}