<?php
include 'conexion.php'; 

try {
    $query = "SELECT *
    FROM datos_de_cliente AS ddc
    CROSS JOIN perfil AS p ON ddc.clienteID = p.clienteID
    CROSS JOIN datosCuenta AS dc ON p.idCuenta = dc.idCuenta
    CROSS JOIN contabilidad AS c ON ddc.clienteID = c.clienteID";
    $stmt = $conn->query($query);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
    echo json_encode($result);
} catch (PDOException $exception) {
    echo "Error al obtener los datos: " . $exception->getMessage();
}
?>


