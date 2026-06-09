// handleAccountStatusChange.js
// This script shows or hides fields based on the account status selection.
document
  .getElementById("accountStatus")
  .addEventListener("change", function () {
    const deliveryDateContainer = document.getElementById(
      "deliveryDateContainer"
    );
    const contractEndContainer = document.getElementById(
      "contractEndContainer"
    );

    if (this.value === "230") {
      deliveryDateContainer.style.display = "block";
      contractEndContainer.style.display = "block";
      console.log("Delivered");

      document.getElementById("deliveryDate").required = true;
      document.getElementById("contractEnd").required = true;
    } else {
      deliveryDateContainer.style.display = "none";
      contractEndContainer.style.display = "none";

      console.log("Not Delivered");

      document.getElementById("deliveryDate").required = false;
      document.getElementById("deliveryDate").value = "";
      document.getElementById("contractEnd").value = "";
      document.getElementById("contractEnd").required = false;
    }
  });

// Handle reasonSubcategory dropdown based on accountStatus
$(document).ready(function () {
  $("#reasonSubcategory").prop("disabled", true);

  $("#accountStatus").on("change", function () {
    var accountStatusId = $(this).val();

    if (accountStatusId) {
      $("#reasonSubcategory")
        .prop("disabled", false)
        .html('<option value="N/A" disabled selected>Loading...</option>');

      $.ajax({
        url: "../php/subcategoryList.php",
        type: "POST",
        data: { status_id: accountStatusId },
        success: function (data) {
          if (data) {
            $("#reasonSubcategory").html(data);
          } else {
            $("#reasonSubcategory")
              .prop("disabled", true)
              .html(
                '<option value="N/A" disabled selected>No subcategories available</option>'
              );
          }
        },
      });
    } else {
      $("#reasonSubcategory")
        .prop("disabled", true)
        .html('<option value="N/A" disabled selected>Choose...</option>');
    }
  });
});
