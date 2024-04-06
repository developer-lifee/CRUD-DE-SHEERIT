<?php
// eliminar.php

$host = "localhost";
$db_name = "estavi0_sheerit";
$username = "estavi0_sheerit";
$password = "26o6ssCOA^";

try {
    $conn = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Asumiendo que 'clienteID' es la clave correcta para identificar el perfil a eliminar
        $clienteID = $_POST['clienteID'];

        // La consulta SQL ahora actualiza varios campos a NULL para el 'clienteID' especificado
        $sql = "UPDATE perfil SET perfil = NULL, clienteID = NULL, fechaPerfil = NULL, pinPerfil = NULL, metodoPago = NULL WHERE clienteID = :clienteID";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':clienteID', $clienteID);

        if ($stmt->execute()) {
            echo "Perfil eliminado con éxito";
        } else {
            echo "Perfil eliminado con éxito";
        }
    }
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
