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
    exit;
}

if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == UPLOAD_ERR_OK) {
    $csvTempFile = $_FILES['csv_file']['tmp_name'];

    if (($handle = fopen($csvTempFile, 'r')) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            if ($data[0] == 'streaming') {
                continue;
            }

            $streaming = $data[0];
            $nombre = $data[1];
            $apellido = $data[2];
            $whatsapp = $data[3];
            $contacto = $data[4];
            $correo = $data[5];

            insertarDatos($streaming, $nombre, $apellido, $whatsapp, $contacto, $correo, "", "", "", "");

            if ($insertado) {
                echo "Datos insertados correctamente para $nombre $apellido<br>";
            } else {
                echo "Error al insertar datos para $nombre $apellido<br>";
            }
        }

        fclose($handle);
    } else {
        echo "Error al abrir el archivo CSV";
    }

    $conn = null;
} else {
    echo "Error al subir el archivo CSV";
}

function insertarDatos($streaming, $nombre, $apellido, $whatsapp, $contacto, $correo, $contrasena, $customerMail, $operador, $pinPerfil, $deben) {
    global $conn;
    $conn->beginTransaction();

    try {
        // Inserta los datos en la base de datos según lo necesitas
        // ...

        $conn->commit();
        return true; // Éxito
    } catch (Exception $e) {
        $conn->rollBack();
        echo "Error al insertar los datos: " . $e->getMessage() . "<br>";
        return false; // Error
    }
}
?>