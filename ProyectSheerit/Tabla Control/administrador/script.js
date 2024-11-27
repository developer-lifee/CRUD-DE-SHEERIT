document.addEventListener("DOMContentLoaded", function() {
    const headers = document.querySelectorAll("th");
    const rows = document.querySelectorAll("tbody tr");

    rows.forEach(row => {
        const cells = row.querySelectorAll("td");
        cells.forEach((cell, i) => {
            cell.setAttribute("data-label", headers[i].textContent);
        });
    });
});

function filtrarColumna(inputElement, columnIndex) {
  var filtro = inputElement.value.toLowerCase();
  var rows = document.querySelectorAll("#tabla-datos tr");

  rows.forEach(row => {
    var cell = row.querySelectorAll("td")[columnIndex];
    if (cell) {
      var textoCelda = cell.textContent.toLowerCase();
      if (textoCelda.includes(filtro)) {
        row.style.display = ""; // Show the row
      } else {
        row.style.display = "none"; // Hide the row
      }
    }
  });
}

