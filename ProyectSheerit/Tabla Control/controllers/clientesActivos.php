<?php

require '../../../../sys/conexion.php';

try {
    // Primera consulta para obtener IDs de 'perfil' donde 'fechaPerfil' no es NULL
    $queryPerfil = "SELECT idCuenta, clienteID, id_streaming, fechaPerfil, metodoPago FROM perfil WHERE fechaPerfil IS NOT NULL";
    $stmtPerfil = $conn->prepare($queryPerfil);
    $stmtPerfil->execute();
    $perfiles = $stmtPerfil->fetchAll(PDO::FETCH_ASSOC);

    // Preparación de las consultas para datos adicionales
    $queryDatosCuenta = "SELECT correo, clave FROM datosCuenta WHERE idCuenta = :idCuenta";
    $queryDatosCliente = "SELECT nombre, apellido, numero, nombreContacto FROM datos_de_cliente WHERE clienteID = :clienteID";
    $queryListaMaestra = "SELECT nombre_cuenta, precio FROM lista_maestra WHERE id_streaming = :id_streaming";

    $infoCompleta = [];

    foreach ($perfiles as $perfil) {
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

        // Combinar toda la información, manejando posibles datos faltantes
        $infoCompleta[] = array_merge(
            $perfil,
            $datosCuenta ? $datosCuenta : [],
            $datosCliente ? $datosCliente : [],
            $datosListaMaestra ? $datosListaMaestra : []
        );
    }

    // Aquí puedes procesar $infoCompleta, por ejemplo, mostrarla en una tabla HTML
    header('Content-Type: application/json');
    echo json_encode($infoCompleta);
} catch (PDOException $e) {
    // Log the error message
    error_log("Error en clientesActivos.php: " . $e->getMessage());

    // Return a JSON response with the error message
    header('Content-Type: application/json', true, 500);
    echo json_encode(["error" => "Error interno del servidor. Por favor, inténtelo de nuevo más tarde."]);
}
?>
