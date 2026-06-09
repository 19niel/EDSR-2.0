document.getElementById("region").addEventListener("change", function () {
  const provinceDropdown = document.getElementById("provinceContainer");
  const barangayDropdown = document.getElementById("barangayContainer");

  if (this.value === "13") {
    provinceDropdown.style.display = "none";
    barangayDropdown.style.display = "none";
  } else {
    provinceDropdown.style.display = "block";
    barangayDropdown.style.display = "block";
  }
});
