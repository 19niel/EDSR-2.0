document.addEventListener("DOMContentLoaded", function () {
  var myModal = new bootstrap.Modal("#updateGraphModal");
  function fetchData() {
    var callDateStart = document.getElementById("callDateStart").value;
    var callDateEnd = document.getElementById("callDateEnd").value;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../php/graphData.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var requestData =
      "callDateStart=" +
      encodeURIComponent(callDateStart) +
      "&callDateEnd=" +
      encodeURIComponent(callDateEnd);
    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4) {
        if (xhr.status == 200) {
          try {
            var responseData = JSON.parse(xhr.responseText);

            // Check if responseData has the expected structure
            if (Array.isArray(responseData) && responseData.length > 0) {
              // Extract data from each object in the array
              var labels = responseData.map(function (item) {
                return item.callDate;
              });

              var barData = responseData.map(function (item) {
                return parseInt(item.rowCount, 10); // Assuming rowCount is a string
              });

              var lineData = responseData.map(function (item) {
                return parseInt(item.statusCount, 10); // Assuming statusCount is a string
              });

              var totalCallCount = responseData.reduce(function (sum, item) {
                return sum + parseInt(item.rowCount, 10);
              }, 0);

              var totalActualCount = responseData.reduce(function (sum, item) {
                return sum + parseInt(item.actualCount, 10);
              }, 0);

              // Calculate the sum of all closeCount values
              var totalCloseCount = responseData.reduce(function (sum, item) {
                return sum + parseInt(item.closeCount, 10);
              }, 0);

              // Calculate the conversion rate
              console.log("Total Actual Count:", totalActualCount);
              console.log("Total Close Count:", totalCloseCount);

              var conversionRate =
                totalCloseCount > 0
                  ? (totalCloseCount / totalActualCount) * 100
                  : 0;
              console.log("Conversion Rate:", conversionRate);

              // Update chart data
              barLineChart.data.labels = labels;
              barLineChart.data.datasets[0].data = barData;
              barLineChart.data.datasets[1].data = lineData;

              document.getElementById("callCountSpan").innerHTML =
                totalCallCount;
              document.getElementById("actualCountSpan").innerHTML =
                totalActualCount;
              document.getElementById("actualClosedCountSpan").innerHTML =
                totalCloseCount;
              document.getElementById("conversionSpan").innerHTML =
                conversionRate.toFixed(2) + "%";

              console.log(totalCallCount);

              myModal.hide();
              barLineChart.update();
            } else {
              console.error("Invalid response format:", responseData);
            }
          } catch (error) {
            console.error("Error parsing response:", error);
          }
        } else {
          console.error("Error fetching data. Status:", xhr.status);
        }
      }
    };

    if (callDateStart && callDateEnd) {
      xhr.send(requestData);
    } else {
      xhr.send();
    }
  }

  // Add an event listener to the form
  var updateGraphForm = document.getElementById("updateGraphForm");
  updateGraphForm.addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent the default form submission
    fetchData(); // Call the fetchData function to update the chart
  });

  // Sample data for the initial chart
  var chartData = {
    labels: ["Label 1", "Label 2", "Label 3", "Label 4", "Label 5"],
    datasets: [
      {
        label: "Calls",
        backgroundColor: "rgba(75, 192, 192, 0.2)",
        borderColor: "rgba(75, 192, 192, 1)",
        borderWidth: 1,
        data: [5, 10, 15, 7, 20],
        type: "bar",
      },
      {
        label: "Closed Call",
        borderColor: "rgba(255, 99, 132, 1)",
        borderWidth: 2,
        fill: false,
        data: [10, 5, 8, 15, 12],
        type: "line",
      },
    ],
  };

  var ctx = document.getElementById("barLineChart").getContext("2d");
  var barLineChart = new Chart(ctx, {
    type: "bar",
    data: chartData,
    options: {
      responsive: true, // Enable responsiveness
      maintainAspectRatio: false, // Allow the chart to stretch its height
      scales: {
        y: {
          beginAtZero: true,
        },
      },
    },
  });

  // Fetch data initially
  fetchData();
});
