<?php
// Incluye el archivo de conexión a la base de datos
include_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtén los valores del formulario
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $whatsapp = $_POST["whatsapp"];
    $correo = $_POST["correo"];
    $contrasena = $_POST["contrasena"];
    
     // Obtén los valores de correo y contraseña de la base de datos
    $sqlGetUserData = "SELECT correo, contrasena FROM elneflis WHERE streaming = 'Hbo' AND deben IS NULL LIMIT 1";
    $stmtGetUserData = $conn->query($sqlGetUserData);
    $userData = $stmtGetUserData->fetch(PDO::FETCH_ASSOC);

    if ($userData) {
        $correoDB = $userData["correo"];
        $contrasenaDB = $userData["contrasena"];
    } else {
        // En caso de que no se encuentren los datos en la base de datos, proporciona valores predeterminados o maneja el error de otra manera.
        $correoDB = "Correo no encontrado";
        $contrasenaDB = "Contraseña no encontrada";
    }
    // Calcula la fecha del mes siguiente
    $fechaActual = date("Y-m-d");
    $fechaSiguiente = date("Y-m-d", strtotime("+1 month", strtotime($fechaActual)));

    // Actualiza todos los campos en la base de datos
    $sql = "UPDATE elneflis SET nombre = ?, apellido = ?, whatsapp = ?, deben = ? WHERE streaming = 'Hbo' AND deben IS NULL LIMIT 1";
    $stmt = $conn->prepare($sql);

    // Ejecuta la consulta con los valores del formulario y la fecha siguiente
    $stmt->execute([$nombre, $apellido, $whatsapp, $fechaSiguiente]);

    // Envía el contenido JSON por WhatsApp (solo correo y contraseña)
    $contenidoJSON = "Bienvenid@ 

Sheer IT quiere darte la bienvenida y te agradecemos por tu compra y esperamos disfrutes tus cuentas 

Recuerda 

*Streaming:* Hbo
*Correo:* $correoDB
*Contraseña:* $contrasenaDB
*Fecha de Vencimiento:* $fechaSiguiente";
    enviarPorWhatsApp($contenidoJSON, $whatsapp);

    echo "Registro actualizado y contenido enviado por WhatsApp correctamente. JSON Actualizado: $contenidoJSON";
}
// Función para enviar contenido por WhatsApp (debes implementarla)
function enviarPorWhatsApp($mensaje, $numeroTelefono) {
    // Asegúrate de configurar adecuadamente la funcionalidad de envío de WhatsApp
    // Reemplaza los valores a continuación con la configuración real

    $apiUrl = 'https://api.whatsapp.com/send'; // URL de la API de WhatsApp

    // Formatea el mensaje y número de teléfono para la URL de WhatsApp
    $mensajeWhatsApp = urlencode($mensaje);
    $urlWhatsApp = "$apiUrl?phone=$numeroTelefono&text=$mensajeWhatsApp";

    // Redirige al usuario a la URL de WhatsApp
    header("Location: $urlWhatsApp");
    exit;
}
?>