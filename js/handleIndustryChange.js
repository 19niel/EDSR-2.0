// handleIndustryChange.js
// This script handles the change event for the industry dropdown and fetches the corresponding subcategories.
$(document).ready(function () {
  $("#industrySubcategory").prop("disabled", true);

  $("#segment").on("change", function () {
    var industryId = $(this).val();

    if (industryId) {
      $("#industrySubcategory")
        .prop("disabled", false)
        .html('<option value="N/A" disabled selected>Loading...</option>');

      $.ajax({
        url: "../php/subcategoryList.php",
        type: "POST",
        data: { industry_id: industryId },
        success: function (data) {
          if (data) {
            $("#industrySubcategory").html(data);
          } else {
            $("#industrySubcategory")
              .prop("disabled", true)
              .html(
                '<option value="N/A" disabled selected>No subcategories available</option>'
              );
          }
        },
      });
    } else {
      $("#industrySubcategory")
        .prop("disabled", true)
        .html('<option value="N/A" disabled selected>Choose...</option>');
    }
  });
});
