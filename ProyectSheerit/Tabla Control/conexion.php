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

function insertarDatos($streaming, $nombre, $apellido, $whatsapp, $contacto, $correo, $contraseña, $customerMail, $operador, $pinPerfil, $deben) {
    global $conn;
    
    $conn->beginTransaction();
    
    try {
        // Busca el id_streaming en la tabla lista_maestra
        $stmtStreaming = $conn->prepare("SELECT id_streaming, precio FROM lista_maestra WHERE nombre_cuenta = ?");
        $stmtStreaming->execute([$streaming]);
        $streamingData = $stmtStreaming->fetch(PDO::FETCH_ASSOC);
        
        if (!$streamingData) {
            // Si no se encuentra el streaming, cancela la transacción
            $conn->rollBack();
            throw new Exception("Streaming no encontrado: $streaming");
        }
        
        $id_streaming = $streamingData['id_streaming'];
<<<<<<< HEAD
        $precio = $streamingData['precio'];

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

=======
        
        // Inserta los datos en datos_de_cliente con activo en 1
        $stmtDatosCliente = $conn->prepare("INSERT INTO datos_de_cliente (nombre, apellido, numero, nombreContacto, activo) VALUES (?, ?, ?, ?, 1)");
        $stmtDatosCliente->execute([$nombre, $apellido, $contacto, $whatsapp]);
        $clienteID = $conn->lastInsertId(); // Recupera el ID del último registro insertado
        
>>>>>>> parent of 359d563 (conversion de fechas y manejo de varios datos un solo cliente)
        echo "El clienteID insertado es: " . $clienteID . "\n";

        // Verificar si el correo ya existe para el mismo id_streaming en datosCuenta
        $stmtCheckCorreo = $conn->prepare("SELECT idCuenta FROM datosCuenta WHERE correo = ? AND id_streaming = ?");
        $stmtCheckCorreo->execute([$correo, $id_streaming]);
        $cuentaData = $stmtCheckCorreo->fetch(PDO::FETCH_ASSOC);

        if (!$cuentaData) {
            // Asume un valor predeterminado para 'clave' si está vacío
            $clave = $contraseña; // Usa el valor de contraseña del CSV
            $fechaCuenta = date('Y-m-d H:i:s'); // Usa la fecha y hora actual

            // Inserta los datos en datosCuenta
            $stmtDatosCuenta = $conn->prepare("INSERT INTO datosCuenta (correo, clave, fechaCuenta, id_streaming) VALUES (?, ?, ?, ?)");
            $stmtDatosCuenta->execute([$correo, $clave, $fechaCuenta, $id_streaming]);
            $idCuenta = $conn->lastInsertId(); // Recupera el ID del último registro insertado en datosCuenta
        } else {
            $idCuenta = $cuentaData['idCuenta'];
        }

        //DEBEN EN NULL / Fecha en pinPerfil
        $debenFormatted = $deben ? $deben : '0000-00-00 00:00:00'; // Formato de fecha por defecto si 'deben' está vacío

        // Inserta los datos en la tabla perfil
        $stmtPerfil = $conn->prepare("INSERT INTO perfil (clienteID, idCuenta, id_streaming, customerMail, operador, pinPerfil, fechaPerfil) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmtPerfil->execute([$clienteID, $idCuenta, $id_streaming, $customerMail, $operador, $pinPerfil ? $pinPerfil : 0, $debenFormatted]);

        // Calcular el valor de la deuda y el descuento
        $stmtContabilidad = $conn->prepare("SELECT COUNT(*) AS numCuentas FROM datosCuenta WHERE clienteID = ?");
        $stmtContabilidad->execute([$clienteID]);
        $cuentaCountData = $stmtContabilidad->fetch(PDO::FETCH_ASSOC);
        $numCuentas = $cuentaCountData['numCuentas'];

        $valorDeuda = $numCuentas * $precio;
        $valorDescuento = ($numCuentas - 1) * 1000; // Descuento de 1000 por cada cuenta adicional

        // Inserta los datos en contabilidad
        $stmtContabilidad = $conn->prepare("INSERT INTO contabilidad (clienteID, deben, valorDeuda, valorDescuento) VALUES (?, ?, ?, ?)");
        $stmtContabilidad->execute([$clienteID, $fechaPerfil, $valorDeuda, $valorDescuento]);

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
