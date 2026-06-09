// handleProductTypeChange.js
// This script handles the change event for the product type dropdown and fetches the corresponding subcategories.
$(document).ready(function () {
  // Delegate change event for dynamically added elements
  $(document).on("change", ".productType", function () {
    var productTypeId = $(this).val();
    var subcategoryDropdown = $(this)
      .closest(".product-entry")
      .find(".productTypeSubcategory");

    if (productTypeId) {
      subcategoryDropdown
        .prop("disabled", false)
        .html('<option value="N/A" disabled selected>Loading...</option>');

      $.ajax({
        url: "../php/subcategoryList.php",
        type: "POST",
        data: { category_id: productTypeId },
        success: function (data) {
          if (data) {
            subcategoryDropdown.html(data);
          } else {
            subcategoryDropdown
              .prop("disabled", true)
              .html(
                '<option value="N/A" disabled selected>No subcategories available</option>'
              );
          }
        },
      });
    } else {
      subcategoryDropdown
        .prop("disabled", true)
        .html('<option value="N/A" disabled selected>Choose...</option>');
    }
  });
});
