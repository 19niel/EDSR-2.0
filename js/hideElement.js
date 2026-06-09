document.addEventListener("DOMContentLoaded", function () {
  if (category === "User") {
    hideElements();
    setTimeout(hideElements, 1000);
  }
  if (category === "Manager" || category === "User") {
    hideManagerTimestamp();
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
  var accountExecutiveSearch = document.getElementById(
    "accountExecutiveSearch"
  );
  var accountExecutiveLabelSearch = document.getElementById(
    "accountExecutiveLabelSearch"
  );
  var action = document.getElementById("action");
  var adminButtons = document.getElementsByClassName("adminButton");
  var editButtons = document.getElementsByClassName("editButton");
  var deleteButtons = document.getElementsByClassName("deleteButton");

  if (uploadButton) {
    uploadButton.style.display = "none";
  }
  if (quickAccess) {
    quickAccess.style.display = "none";
  }
  if (performanceTab) {
    performanceTab.style.display = "none";
  }
  if (users) {
    users.style.display = "none";
  }
  if (leave) {
    leave.style.display = "none";
  }
  if (customize) {
    customize.style.display = "none";
  }
  if (accountExecutiveSearch) {
    accountExecutiveSearch.style.display = "none";
  }
  if (accountExecutiveLabelSearch) {
    accountExecutiveLabelSearch.style.display = "none";
  }
  if (action) {
    action.style.display = "none";
  }
  hideElementsWithClass(adminButtons);
  hideElementsWithClass(editButtons);
  hideElementsWithClass(deleteButtons);

  // Similarly, you can hide other elements or perform other actions as needed
  // Add more lines of code for other elements if necessary
}

function hideElementsWithClass(elements) {
  for (var i = 0; i < elements.length; i++) {
    elements[i].style.display = "none";
  }
}
