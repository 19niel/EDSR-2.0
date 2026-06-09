document.addEventListener("DOMContentLoaded", function () {
  const productEntries = document.getElementById("productEntries");
  const addProductEntry = document.getElementById("addProductEntry");

  // Add a new product entry
  addProductEntry.addEventListener("click", function () {
    let newEntry = document.querySelector(".product-entry").cloneNode(true);

    // Clear input values in the cloned entry
    newEntry.querySelectorAll("input, select").forEach((el) => {
      if (el.tagName === "SELECT") {
        el.selectedIndex = 0;
      } else {
        el.value = "";
      }
    });

    // Append the cloned entry
    productEntries.appendChild(newEntry);
  });

  // Remove a product entry
  productEntries.addEventListener("click", function (e) {
    if (e.target.classList.contains("remove-entry")) {
      if (document.querySelectorAll(".product-entry").length > 1) {
        e.target.closest(".product-entry").remove();
      } else {
        alert("At least one product entry is required.");
      }
    }
  });
});
