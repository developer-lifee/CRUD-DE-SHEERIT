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
        $stmtStreaming = $conn->prepare("SELECT id_streaming, precio FROM lista_maestra WHERE nombre_cuenta = ?");
        $stmtStreaming->execute([$streaming]);
        $streamingData = $stmtStreaming->fetch(PDO::FETCH_ASSOC);
        
        if (!$streamingData) {
            // Si no se encuentra el streaming, cancela la transacción
            $conn->rollBack();
            throw new Exception("Streaming no encontrado: $streaming");
        }
        
        $id_streaming = $streamingData['id_streaming'];
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

        // Convertir la fecha
        $fechaPerfil = convertirFecha($deben);

        // Verificar si los datos ya existen en la tabla perfil
        $stmtCheckPerfil = $conn->prepare("SELECT * FROM perfil WHERE clienteID = ? AND idCuenta = ? AND id_streaming = ? AND customerMail = ? AND operador = ? AND pinPerfil = ? AND fechaPerfil = ?");
        $stmtCheckPerfil->execute([$clienteID, $idCuenta, $id_streaming, $customerMail, $operador, $pinPerfil ? $pinPerfil : 0, $fechaPerfil]);
        $perfilData = $stmtCheckPerfil->fetch(PDO::FETCH_ASSOC);

        if ($perfilData) {
            // Si los datos ya existen, imprimir un mensaje y continuar
            echo "Datos duplicados encontrados en perfil: clienteID = $clienteID, idCuenta = $idCuenta, id_streaming = $id_streaming, customerMail = $customerMail, operador = $operador, pinPerfil = $pinPerfil, fechaPerfil = $fechaPerfil\n";
        } else {
            // Inserta los datos en la tabla perfil
            $stmtPerfil = $conn->prepare("INSERT INTO perfil (clienteID, idCuenta, id_streaming, customerMail, operador, pinPerfil, fechaPerfil) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmtPerfil->execute([$clienteID, $idCuenta, $id_streaming, $customerMail, $operador, $pinPerfil ? $pinPerfil : 0, $fechaPerfil]);
        }

        // Calcular el valor de la deuda y el descuento
        $stmtContabilidad = $conn->prepare("SELECT COUNT(*) AS numCuentas FROM datosCuenta WHERE correo = ?");
        $stmtContabilidad->execute([$correo]);
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
