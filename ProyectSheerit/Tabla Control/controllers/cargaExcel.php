<?php

require '../../../../sys/conexion.php';

function convertirFecha($fecha) {
    $date = DateTime::createFromFormat('m/d/Y', $fecha);
    return $date ? $date->format('Y-m-d H:i:s') : null;
}

function insertarDatos($streaming, $nombre, $apellido, $whatsapp, $contacto, $correo, $contraseña, $customerMail, $operador, $pinPerfil, $deben) {
    global $conn;

    // Determinar si hay que manejar el perfil
    $perfilEnUso = !empty($deben);
    
    $conn->beginTransaction();
    
    try {
        // Busca el id_streaming y max_perfiles en la tabla lista_maestra
        $stmtStreaming = $conn->prepare("SELECT id_streaming, precio, max_perfiles FROM lista_maestra WHERE nombre_cuenta = ?");
        $stmtStreaming->execute([$streaming]);
        $streamingData = $stmtStreaming->fetch(PDO::FETCH_ASSOC);
        
        if (!$streamingData) {
            $conn->rollBack();
            throw new Exception("Streaming no encontrado: $streaming");
        }
        
        $id_streaming = $streamingData['id_streaming'];
        $precio = $streamingData['precio'];
        $maxPerfiles = $streamingData['max_perfiles'];

        $clienteID = null;

        if ($perfilEnUso) {
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
        }

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

        // **Verificación de Duplicados en la Tabla 'perfil' con Excepción cuando deben es NULL**
        if (!$perfilEnUso) {
            $stmtCheckPerfil = $conn->prepare("SELECT * FROM perfil WHERE idCuenta = ? AND customerMail = ? AND operador = ? AND pinPerfil = ? AND fechaPerfil IS NULL");
            $stmtCheckPerfil->execute([$idCuenta, $customerMail, $operador, $pinPerfil ? $pinPerfil : 0]);
        } else {
            $stmtCheckPerfil = $conn->prepare("SELECT * FROM perfil WHERE idCuenta = ? AND customerMail = ? AND operador = ? AND pinPerfil = ? AND fechaPerfil = ?");
            $stmtCheckPerfil->execute([$idCuenta, $customerMail, $operador, $pinPerfil ? $pinPerfil : 0, $fechaPerfil]);
        }

        $perfilData = $stmtCheckPerfil->fetch(PDO::FETCH_ASSOC);

        if ($perfilData) {
            // Si los datos ya existen, imprimir un mensaje y no insertar
            echo "Datos duplicados encontrados en perfil: idCuenta = $idCuenta, customerMail = $customerMail, operador = $operador, pinPerfil = $pinPerfil, fechaPerfil = $fechaPerfil\n";
        } else {
            // Verificar el límite de perfiles por cuenta utilizando max_perfiles
            $stmtCheckMaxPerfiles = $conn->prepare("SELECT COUNT(*) AS totalPerfiles FROM perfil WHERE idCuenta = ?");
            $stmtCheckMaxPerfiles->execute([$idCuenta]);
            $totalPerfilesData = $stmtCheckMaxPerfiles->fetch(PDO::FETCH_ASSOC);
            $totalPerfiles = $totalPerfilesData['totalPerfiles'];

            if ($totalPerfiles >= $maxPerfiles) {
                throw new Exception("Límite de perfiles alcanzado para idCuenta = $idCuenta. Máximo permitido: $maxPerfiles");
            }

            // Calcular el precio unitario con el descuento aplicado
            $stmtContabilidad = $conn->prepare("SELECT COUNT(*) AS numCuentas FROM perfil WHERE idCuenta = ?");
            $stmtContabilidad->execute([$idCuenta]);
            $cuentaCountData = $stmtContabilidad->fetch(PDO::FETCH_ASSOC);
            $numCuentas = $cuentaCountData['numCuentas'];
            
            $valorDescuento = ($numCuentas > 0) ? ($numCuentas * 1000 / $numCuentas) : 0;
            $precioUnitario = $precio - $valorDescuento;

            // Inserta los datos en la tabla perfil
            $stmtPerfil = $conn->prepare("INSERT INTO perfil (clienteID, idCuenta, id_streaming, customerMail, operador, pinPerfil, fechaPerfil, precioUnitario) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmtPerfil->execute([$clienteID, $idCuenta, $id_streaming, $customerMail, $operador, $pinPerfil ? $pinPerfil : 0, $fechaPerfil, $precioUnitario]);
        }

        if ($perfilEnUso) {
            // Calcular el valor de la deuda y el descuento
            $stmtContabilidad = $conn->prepare("SELECT COUNT(*) AS numCuentas FROM datosCuenta WHERE correo = ?");
            $stmtContabilidad->execute([$correo]);
            $cuentaCountData = $stmtContabilidad->fetch(PDO::FETCH_ASSOC);
            $numCuentas = $cuentaCountData['numCuentas'];

            $valorDeuda = $numCuentas * $precio;
            $valorDescuento = ($numCuentas - 1) * 1000; // Descuento de 1000 por cada cuenta adicional

            // Inserta los datos en contabilidad
            $stmtContabilidad = $conn->prepare("INSERT INTO contabilidad (clienteID, deben, valorDeuda, valorDescuento) VALUES (?, ?, ?, ?)");
            $stmtContabilidad->execute([$clienteID, convertirFecha($deben), $valorDeuda, $valorDescuento]);
        }

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

// Ejemplo de cómo procesar múltiples registros en un CSV
$datosCSV = [
    ['AMAZON', '', '', '', '', 'estivlol459@gmail.com', '331256', '', '', '', ''],
    ['AMAZON', '', '', '', '', 'mguelstr+yyrnd@gmail.com', 'Moneti23@', '', '', '', ''],
    // Otros registros...
];

foreach ($datosCSV as $fila) {
    insertarDatos(...$fila);
}
?>
