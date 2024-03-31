<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'conexion.php';

// Verificar si se ha enviado el formulario y si telefonoHidden tiene un valor
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["telefonoHidden"])) {
    try {
        // Obtener los valores enviados desde el formulario
        $cuenta = $_POST["cuenta"];
        $metodoPago = $_POST["metodoPago"];
        $fechaCompra = $_POST["fechaCompra"];
        $telefonoHidden = $_POST["telefonoHidden"];

        // Mostrar los valores obtenidos para depuración
        echo "Cuenta: " . $cuenta . "<br />";
        echo "Metodo de Pago: " . $metodoPago . "<br />";
        echo "Fecha de Compra: " . $fechaCompra . "<br />";
        echo "Telefono Hidden: " . $telefonoHidden . "<br />";

        // Verificar si el clienteID existe en datos_de_cliente
        $sqlClienteID = "SELECT clienteID FROM datos_de_cliente WHERE numero = :numero";
        $stmtClienteID = $conn->prepare($sqlClienteID);
        $stmtClienteID->bindParam(':numero', $telefonoHidden);
        $stmtClienteID->execute();
        $clienteID = $stmtClienteID->fetchColumn();

        if ($clienteID) {
            echo "ClienteID encontrado: " . $clienteID . "<br />";

            // Proceder con la actualización del perfil solo si el clienteID existe
            $sql = "SELECT id_streaming FROM lista_maestra WHERE nombre_cuenta = :cuenta";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':cuenta', $cuenta);
            $stmt->execute();
            $idStreaming = $stmt->fetchColumn();

            // Verificar si existe un perfil para el streaming seleccionado y fechaPerfil es NULL
            $sql = "SELECT idPerfil FROM perfil WHERE id_streaming = :idStreaming AND fechaPerfil IS NULL";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':idStreaming', $idStreaming);
            $stmt->execute();
            $idPerfil = $stmt->fetchColumn();

            if ($idPerfil) {
                // Actualizar perfil con los datos proporcionados
                $sqlUpdatePerfil = "UPDATE perfil SET clienteID = :clienteID, metodoPago = :metodoPago, fechaPerfil = :fechaCompra WHERE idPerfil = :idPerfil";
                $stmtUpdatePerfil = $conn->prepare($sqlUpdatePerfil);
                $stmtUpdatePerfil->bindParam(':clienteID', $clienteID);
                $stmtUpdatePerfil->bindParam(':metodoPago', $metodoPago);
                $stmtUpdatePerfil->bindParam(':fechaCompra', $fechaCompra);
                $stmtUpdatePerfil->bindParam(':idPerfil', $idPerfil);
                $stmtUpdatePerfil->execute();

                echo "Perfil actualizado exitosamente.";
            } else {
                echo "No se encontró un perfil válido para actualizar.";
            }
        } else {
            echo "El número de teléfono proporcionado no coincide con ningún clienteID existente.";
        }
    } catch (PDOException $exception) {
        echo "Error: " . $exception->getMessage();
    }
} else {
    echo "No se recibió el número de teléfono esperado o el formulario no fue enviado correctamente.";
}
?>
