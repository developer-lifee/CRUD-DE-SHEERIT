<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'conexion.php';

// Verificar si se ha enviado el formulario y si telefonoHidden tiene un valor
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["telefonoHidden"])) {
    try {
        // Obtener los valores enviados desde el formulario
        $cuentas = $_POST["cuenta"];
        $meses = intval($_POST["meses"]);
        $metodoPago = $_POST["metodoPago"];
        $telefonoHidden = $_POST["telefonoHidden"];

        // Verificar si el clienteID existe en datos_de_cliente
        $sqlClienteID = "SELECT clienteID FROM datos_de_cliente WHERE numero = :numero";
        $stmtClienteID = $conn->prepare($sqlClienteID);
        $stmtClienteID->bindParam(':numero', $telefonoHidden);
        $stmtClienteID->execute();
        $clienteID = $stmtClienteID->fetchColumn();

        if ($clienteID) {
            $total = 0;
            foreach ($cuentas as $cuenta) {
                // Obtener el precio de la cuenta desde la base de datos
                $sqlPrecio = "SELECT id_streaming, precio FROM lista_maestra WHERE nombre_cuenta = :cuenta";
                $stmtPrecio = $conn->prepare($sqlPrecio);
                $stmtPrecio->bindParam(':cuenta', $cuenta);
                $stmtPrecio->execute();
                $resultado = $stmtPrecio->fetch(PDO::FETCH_ASSOC);

                $idStreaming = $resultado['id_streaming'];
                $precioCuenta = $resultado['precio'];
                $total += $precioCuenta;

                // Calcular la fecha de vencimiento (fechaPerfil) basada en la cantidad de meses seleccionados
                $fechaPerfil = new DateTime('now', new DateTimeZone('America/Bogota'));
                $fechaPerfil->modify("+$meses months");
                $fechaPerfilFormatted = $fechaPerfil->format('Y-m-d H:i:s');

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
                    $sqlUpdatePerfil = "UPDATE perfil SET clienteID = :clienteID, metodoPago = :metodoPago, fechaPerfil = :fechaPerfil WHERE idPerfil = :idPerfil";
                    $stmtUpdatePerfil = $conn->prepare($sqlUpdatePerfil);
                    $stmtUpdatePerfil->bindParam(':clienteID', $clienteID);
                    $stmtUpdatePerfil->bindParam(':metodoPago', $metodoPago);
                    $stmtUpdatePerfil->bindParam(':fechaPerfil', $fechaPerfilFormatted);
                    $stmtUpdatePerfil->bindParam(':idPerfil', $idPerfil);
                    $stmtUpdatePerfil->execute();

                    echo "Perfil actualizado exitosamente para la cuenta: " . $cuenta . "<br />";
                    // Prepare data for clipboard
                    $clipboardData = "nombre de cuenta: $cuenta\nCORREO: Sheerstreaming@gmail.com\nCONTRASEÑA: 1294363\nPERFIL: ana\nEL SERVICIO VENCERA EL DIA: " . $fechaPerfil->format('d \d\e F \d\e Y');
                    echo "<script>
                        navigator.clipboard.writeText(`$clipboardData`).then(function() {
                            console.log('Datos copiados al portapapeles');
                        }, function(err) {
                            console.error('Error al copiar al portapapeles: ', err);
                        });
                        window.location.href = '../Tabla Control/administrador/detallesUsuario.html?usuarioID=$clienteID';
                    </script>";
                } else {
                    echo "No se encontró un perfil válido para actualizar en la cuenta: " . $cuenta . "<br />";
                }
            }

            // Aplicar descuento por cantidad de cuentas
            $descuento = (count($cuentas) - 1) * 1000;
            $total -= $descuento;

            // Aplicar descuento por cantidad de meses
            if ($meses === 6) {
                $total *= 0.93;
            } elseif ($meses === 12) {
                $total *= 0.85;
            }

            $total *= $meses;
            $total = floor($total / 1000) * 1000;

            echo "El total a pagar es: $total COP";
        } else {
            echo "El número de teléfono proporcionado no coincide con ningún clienteID existente.";
        }
    } catch (PDOException $exception) {
        echo "Error: " . $exception->getMessage();
    }
} else {
    echo "No se recibió el número de teléfono esperado o el formulario no fue enviado correctamente.";
}
