<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["numero"])) {
    try {
        // Obtener los valores enviados desde el formulario de nuevo cliente
        $cuentas = $_POST["cuenta"];
        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $nombreContacto = $_POST["nombreContacto"];
        $numero = $_POST["numero"];
        $deben = $_POST["deben"];
        $metodoPago = $_POST["metodoPago"]; // Asumiendo que tienes un input en tu formulario para el metodoPago
        $meses = intval($_POST["meses"]);
        $total = 0;

        // Insertar el nuevo cliente en la base de datos
        $sqlInsertCliente = "INSERT INTO datos_de_cliente (nombre, apellido, nombreContacto, numero, activo) VALUES (:nombre, :apellido, :nombreContacto, :numero, 1)";
        $stmtInsertCliente = $conn->prepare($sqlInsertCliente);
        $stmtInsertCliente->bindParam(':nombre', $nombre);
        $stmtInsertCliente->bindParam(':apellido', $apellido);
        $stmtInsertCliente->bindParam(':nombreContacto', $nombreContacto);
        $stmtInsertCliente->bindParam(':numero', $numero);
        $stmtInsertCliente->execute();

        // Obtener el clienteID del cliente que acabamos de insertar
        $clienteID = $conn->lastInsertId();

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

            // Verificar si existe un perfil para el streaming seleccionado y fechaPerfil es NULL
            $sql = "SELECT id_streaming FROM lista_maestra WHERE nombre_cuenta = :cuenta";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':cuenta', $cuenta);
            $stmt->execute();
            $idStreaming = $stmt->fetchColumn();

            if ($idStreaming) {
                $sqlPerfil = "SELECT idPerfil FROM perfil WHERE id_streaming = :idStreaming AND fechaPerfil IS NULL";
                $stmtPerfil = $conn->prepare($sqlPerfil);
                $stmtPerfil->bindParam(':idStreaming', $idStreaming);
                $stmtPerfil->execute();
                $idPerfil = $stmtPerfil->fetchColumn();

                if ($idPerfil) {
                    // Actualizar perfil con los datos del nuevo cliente
                    $sqlUpdatePerfil = "UPDATE perfil SET clienteID = :clienteID, metodoPago = :metodoPago, fechaPerfil = :deben WHERE idPerfil = :idPerfil";
                    $stmtUpdatePerfil = $conn->prepare($sqlUpdatePerfil);
                    $stmtUpdatePerfil->bindParam(':clienteID', $clienteID);
                    $stmtUpdatePerfil->bindParam(':metodoPago', $metodoPago);
                    $stmtUpdatePerfil->bindParam(':deben', $deben);
                    $stmtUpdatePerfil->bindParam(':idPerfil', $idPerfil);
                    $stmtUpdatePerfil->execute();

                    echo "Perfil actualizado con los datos del nuevo cliente, método de pago y fecha asignada para la cuenta: " . $cuenta . "<br />";
                } else {
                    echo "No se encontró un perfil válido para asociar con el nuevo cliente en la cuenta: " . $cuenta . "<br />";
                }
            } else {
                echo "No se encontró una cuenta de streaming que coincida con la proporcionada: " . $cuenta . "<br />";
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
        echo "<script>
            window.location.href = '../Tabla Control/administrador/detallesUsuario.php?clienteID=$clienteID';
        </script>";
    } catch (PDOException $exception) {
        echo "Error: " . $exception->getMessage();
    }
} else {
    echo "No se recibieron todos los datos necesarios a través del formulario.";
}
