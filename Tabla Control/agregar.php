<?php
// Incluye el archivo de conexión a la base de datos
include_once 'conexion.php';

// Realiza una consulta SQL para obtener los datos de la tabla 'elneflis'
$sql = "SELECT streaming, nombre, apellido, whatsapp, correo, contrasena,  deben, perfil FROM elneflis";
$result = $conn->query($sql);

// Inicializa un array para almacenar los datos
$data = array();

if ($result) {
    // Itera sobre los resultados y agrega cada fila al array
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }

    // Convierte el array a formato JSON y lo imprime
    echo json_encode($data);
} else {
    echo "Error al obtener los datos de la base de datos: " . $conn->error;
}
?>

