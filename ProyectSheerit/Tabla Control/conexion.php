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

function insertarDatos($streaming, $nombre, $apellido, $numero, $nombreContacto, $correo, $operador, $pinPerfil, $fechaPerfil) {
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
        
        // Inserta los datos en datos_de_cliente
        $stmtDatosCliente = $conn->prepare("INSERT INTO datos_de_cliente (nombre, apellido, numero, nombreContacto) VALUES (?, ?, ?, ?)");
        $stmtDatosCliente->execute([$nombre, $apellido, $numero, $nombreContacto]);
        $clienteID = $conn->lastInsertId(); // Recupera el ID del último registro insertado
        
        echo "El clienteID insertado es: " . $clienteID . "\n";

        // Verificar si el clienteID existe en la tabla datos_de_cliente
        $stmtCheckCliente = $conn->prepare("SELECT clienteID FROM datos_de_cliente WHERE clienteID = ?");
        $stmtCheckCliente->execute([$clienteID]);
        if (!$stmtCheckCliente->fetch(PDO::FETCH_ASSOC)) {
            $conn->rollBack();
            throw new Exception("El clienteID no existe en la tabla datos_de_cliente.");
        }

        // Asume un valor predeterminado para 'clave' si está vacío
        $clave = '0'; // Asume un valor predeterminado
        $fechaCuenta = date('Y-m-d H:i:s'); // Usa la fecha y hora actual
        
        // Inserta los datos en datosCuenta
        $stmtDatosCuenta = $conn->prepare("INSERT INTO datosCuenta (correo, clave, fechaCuenta, id_streaming) VALUES (?, ?, ?, ?)");
        $stmtDatosCuenta->execute([$correo, $clave, $fechaCuenta, $id_streaming]);

        //DEBEN EN NULL / Fecha en pinPerfil
        // Prepara los datos para contabilidad
        $debenFormatted = $fechaPerfil ? $fechaPerfil : '0000-00-00 00:00:00'; // Formato de fecha por defecto si 'deben' está vacío

        // Inserta los datos en contabilidad si es necesario
        // $stmtContabilidad = $conn->prepare("INSERT INTO contabilidad (clienteID, deben, valorDeuda, valorDescuento) VALUES (?, ?, ?, ?)");
        // $stmtContabilidad->execute([$clienteID, $debenFormatted, 0, 0]); // Asigna 0 a valorDeuda y valorDescuento por defecto
        
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
