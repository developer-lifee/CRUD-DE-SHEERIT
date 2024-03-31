$(document).ready(function() {
    // Suponemos que agregar.php devuelve la información en el formato correcto.
    $.ajax({
        url: 'conexion2.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            mostrarDatos(data);
        },
        error: function() {
            alert("Error al obtener los datos");
        }
    });

    $('#aplicarFiltro').on('click', function() {
        // Implementa tu lógica para filtrar los datos por color aquí.
    });
});

function mostrarDatos(data) {
    $("#tabla-datos").empty();

    // Itera sobre cada fila de datos y agrega a la tabla
    data.forEach(function(row) {
        // Construye la fila de la tabla basada en los datos
        var newRow = `<tr>
            <td>${row.streaming}</td>
            <td>${row.nombre}</td>
            <td>${row.apellido}</td>
            <td>${row.whatsapp}</td>
            <td>${row.correo}</td>
            <td>${row.contrasena}</td>
            <td>${row.perfil}</td>
            <td>${row.deben}</td>
            <td>${/* Días faltantes */}</td>
            <td>
                <button onclick="enviarMensaje(${row.id}, '${row.whatsapp}')">Enviar WhatsApp</button>
                <input type="text" id="mensaje-${row.id}" placeholder="Escribe un mensaje...">
                <button onclick="guardarMensaje(${row.id})">Guardar Mensaje</button>
            </td>
        </tr>`;
        $("#tabla-datos").append(newRow);
    });
}

function enviarMensaje(id, whatsapp) {
    var mensaje = encodeURIComponent($("#mensaje-" + id).val());
    var whatsappUrl = `https://api.whatsapp.com/send?phone=${whatsapp}&text=${mensaje}`;
    window.open(whatsappUrl, '_blank');
}

function guardarMensaje(id) {
    var mensaje = $("#mensaje-" + id).val();
    $.ajax({
        url: 'mensaje.php',
        type: 'POST',
        data: { id: id, mensaje: mensaje },
        success: function(response) {
            alert("Mensaje guardado con éxito.");
        },
        error: function() {
            alert("Error al guardar el mensaje.");
        }
    });
}
