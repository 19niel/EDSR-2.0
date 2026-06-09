function exportToExcel() {
  var table = document.getElementById("largeTable");
  var wb = XLSX.utils.table_to_book(table, { sheet: "Sheet 1" });
  XLSX.writeFile(wb, "myTable.xlsx");
}
