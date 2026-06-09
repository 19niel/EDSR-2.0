// toggleDateFields.js
// This script toggles the date fields and sets the required attribute based on the contract type and account status.
$(document).ready(function () {
  function toggleDateFields() {
    var contractType = $("#contractType").val();
    var accountStatus = $("#accountStatus").val();

    if (contractType === "Rental" && accountStatus === "Delivered") {
      $("#contractStartDate").prop("disabled", false).prop("required", true);
      $("#contractEndDate").prop("disabled", false).prop("required", true);
    } else {
      $("#contractStartDate").prop("disabled", true).prop("required", false);
      $("#contractEndDate").prop("disabled", true).prop("required", false);
    }
  }
  toggleDateFields();

  $("#contractType, #accountStatus").change(function () {
    toggleDateFields();
  });
});
