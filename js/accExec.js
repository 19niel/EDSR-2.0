document.addEventListener("DOMContentLoaded", function () {
  // Get the accountExecutiveContainer element
  var accountExecutiveContainer = document.getElementById(
    "accountExecutiveContainer"
  );

  // Function to populate input or dropdown based on user category
  function populateAccountExecutiveField(
    category,
    userName,
    userNames,
    userNamesAdmin
  ) {
    // Check if the category is "User"
    if (category === "User") {
      // Create an input field
      var inputField = document.createElement("input");
      inputField.type = "text";
      inputField.className = "form-control";
      inputField.id = "accountExecutive";
      inputField.name = "accountExecutive";
      inputField.value = userName;
      inputField.readOnly = true;

      // Append the input field to the container
      accountExecutiveContainer.innerHTML = ""; // Clear the container
      accountExecutiveContainer.appendChild(inputField);
    } else if (category === "Manager") {
      // Create a dropdown (select) element
      var dropdown = document.createElement("select");
      dropdown.className = "form-control";
      dropdown.id = "accountExecutive";
      dropdown.name = "accountExecutive";

      // Create and append options to the dropdown
      userNames.forEach(function (user) {
        var option = document.createElement("option");
        option.value = user.name; // Adjust based on your user data structure
        option.text = user.name; // Adjust based on your user data structure
        dropdown.appendChild(option);
      });

      // Append the dropdown to the container
      accountExecutiveContainer.innerHTML = ""; // Clear the container
      accountExecutiveContainer.appendChild(dropdown);
    } else if (category === "Admin") {
      // Create a dropdown (select) element
      var dropdown = document.createElement("select");
      dropdown.className = "form-control";
      dropdown.id = "accountExecutive";
      dropdown.name = "accountExecutive";

      // Create and append options to the dropdown
      userNamesAdmin.forEach(function (user) {
        var option = document.createElement("option");
        option.value = user.name; // Adjust based on your user data structure
        option.text = user.name; // Adjust based on your user data structure
        dropdown.appendChild(option);
      });

      // Append the dropdown to the container
      accountExecutiveContainer.innerHTML = ""; // Clear the container
      accountExecutiveContainer.appendChild(dropdown);
    }
  }

  // Call the function to populate the field on page load
  populateAccountExecutiveField(
    userCategory,
    userName,
    userArrayManager,
    userArrayAdmin
  );
});
