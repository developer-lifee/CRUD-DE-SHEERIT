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

function mostrarDatos(data) {
    var tabla = $("#tabla-datos");
    tabla.empty(); // Limpia la tabla antes de agregar nuevos datos

    $.each(data, function(index, row) {
        if (row !== null) {
            // Calcula los días restantes para el próximo pago
            var fechaPerfil = new Date(row.fechaPerfil);
            var fechaVencimiento = new Date(fechaPerfil.getTime() + 30 * 24 * 60 * 60 * 1000);
            var hoy = new Date();
            var diferenciaTiempo = fechaVencimiento.getTime() - hoy.getTime();
            var diasRestantes = Math.ceil(diferenciaTiempo / (1000 * 3600 * 24));

            // Aplica los estilos según los días restantes
            var color = diasRestantes <= 0 ? "red" : diasRestantes <= 7 ? "yellow" : "none";
            var estiloDias = `style="background-color: ${color};"`;

            var htmlRow = `
            <tr>
                <td>${row.correo}</td>
                <td>${row.clave}</td>
                <td>${row.nombre}</td>
                <td>${row.apellido}</td>
                <td>${row.numero}</td>
                <td>${row.nombreContacto}</td>
                <td>${row.nombre_cuenta}</td>
                <td>${row.precio}</td>
                <td>${row.metodoPago}</td>
                <td><button onclick="eliminarPerfil(${row.clienteID})">Eliminar</button></td>
            </tr>
            `;
            tabla.append(htmlRow);
        }
    });

    // Reasigna data-labels después de cargar los datos dinámicamente
    const headers = document.querySelectorAll("th");
    const rows = document.querySelectorAll("tbody tr");

    rows.forEach(row => {
        const cells = row.querySelectorAll("td");
        cells.forEach((cell, i) => {
            cell.setAttribute("data-label", headers[i].textContent);
        });
    });
}

function eliminarPerfil(clienteID) {
    if (confirm("¿Estás seguro de que quieres eliminar este perfil?")) {
        $.ajax({
            url: '../controllers/desactivarPerfil.php',
            type: 'POST',
            data: { clienteID: clienteID },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    location.reload(); // Recargar la página para reflejar los cambios
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert("Error al eliminar el perfil.");
            }
        });
    }
}

function aplicarFiltros() {
    var filtroNombre = $('#filtroNombre').val().toLowerCase();
    var filtroCuenta = $('#filtroCuenta').val().toLowerCase();
    var filtroMetodoPago = $('#filtroMetodoPago').val().toLowerCase();
    var filtroCorreo = $('#filtroCorreo').val().toLowerCase();

    // Verifica si todos los filtros están vacíos
    if (!filtroNombre && !filtroCuenta && !filtroMetodoPago && !filtroCorreo) {
        mostrarDatos(datosOriginales); // Muestra todos los datos si no hay filtros aplicados
        return; // Termina la ejecución de la función aquí
    }

    var datosFiltrados = datosOriginales.filter(function(item) {
        // Verifica si el elemento es null o no
        if (!item) return false;

        var nombre = item.nombre ? item.nombre.toLowerCase() : '';
        var nombreCuenta = item.nombre_cuenta ? item.nombre_cuenta.toLowerCase() : '';
        var metodoPago = item.metodoPago ? item.metodoPago.toLowerCase() : '';
        var correo = item.correo ? item.correo.toLowerCase() : '';

        return (nombre.includes(filtroNombre) || filtroNombre === '') &&
            (nombreCuenta.includes(filtroCuenta) || filtroCuenta === '') &&
            (metodoPago.includes(filtroMetodoPago) || filtroMetodoPago === '') &&
            (correo.includes(filtroCorreo) || filtroCorreo === '');
    });

    mostrarDatos(datosFiltrados);
}
