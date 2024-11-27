<?php
// desactivarPerfil.php

require '../../../../sys/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $clienteID = $_POST['clienteID'];
    $perfilID = $_POST['perfilID']; // Asegúrate de recibir el ID del perfil a desactivar

    try {
        $conn->beginTransaction();

        // Actualizar el estado del perfil y la fecha de desactivación
        $sql = "UPDATE perfil SET estado = 'inactivo', fechaDesactivacion = NOW(), clienteID = NULL WHERE perfilID = :perfilID";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':perfilID', $perfilID);

        if ($stmt->execute()) {
            // Verificar si el cliente tiene otros perfiles activos
            $sqlCheckPerfiles = "SELECT COUNT(*) FROM perfil WHERE clienteID = :clienteID AND estado = 'activo'";
            $stmtCheckPerfiles = $conn->prepare($sqlCheckPerfiles);
            $stmtCheckPerfiles->bindParam(':clienteID', $clienteID);
            $stmtCheckPerfiles->execute();
            $perfilesActivos = $stmtCheckPerfiles->fetchColumn();

            // Si no tiene otros perfiles activos, actualizar el estado del cliente a inactivo
            if ($perfilesActivos == 0) {
                $sqlCliente = "UPDATE datos_de_cliente SET activo = 0 WHERE clienteID = :clienteID";
                $stmtCliente = $conn->prepare($sqlCliente);
                $stmtCliente->bindParam(':clienteID', $clienteID);
                $stmtCliente->execute();
            }

            $conn->commit();
            echo "Perfil desactivado con éxito y listo para ser vendido a otro cliente.";
        } else {
            $conn->rollBack();
            echo "Error al desactivar el perfil.";
        }
    } catch (Exception $e) {
        $conn->rollBack();
        echo "Error al desactivar el perfil: " . $e->getMessage();
    }
}
?>