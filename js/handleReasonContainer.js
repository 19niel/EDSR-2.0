// handleReasonContainer.js
// This script shows or hides the reason container based on the account status selection.
var accountStatusDropdown = document.getElementById("accountStatus");
var reasonContainer = document.getElementById("reasonContainer");

accountStatusDropdown.addEventListener("change", function () {
  var selectedValue = accountStatusDropdown.value.toLowerCase();

  if (selectedValue === "lost" || selectedValue === "dropped") {
    reasonContainer.style.display = "block";
  } else {
    reasonContainer.style.display = "none";
  }
});
