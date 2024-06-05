<?php

$host = "localhost";
$db_name = "estavi0_sheerit";
$username = "estavi0_sheerit";
$password = "26o6ssCOA^";

try {
    $conn = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
    echo "Error de conexión: " . $exception->getMessage();
    exit;
}

function convertirFecha($fecha) {
    $date = DateTime::createFromFormat('m/d/Y', $fecha);
    return $date ? $date->format('Y-m-d H:i:s') : '0000-00-00 00:00:00';
}

function insertarDatos($streaming, $nombre, $apellido, $whatsapp, $contacto, $correo, $contraseña, $customerMail, $operador, $pinPerfil, $deben) {
    global $conn;
    
    $conn->beginTransaction();
    
    try {
        // Busca el id_streaming en la tabla lista_maestra
        $stmtStreaming = $conn->prepare("SELECT id_streaming FROM lista_maestra WHERE nombre_cuenta = ?");
        $stmtStreaming->execute([$streaming]);
        $streamingData = $stmtStreaming->fetch(PDO::FETCH_ASSOC);
        
        if (!$streamingData) {
            // Si no se encuentra el streaming, cancela la transacción
            $conn->rollBack();
            throw new Exception("Streaming no encontrado: $streaming");
        }
        
        $id_streaming = $streamingData['id_streaming'];

        // Verificar si el número de teléfono ya existe en datos_de_cliente
        $stmtCheckCliente = $conn->prepare("SELECT clienteID FROM datos_de_cliente WHERE numero = ?");
        $stmtCheckCliente->execute([$contacto]);
        $clienteData = $stmtCheckCliente->fetch(PDO::FETCH_ASSOC);

        if ($clienteData) {
            $clienteID = $clienteData['clienteID'];
        } else {
            // Inserta los datos en datos_de_cliente con activo en 1
            $stmtDatosCliente = $conn->prepare("INSERT INTO datos_de_cliente (nombre, apellido, numero, nombreContacto, activo) VALUES (?, ?, ?, ?, 1)");
            $stmtDatosCliente->execute([$nombre, $apellido, $contacto, $whatsapp]);
            $clienteID = $conn->lastInsertId(); // Recupera el ID del último registro insertado
        }

        echo "El clienteID insertado es: " . $clienteID . "\n";

        // Asume un valor predeterminado para 'clave' si está vacío
        $clave = $contraseña; // Usa el valor de contraseña del CSV
        $fechaCuenta = date('Y-m-d H:i:s'); // Usa la fecha y hora actual
        
        // Inserta los datos en datosCuenta
        $stmtDatosCuenta = $conn->prepare("INSERT INTO datosCuenta (correo, clave, fechaCuenta, id_streaming) VALUES (?, ?, ?, ?)");
        $stmtDatosCuenta->execute([$correo, $clave, $fechaCuenta, $id_streaming]);
        $idCuenta = $conn->lastInsertId(); // Recupera el ID del último registro insertado en datosCuenta

        // Convertir la fecha
        $fechaPerfil = convertirFecha($deben);

        // Inserta los datos en la tabla perfil
        $stmtPerfil = $conn->prepare("INSERT INTO perfil (clienteID, idCuenta, id_streaming, customerMail, operador, pinPerfil, fechaPerfil) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmtPerfil->execute([$clienteID, $idCuenta, $id_streaming, $customerMail, $operador, $pinPerfil ? $pinPerfil : 0, $fechaPerfil]);

        // Confirma la transacción
        $conn->commit();
        
        echo "Todos los datos insertados correctamente.\n";
        return true;
    } catch (Exception $e) {
        // En caso de error, revierte la transacción
        $conn->rollBack();
        echo "Error al insertar datos: " . $e->getMessage();
        return false;
    }
}

?>
