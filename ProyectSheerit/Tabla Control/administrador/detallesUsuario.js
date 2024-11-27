
document.addEventListener("DOMContentLoaded", function() {
    // Obtén el ID del usuario de los parámetros de la URL
    const urlParams = new URLSearchParams(window.location.search);
    const usuarioID = urlParams.get("usuarioID");

    if (!usuarioID) {
        alert("No se encontró un usuario válido.");
        return;
    }

    // Llama a un endpoint para obtener los datos del usuario
    fetch(`../controllers/obtenerDetallesUsuario.php?usuarioID=${usuarioID}`)
        .then(response => response.json())
        .then(data => {
            if (data && data.usuario) {
                // Muestra el nombre del usuario
                document.getElementById("usuario-nombre").textContent = `Cuentas de: ${data.usuario.nombre}`;

                // Llena la tabla con las cuentas del usuario
                const tabla = document.getElementById("tabla-cuentas");
                data.cuentas.forEach(cuenta => {
                    const row = document.createElement("tr");
                    row.innerHTML = `
                        <td>${cuenta.correo}</td>
                        <td>${cuenta.clave}</td>
                        <td>${cuenta.nombre_cuenta}</td>
                        <td>${cuenta.precio}</td>
                        <td>${cuenta.metodoPago}</td>
                    `;
                    tabla.appendChild(row);
                });
            } else {
                alert("No se encontraron datos para este usuario.");
            }
        })
        .catch(error => {
            console.error("Error al obtener los datos del usuario:", error);
            alert("Hubo un error al cargar los detalles del usuario.");
        });
});