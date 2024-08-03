<?php
// Incluye el archivo de conexión a la base de datos
include_once 'conexion.php';

// Verifica si la solicitud es de tipo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica si se proporcionó el ID del usuario y el mensaje
    if (isset($_POST['userId']) && isset($_POST['mensaje'])) {
        $userId = $_POST['userId'];
        $mensaje = $_POST['mensaje'];

        // Realiza una consulta SQL para actualizar la columna 'mensaje' para el usuario especificado
        $sql = "UPDATE elneflis SET mensaje = :mensaje WHERE id = :userId";

        // Prepara la declaración SQL
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Vincula los parámetros y ejecuta la consulta
            $stmt->bindParam(':mensaje', $mensaje);
            $stmt->bindParam(':userId', $userId);

            if ($stmt->execute()) {
                // Éxito, el mensaje se ha guardado en la base de datos
                echo "Mensaje asignado y guardado con éxito.";
            } else {
                echo "Error al guardar el mensaje: " . $stmt->error;
            }

            // Cierra la declaración
            $stmt->close();
        } else {
            echo "Error en la preparación de la consulta: " . $conn->error;
        }
    } else {
        echo "No se han proporcionado datos para guardar el mensaje.";
    }
} else {
    echo "Acceso no válido a este archivo.";
}

// Cierra la conexión a la base de datos
$conn->close();
?>


