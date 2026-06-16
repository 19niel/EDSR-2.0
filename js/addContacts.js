document.addEventListener("DOMContentLoaded", function () {
  const contactEntries = document.getElementById("contactEntries");
  const addContactEntry = document.getElementById("addContactEntry");

  if (!contactEntries || !addContactEntry) return;

  // Add a new contact entry
  addContactEntry.addEventListener("click", function () {
    let newEntry = document.querySelector(".contact-entry").cloneNode(true);

    // Clear input values in the cloned entry
    newEntry.querySelectorAll("input, select").forEach((el) => {
      if (el.tagName === "SELECT") {
        el.selectedIndex = 0;
      } else {
        el.value = "";
      }
    });

    // Make the remove button visible for cloned items
    const removeBtnContainer = newEntry.querySelector(".contact-remove-container");
    if (removeBtnContainer) {
      removeBtnContainer.style.display = "block";
    }

    // Append the cloned entry
    contactEntries.appendChild(newEntry);
  });

  // Remove a contact entry
  contactEntries.addEventListener("click", function (e) {
    const removeBtn = e.target.closest(".remove-contact");
    if (removeBtn) {
      if (document.querySelectorAll(".contact-entry").length > 1) {
        removeBtn.closest(".contact-entry").remove();
      } else {
        alert("At least one contact entry is required.");
      }
    }
  });
});
