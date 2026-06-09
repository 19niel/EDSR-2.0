// callType.js

document.addEventListener("DOMContentLoaded", function () {
  // Function to fetch call type counts from the server
  function fetchCallTypeData() {
    var xhr = new XMLHttpRequest();
    // Adjust the path to your PHP file as needed.
    xhr.open("GET", "../php/dashboardCalls.php", true);
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          try {
            var data = JSON.parse(xhr.responseText);

            // Update the corresponding divs by ID
            document.getElementById("courtesyVisit").innerHTML =
              data.courtesyVisit || 0;
            document.getElementById("messageCall").innerHTML =
              data.messageCall || 0;
            document.getElementById("virtualMeeting").innerHTML =
              data.virtualMeeting || 0;
            document.getElementById("scheduledMeeting").innerHTML =
              data.scheduledMeeting || 0;
            document.getElementById("email").innerHTML = data.email || 0;
          } catch (e) {
            console.error("Error parsing call type data:", e);
          }
        } else {
          console.error("Error fetching call type data. Status:", xhr.status);
        }
      }
    };
    xhr.send();
  }

  // Fetch data immediately on page load
  fetchCallTypeData();

  // OPTIONAL: Set an interval to refresh the counts every so often
  // setInterval(fetchCallTypeData, 60000); // Refresh every 60 seconds
});
