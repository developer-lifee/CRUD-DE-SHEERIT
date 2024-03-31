<?php
$host = "localhost";
$db_name = "id20971532_sheerit";
$username = "id20971532_root";
$password = "26o6ssCOA^";

try {
    $conn = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
    echo "Error de conexión: " . $exception->getMessage();
}
?>
