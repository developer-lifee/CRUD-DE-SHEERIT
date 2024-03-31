<?php
// Incluir el archivo de conexión a la base de datos
include 'conexion.php';

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Obtener los valores enviados desde el formulario
        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $numero = $_POST["numero"];
        $nombreContacto = $_POST["nombreContacto"];
        $metodoPago = $_POST["metodoPago"];
        $fechaCompra = $_POST["fechaCompra"];
        $cuenta = $_POST["cuenta"];

        // Verificar si el usuario está registrado
        if ($_POST["registrado"] == "true") {
            // Si está registrado, solo actualizar los datos existentes
            $sql = "UPDATE datos_de_cliente SET nombre = :nombre, apellido = :apellido, numero = :numero, nombreContacto = :nombreContacto WHERE clienteID = :clienteID";

            // Preparar la sentencia SQL
            $stmt = $conn->prepare($sql);

            // Vincular los valores a los parámetros de la consulta
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':numero', $numero);
            $stmt->bindParam(':nombreContacto', $nombreContacto);
            $stmt->bindParam(':clienteID', $_POST["clienteID"]);

            // Ejecutar la consulta
            $stmt->execute();
        } else {
            // Si no está registrado, insertar nuevos datos
            $sql = "INSERT INTO datos_de_cliente (nombre, apellido, numero, nombreContacto) VALUES (:nombre, :apellido, :numero, :nombreContacto)";

            // Preparar la sentencia SQL
            $stmt = $conn->prepare($sql);

            // Vincular los valores a los parámetros de la consulta
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':numero', $numero);
            $stmt->bindParam(':nombreContacto', $nombreContacto);

            // Ejecutar la consulta
            $stmt->execute();
        }

        // Realizar operaciones comunes para ambas situaciones
        // Buscar el ID de cuenta según el nombre de cuenta seleccionado
        $sql = "SELECT id_streaming FROM lista_maestra WHERE nombre_cuenta = :cuenta";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':cuenta', $cuenta);
        $stmt->execute();
        $idStreaming = $stmt->fetchColumn();

        // Insertar en lista_maestra si no existe
        $sql = "INSERT IGNORE INTO lista_maestra (id_streaming, nombre_cuenta) VALUES (:idStreaming, :cuenta)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':idStreaming', $idStreaming);
        $stmt->bindParam(':cuenta', $cuenta);
        $stmt->execute();

        // Actualizar perfil según la lógica proporcionada
        // ...

        // Mostrar mensaje de éxito
        echo "Los datos se han insertado o actualizado correctamente.";
    } catch (PDOException $exception) {
        echo "Error: " . $exception->getMessage();
    }
}
?>