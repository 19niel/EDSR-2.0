// Function to initialize column visibility on page load
window.onload = function () {
  showDefaultColumns(); // Show default columns on page load
};

// Function to show the default columns (Account Name, Date of Call, Nature of Call, What Transpired)
function showDefaultColumns() {
  var table = document.getElementById("largeTable");
  var rows = table.getElementsByTagName("tr");

  // Loop through each row and show the default columns
  for (var i = 0; i < rows.length; i++) {
    var cells = rows[i].getElementsByTagName("td");
    if (cells.length > 0) {
      for (var j = 0; j < cells.length; j++) {
        // Show specific columns (Account Name, Date of Call, Nature of Call, What Transpired)
        if (j === 0 || j === 2 || j === 3 || j === 25 || j === 28) {
          cells[j].style.display = ""; // Make sure these columns are visible
        } else {
          cells[j].style.display = "none"; // Hide the other columns
        }
      }
    }
  }

  // Also apply to headers (th)
  var headers = table.getElementsByTagName("th");
  for (var k = 0; k < headers.length; k++) {
    // Show specific headers (Account Name, Date of Call, Nature of Call, What Transpired)
    if (k === 0 || k === 2 || k === 3 || k === 25 || k === 28) {
      headers[k].style.display = ""; // Make sure these columns are visible
    } else {
      headers[k].style.display = "none"; // Hide the other headers
    }
  }
}

// Function to toggle visibility of other columns based on user preference
function toggleColumns() {
  var table = document.getElementById("largeTable");
  var rows = table.getElementsByTagName("tr");

  // Loop through each row and toggle the visibility of all columns except the default ones
  for (var i = 0; i < rows.length; i++) {
    var cells = rows[i].getElementsByTagName("td");
    if (cells.length > 0) {
      for (var j = 0; j < cells.length; j++) {
        // Toggle visibility for columns that are not Account Name (0), Date of Call (2), Nature of Call (3), What Transpired (6)
        if (j !== 0 && j !== 2 && j !== 3 && j !== 25 && j !== 28) {
          cells[j].style.display =
            cells[j].style.display === "none" ? "" : "none"; // Toggle visibility
        }
      }
    }
  }

  // Also toggle the header columns
  var headers = table.getElementsByTagName("th");
  for (var k = 0; k < headers.length; k++) {
    if (k !== 0 && k !== 2 && k !== 3 && k !== 25 && k !== 28) {
      headers[k].style.display =
        headers[k].style.display === "none" ? "" : "none"; // Toggle visibility
    }
  }
}
