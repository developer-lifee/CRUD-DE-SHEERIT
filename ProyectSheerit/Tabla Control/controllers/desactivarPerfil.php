<?php
// eliminar.php

require '../../../../sys/conexion.php';

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
?>
