<?php
// Incluye el archivo de conexión a la base de datos
include_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $streaming = $_POST["streaming"];
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $whatsapp = $_POST["whatsapp"];
    $correo = $_POST["correo"];
    $contraseña = $_POST["contraseña"];
    $perfil = $_POST["perfil"];
    $deben = $_POST["deben"];

    $sql = "INSERT INTO elneflis (streaming, nombre, apellido, whatsapp, correo, contraseña, perfil, deben) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$streaming, $nombre, $apellido, $whatsapp, $correo, $contraseña, $perfil, $deben]);

    echo "Datos ingresados correctamente";
}
?>
