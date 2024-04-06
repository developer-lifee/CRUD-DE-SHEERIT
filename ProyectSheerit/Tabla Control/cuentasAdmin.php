<?php
$host = "localhost";
$db_name = "id20971532_sheerit";
$username = "id20971532_root";
$password = "26o6ssCOA^";

try {
    $conn = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta SQL para obtener los datos de la tabla datosCuenta
    $sql = "SELECT dc.idCuenta, dc.correo, dc.clave, dc.fechaCuenta, dc.id_streaming, lm.nombre_cuenta 
            FROM datosCuenta dc 
            INNER JOIN lista_maestra lm ON dc.id_streaming = lm.id_streaming";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Obtener los resultados de la consulta como un array asociativo
    $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Enviar los datos como respuesta JSON
    echo json_encode($datos);

} catch (PDOException $exception) {
    // En caso de error, enviar un mensaje de error JSON
    echo json_encode(array('error' => 'Error de conexión: ' . $exception->getMessage()));
    exit;
}
?>
