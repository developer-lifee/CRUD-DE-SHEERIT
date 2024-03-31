<?php

$host = "localhost";
$db_name = "id20971532_sheerit";
$username = "id20971532_root";
$password = "26o6ssCOA^";

try {
    $conn = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $clienteID = $_POST['clienteID'];
        $mensaje = $_POST['mensaje'];

        $sql = "UPDATE datos_de_cliente SET mensaje = :mensaje WHERE clienteID = :clienteID";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':mensaje', $mensaje);
        $stmt->bindParam(':clienteID', $clienteID);
        
        if ($stmt->execute()) {
            echo "Mensaje guardado con éxito";
        } else {
            echo "Error al guardar el mensaje";
        }
    }
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
