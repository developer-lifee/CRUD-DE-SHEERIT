<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'conexion.php';

// Verificar si se ha enviado el formulario y si telefonoHidden tiene un valor
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["telefonoHidden"])) {
    try {
        // Obtener los valores enviados desde el formulario
        $cuentas = $_POST["cuenta"];
        $fechasCompra = $_POST["fechaCompra"];
        $metodoPago = $_POST["metodoPago"];
        $telefonoHidden = $_POST["telefonoHidden"];

        // Verificar si el clienteID existe en datos_de_cliente
        $sqlClienteID = "SELECT clienteID FROM datos_de_cliente WHERE numero = :numero";
        $stmtClienteID = $conn->prepare($sqlClienteID);
        $stmtClienteID->bindParam(':numero', $telefonoHidden);
        $stmtClienteID->execute();
        $clienteID = $stmtClienteID->fetchColumn();

        if ($clienteID) {
            foreach ($cuentas as $index => $cuenta) {
                $fechaCompra = $fechasCompra[$index];

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

                    echo "Perfil actualizado exitosamente para la cuenta: " . $cuenta . "<br />";
                } else {
                    echo "No se encontró un perfil válido para actualizar en la cuenta: " . $cuenta . "<br />";
                }
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