// handleAccountCategoryChange.js
// This script shows or hides fields based on the account category selection.
document
  .getElementById("accountCategory")
  .addEventListener("change", function () {
    const existingSystemContainer = document.getElementById(
      "existingSystemContainer"
    );
    const contractEndCompetitorContainer = document.getElementById(
      "contractEndCompetitorContainer"
    );

    if (this.value === "NEW") {
      existingSystemContainer.style.display = "block";
      contractEndCompetitorContainer.style.display = "block";

      document.getElementById("existingSystem").required = false;
      document.getElementById("contractEndCompetitor").required = false;
    } else {
      existingSystemContainer.style.display = "none";
      contractEndCompetitorContainer.style.display = "none";

      document.getElementById("existingSystem").required = false;
      document.getElementById("existingSystem").value = "";
      document.getElementById("contractEndCompetitor").value = "";
      document.getElementById("contractEndCompetitor").required = false;
    }
  });
