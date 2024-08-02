<?php


$host = "localhost";
$db_name = "estavi0_sheerit";
$username = "estavi0_sheerit";
$password = "26o6ssCOA^";


try {
    $conn = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Primera consulta para obtener IDs de 'perfil' donde 'fechaPerfil' no es NULL-l
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


    // Aquí puedes procesar $infoCompleta, por ejemplo, mostrarla en una tabla HTML
   header('Content-Type: application/json');
echo json_encode($infoCompleta);

} catch (PDOException $exception) {
    echo "Error de conexión: " . $exception->getMessage();
    exit;
}

?>
