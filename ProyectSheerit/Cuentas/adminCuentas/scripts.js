$(document).ready(function() {
    $.ajax({
        url: 'cuentasAdmin.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            datosOriginales = data;
            mostrarDatos(datosOriginales);
        },
        error: function(xhr, status, error) {
            alert("Error al obtener los datos");
        }
    });

    $('#toggle-dark-mode').click(function() {
        $('body').toggleClass('dark-mode');
    });
});

function mostrarDatos(data) {
    var tabla = $("#tabla-datos");
    tabla.empty();

    $.each(data, function(index, row) {
        if (row && row.idCuenta !== undefined) {
            var htmlRow = `
            <tr>
                <td>${row.correo}</td>
                <td>${row.clave}</td>
                <td>${row.fechaCuenta}</td>
                <td>${row.nombre_cuenta}</td>
                <td>
                    <button onclick="editarCuenta(${row.idCuenta})">Editar</button>
                    <button onclick="eliminarCuenta(${row.idCuenta})">Eliminar</button>
                </td>
            </tr>
            `;
            tabla.append(htmlRow);
        }
    });
}

function eliminarCuenta(idCuenta) {
    if (confirm("¿Estás seguro de que quieres eliminar esta cuenta?")) {
        $.ajax({
            url: 'editarEliminarCuentas.php',
            type: 'POST',
            dataType: 'json',
            data: { action: "delete", idCuenta: idCuenta },
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                } else {
                    alert("Error: " + response.error);
                }
            },
            error: function(xhr, status, error) {
                alert("Error en la solicitud: " + error);
            }
        });
    }
}

function editarCuenta(idCuenta) {
    $('#modalEditar').css('display', 'block');

    $('#formEditarCuenta').submit(function(event) {
        event.preventDefault();

        var formData = {
            action: "edit",
            idCuenta: idCuenta,
            correo: $('#correo').val(),
            clave: $('#clave').val()
        };

        $.ajax({
            url: 'editarEliminarCuentas.php',
            type: 'POST',
            dataType: 'json',
            data: formData,
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    $('#modalEditar').css('display', 'none');
                } else {
                    alert("Error: " + response.error);
                }
            },
            error: function(xhr, status, error) {
                alert("Error en la solicitud: " + error);
            }
        });
    });
}