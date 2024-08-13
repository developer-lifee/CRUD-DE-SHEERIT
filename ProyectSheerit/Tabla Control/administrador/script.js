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

