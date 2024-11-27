<?php

require '../../../../sys/conexion.php';

// ...existing code...

$queryPerfil = "SELECT idCuenta, clienteID, id_streaming, fechaPerfil, metodoPago FROM perfil WHERE fechaPerfil IS NOT NULL";
$stmtPerfil = $conn->prepare($queryPerfil);
$stmtPerfil->execute();
$perfiles = $stmtPerfil->fetchAll(PDO::FETCH_ASSOC);

// ...existing code...

foreach ($perfiles as $perfil) {
    // ...existing code...

    // Datos de cuenta
    $stmtDatosCuenta = $conn->prepare($queryDatosCuenta);
    $stmtDatosCuenta->execute(['idCuenta' => $perfil['idCuenta']]);
    $datosCuenta = $stmtDatosCuenta->fetch(PDO::FETCH_ASSOC);

    // Datos del cliente
    $stmtDatosCliente = $conn->prepare($queryDatosCliente);
    $stmtDatosCliente->execute(['clienteID' => $perfil['clienteID']]);
    $datosCliente = $stmtDatosCliente->fetch(PDO::FETCH_ASSOC);

    // Datos de lista maestra (streaming)
    $stmtListaMaestra = $conn->prepare($queryListaMaestra);
    $stmtListaMaestra->execute(['id_streaming' => $perfil['id_streaming']]);
    $datosListaMaestra = $stmtListaMaestra->fetch(PDO::FETCH_ASSOC);

    // Verificar si $datosListaMaestra es un array válido antes de combinarlo
    if (is_array($datosListaMaestra)) {
        // Combinar toda la información
        $infoCompleta[] = array_merge($perfil, $datosCuenta, $datosCliente, $datosListaMaestra);
    } else {
        // Manejar el caso en el que la consulta no devolvió resultados
        // Por ejemplo, puedes agregar un valor predeterminado o ignorar esta entrada
        $infoCompleta[] = array_merge($perfil, $datosCuenta, $datosCliente);
    }
}

// ...existing code...
header('Content-Type: application/json');
echo json_encode($infoCompleta);
?>
