document
  .getElementById("downloadPdfBtn")
  .addEventListener("click", function () {
    const { jsPDF } = window.jspdf; // Access the jsPDF constructor
    const doc = new jsPDF({
      format: "a3",
      orientation: "landscape",
    });

    // Add the title text
    doc.setFontSize(16);
    doc.text("SUMMARY OF E-DSR", doc.internal.pageSize.getWidth() / 2, 15, {
      align: "center",
    });

    // Use the autoTable function to convert the HTML table to a PDF
    doc.autoTable({
      html: ".table",
      startY: 25, // Adjust startY to leave space for the title
      theme: "grid", // Use 'grid' theme for borders (alternatively, customize below)
      styles: {
        halign: "center",
        font: "helvetica",
        fontSize: 10,
        textColor: "black",
        valign: "middle",
        borderWidth: 0.5, // Border thickness for all cells
        borderColor: "black", // Border color for all cells
      },
      headStyles: {
        fillColor: "#002060", // Header background color
        textColor: "white", // Header text color
        borderColor: "black", // Header border color
      },
      bodyStyles: {
        fillColor: "white", // Body background color
        textColor: "black", // Body text color
        borderColor: "black", // Body border color
      },
      didParseCell: function (data) {
        // Custom styling based on classes in HTML
        if (data.cell.raw && data.cell.raw.classList) {
          if (
            data.cell.section === "head" &&
            data.cell.raw.classList.contains("days")
          ) {
            data.cell.styles.fillColor = "#92cddc";
          }
          if (
            data.cell.section === "body" &&
            data.cell.raw.classList.contains("dayCalls")
          ) {
            data.cell.styles.fillColor = "#92d050";
          }
          if (
            data.cell.section === "body" &&
            data.cell.raw.classList.contains("names")
          ) {
            data.cell.styles.halign = "start";
          }
          if (
            data.cell.section === "head" &&
            data.cell.raw.classList.contains("total")
          ) {
            data.cell.styles.fillColor = "#938953";
          }
          if (
            data.cell.section === "body" &&
            data.cell.raw.classList.contains("achievement")
          ) {
            data.cell.styles.fillColor = "#8db3e2";
          }
          if (
            data.cell.section === "body" &&
            data.cell.raw.classList.contains("actual")
          ) {
            data.cell.styles.textColor = "#4f81bd";
          }
        }
      },
    });

    // Save the PDF
    doc.save("performance_table.pdf");
  });
