<?php

require '../../../../sys/conexion.php';

function convertirFecha($fecha)
{
    $date = DateTime::createFromFormat('m/d/Y', $fecha);
    return $date ? $date->format('Y-m-d H:i:s') : null;
}

function insertarDatos($streaming, $nombre, $apellido, $whatsapp, $contacto, $correo, $contraseña, $vencimiento, $customerMail, $operador, $pinPerfil, $deben)
{
    global $conn;

    $perfilEnUso = !empty($deben);

    try {
        $conn->beginTransaction();

        // Eliminar datos existentes
        $stmtDeletePerfil = $conn->prepare("DELETE FROM perfil WHERE customerMail = ? AND operador = ?");
        $stmtDeletePerfil->execute([$customerMail, $operador]);

        $stmtDeleteCuenta = $conn->prepare("DELETE FROM datosCuenta WHERE correo = ? AND id_streaming = (SELECT id_streaming FROM lista_maestra WHERE nombre_cuenta = ?)");
        $stmtDeleteCuenta->execute([$correo, $streaming]);

        $stmtDeleteCliente = $conn->prepare("DELETE FROM datos_de_cliente WHERE numero = ?");
        $stmtDeleteCliente->execute([$contacto]);

        // Busca el id_streaming y max_perfiles en la tabla lista_maestra
        $stmtStreaming = $conn->prepare("SELECT id_streaming, precio, max_perfiles FROM lista_maestra WHERE nombre_cuenta = ? ");
        $stmtStreaming->execute([$streaming]);
        $streamingData = $stmtStreaming->fetch(PDO::FETCH_ASSOC);

        if (!$streamingData) {
            throw new Exception("Streaming no encontrado: $streaming \n");
        }

        $id_streaming = $streamingData['id_streaming'];
        $precio = $streamingData['precio'];
        $maxPerfiles = $streamingData['max_perfiles'];

        echo "Servicio encontrado: id_streaming = $id_streaming, precio = $precio, max_perfiles = $maxPerfiles.\n";

        $clienteID = null;

        if ($perfilEnUso) {
            echo "Procesando datos del cliente...\n";

            $stmtCheckCliente = $conn->prepare("SELECT clienteID FROM datos_de_cliente WHERE numero = ?");
            $stmtCheckCliente->execute([$contacto]);
            $clienteData = $stmtCheckCliente->fetch(PDO::FETCH_ASSOC);

            if ($clienteData) {
                $clienteID = $clienteData['clienteID'];
                echo "Cliente existente encontrado: clienteID = $clienteID.\n";
            } else {
                $stmtDatosCliente = $conn->prepare("INSERT INTO datos_de_cliente (nombre, apellido, numero, nombreContacto, activo) VALUES (?, ?, ?, ?, 1)");
                $stmtDatosCliente->execute([$nombre, $apellido, $contacto, $whatsapp]);
                $clienteID = $conn->lastInsertId();
                echo "Nuevo cliente insertado: clienteID = $clienteID.\n";
            }
        }

        // Verificar si el correo ya existe para el mismo id_streaming en datosCuenta
        $stmtCheckCorreo = $conn->prepare("SELECT idCuenta FROM datosCuenta WHERE correo = ? AND id_streaming = ?");
        $stmtCheckCorreo->execute([$correo, $id_streaming]);
        $cuentaData = $stmtCheckCorreo->fetch(PDO::FETCH_ASSOC);

        $fechaCuenta = convertirFecha($vencimiento); // Convertir la fecha de vencimiento

        if (!$cuentaData) {
            $clave = $contraseña;

            $stmtDatosCuenta = $conn->prepare("INSERT INTO datosCuenta (correo, clave, fechaCuenta, id_streaming) VALUES (?, ?, ?, ?)");
            $stmtDatosCuenta->execute([$correo, $clave, $fechaCuenta, $id_streaming]);
            $idCuenta = $conn->lastInsertId();
            echo "Nueva cuenta insertada: idCuenta = $idCuenta.\n";
        } else {
            $idCuenta = $cuentaData['idCuenta'];

            // Actualizar fechaCuenta con la fecha de vencimiento
            $stmtUpdateFechaCuenta = $conn->prepare("UPDATE datosCuenta SET fechaCuenta = ? WHERE idCuenta = ?");
            $stmtUpdateFechaCuenta->execute([$fechaCuenta, $idCuenta]);

            echo "Cuenta existente encontrada y actualizada: idCuenta = $idCuenta.\n";
        }

        $fechaPerfil = convertirFecha($deben);

        // Verificar si el perfil ya existe
        if (!$perfilEnUso) {
            $stmtCheckPerfil = $conn->prepare("SELECT * FROM perfil WHERE idCuenta = ? AND customerMail = ? AND operador = ? AND pinPerfil = ? AND fechaPerfil IS NULL");
            $stmtCheckPerfil->execute([$idCuenta, $customerMail, $operador, $pinPerfil ? $pinPerfil : 0]);
        } else {
            $stmtCheckPerfil = $conn->prepare("SELECT * FROM perfil WHERE idCuenta = ? AND customerMail = ? AND operador = ? AND pinPerfil = ? AND fechaPerfil = ?");
            $stmtCheckPerfil->execute([$idCuenta, $customerMail, $operador, $pinPerfil ? $pinPerfil : 0, $fechaPerfil]);
        }

        $perfilData = $stmtCheckPerfil->fetch(PDO::FETCH_ASSOC);

        if ($perfilData) {
            echo "Datos duplicados encontrados, no se insertará el perfil.\n";
        } else {
            $stmtCheckMaxPerfiles = $conn->prepare("SELECT COUNT(*) AS totalPerfiles FROM perfil WHERE idCuenta = ?");
            $stmtCheckMaxPerfiles->execute([$idCuenta]);
            $totalPerfilesData = $stmtCheckMaxPerfiles->fetch(PDO::FETCH_ASSOC);
            $totalPerfiles = $totalPerfilesData['totalPerfiles'];

            if ($totalPerfiles >= $maxPerfiles) {
                throw new Exception("Límite de perfiles alcanzado para idCuenta = $idCuenta. Máximo permitido: $maxPerfiles.\n");
            }

            $valorDescuento = ($totalPerfiles > 0) ? ($totalPerfiles * 1000 / $totalPerfiles) : 0;
            $precioUnitario = $precio - $valorDescuento;

            $stmtPerfil = $conn->prepare("INSERT INTO perfil (clienteID, idCuenta, id_streaming, customerMail, operador, pinPerfil, fechaPerfil, precioUnitario) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmtPerfil->execute([$clienteID, $idCuenta, $id_streaming, $customerMail, $operador, $pinPerfil ? $pinPerfil : 0, $fechaPerfil, $precioUnitario]);

            echo "Perfil insertado correctamente para idCuenta = $idCuenta.\n";
        }

        $conn->commit();
        return true;
    } catch (Exception $e) {
        $conn->rollBack();
        echo "Error al insertar datos: " . $e->getMessage() . "\n";
        return false;
    }
}
?>
