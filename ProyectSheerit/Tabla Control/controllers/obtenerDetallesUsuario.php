
<?php
require '../../../../sys/conexion.php';

if (isset($_GET['usuarioID'])) {
    $usuarioID = intval($_GET['usuarioID']);

    // Consulta para obtener los datos del usuario
    $queryUsuario = "SELECT nombre, apellido FROM datos_de_cliente WHERE clienteID = :usuarioID";
    $stmtUsuario = $conn->prepare($queryUsuario);
    $stmtUsuario->bindParam(':usuarioID', $usuarioID);
    $stmtUsuario->execute();
    $usuario = $stmtUsuario->fetch(PDO::FETCH_ASSOC);

    // Consulta para obtener las cuentas del usuario
    $queryCuentas = "SELECT correo, clave, nombre_cuenta, precio, metodoPago 
                     FROM perfil 
                     JOIN datosCuenta ON perfil.idCuenta = datosCuenta.idCuenta
                     JOIN lista_maestra ON perfil.id_streaming = lista_maestra.id_streaming
                     WHERE perfil.clienteID = :usuarioID";
    $stmtCuentas = $conn->prepare($queryCuentas);
    $stmtCuentas->bindParam(':usuarioID', $usuarioID);
    $stmtCuentas->execute();
    $cuentas = $stmtCuentas->fetchAll(PDO::FETCH_ASSOC);

    if ($usuario && $cuentas) {
        echo json_encode([
            "usuario" => $usuario,
            "cuentas" => $cuentas
        ]);
    } else {
        echo json_encode(["error" => "No se encontraron datos"]);
    }
} else {
    echo json_encode(["error" => "Parámetros inválidos"]);
}
?>