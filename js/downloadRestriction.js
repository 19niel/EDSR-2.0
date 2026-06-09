document.addEventListener("DOMContentLoaded", function () {
  if (typeof isDownloadRestricted !== "undefined" && isDownloadRestricted) {
    let exportButton = document.getElementById("exportButton");
    if (exportButton) {
      exportButton.disabled = true;
    }
  }
});
