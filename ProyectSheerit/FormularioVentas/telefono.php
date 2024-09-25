<?php
$host = "localhost";
$db_name = "estavi0_sheerit";
$username = "estavi0_sheerit";
$password = "26o6ssCOA^";

try {
    // Establecer la conexión PDO
    $conn = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener el número de teléfono enviado desde el formulario
    $contactoNumero = $_POST['telefono']; // Cambiar 'contacto_numero' por 'telefono'

    // Consultar la tabla datos_de_cliente para verificar si el número de teléfono está presente
    $query = "SELECT COUNT(*) as count FROM datos_de_cliente WHERE numero = :contactoNumero";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':contactoNumero', $contactoNumero);
    $stmt->execute();

    // Obtener el número de resultados de la consulta
    $rowCount = $stmt->rowCount();

    // Obtener el resultado de la consulta
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $count = $row['count'];

    // Verificar el resultado de la consulta y devolver una respuesta al cliente
    if ($count > 0) {
        // Si se encontró el número en la base de datos
        echo json_encode(array("registrado" => true));
    } else {
        // Si el número no se encontró en la base de datos
        echo json_encode(array("registrado" => false));
    }
} catch (PDOException $exception) {
    // Capturar cualquier error de conexión o de consulta
    echo json_encode(array("error" => "Error de conexión: " . $exception->getMessage()));
}
