<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $numero = $_POST["numero"];
        $nombreContacto = $_POST["nombreContacto"];
        $metodoPago = $_POST["metodoPago"];
        $meses = intval($_POST["meses"]);
        $cuentas = $_POST["cuenta"];

        $clienteID = null;

        if ($_POST["registrado"] == "true") {
            // Obtener el nombre del cliente
            $nombreCliente = $_POST["nombre"];

            // Actualizar datos del cliente existente
            $sql = "UPDATE datos_de_cliente SET nombre = :nombre, apellido = :apellido, nombreContacto = :nombreContacto WHERE clienteID = :clienteID";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':nombreContacto', $nombreContacto);
            $stmt->bindParam(':clienteID', $_POST["clienteID"]);
            $stmt->execute();

            $clienteID = $_POST["clienteID"];
        } else {
            // Insertar nuevo cliente
            $sql = "INSERT INTO datos_de_cliente (nombre, apellido, numero, nombreContacto) VALUES (:nombre, :apellido, :numero, :nombreContacto)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':numero', $numero);
            $stmt->bindParam(':nombreContacto', $nombreContacto);
            $stmt->execute();

            $clienteID = $conn->lastInsertId();
        }

        $total = 0;

        foreach ($cuentas as $index => $cuenta) {
            // Obtener el ID y precio de la cuenta seleccionada
            $sql = "SELECT id_streaming, precio FROM lista_maestra WHERE nombre_cuenta = :cuenta";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':cuenta', $cuenta);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $idStreaming = $result['id_streaming'];
            $precio = $result['precio'];

            if ($index > 0) {
                $precio -= 1000; // Descuento por cuenta adicional
            }

            $total += $precio;

            // Insertar el perfil asociado con el cliente y la cuenta de streaming
            $fechaVencimiento = date('Y-m-d', strtotime("+$meses months"));
            $sqlPerfil = "INSERT INTO perfil (clienteID, id_streaming, metodoPago, fechaPerfil) VALUES (:clienteID, :id_streaming, :metodoPago, :fechaPerfil)";
            $stmtPerfil = $conn->prepare($sqlPerfil);
            $stmtPerfil->bindParam(':clienteID', $clienteID);
            $stmtPerfil->bindParam(':id_streaming', $idStreaming);
            $stmtPerfil->bindParam(':metodoPago', $metodoPago);
            $stmtPerfil->bindParam(':fechaPerfil', $fechaVencimiento);
            $stmtPerfil->execute();
        }

        // Aplicar descuentos si es necesario
        if ($meses == 6) {
            $total *= 0.93; // 7% de descuento
        } elseif ($meses == 12) {
            $total *= 0.85; // 15% de descuento
        }

        $total = floor($total / 1000) * 1000; // Redondear a miles hacia abajo

        echo "Total a pagar: $total COP. Los datos se han insertado o actualizado correctamente.";
    } catch (PDOException $exception) {
        echo "Error: " . $exception->getMessage();
    }
}
